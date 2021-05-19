<?php


namespace App\Services;

use App\Models\Customer;
use App\Models\TenderObject;
use carono\okvad\Okvad2;
use Symfony\Component\DomCrawler\Crawler;
use GuzzleHttp;

class Fz44ParserService
{
    private $client;
    public function __construct()
    {
        $this->client = new GuzzleHttp\Client([
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:56.0) Gecko/20100101 Firefox/56.0',
            ]
        ]);
    }

    public function parse($tender)
    {
        $htmlDom = $this->client->get($tender->source_url);
        $crawler = new Crawler($htmlDom->getBody()->getContents());
        $tender->name = $this->parseName($crawler);
        $customer = $this->parseCustomer($crawler);
        $tender->customer()->associate($customer);
        $tender->end_request_date = $this->getDate($crawler->filter("section:contains('Дата и время окончания срока подачи заявок') .section__info"));
        $tender->save();
        $this->getObjects($crawler, $tender);
    }

    private function parseName($crawler)
    {
        $name = $crawler->filter("section:contains('Наименование объекта закупки') .section__info");
        if ($name->count() == 0) {
            $name = 'Отсутствует';
        } else {
            $name = $name->last()->text();
        }
        return $name;
    }

    private function parseCustomer($crawler)
    {
        $name = $crawler->filter("section:contains('Организация, осуществляющая размещение') .section__info");
        if ($name->count() == 0) {
            $name = 'Отсутствуют';
        } else {
            $name = $name->text();
        }

        $cpPhone = $crawler->filter("section:contains('Номер контактного телефона') .section__info")->text();
        $cpEmail = $crawler->filter("section:contains('Адрес электронной почты') .section__info")->text();
        $cpName = $crawler->filter("section:contains('Ответственное должностное лицо') .section__info")->text();
        $linkOnCustomerInfo = $crawler->filter("span:contains('Заказчик') a");
        if ($linkOnCustomerInfo->count() == 0) {
            $linkOnCustomerInfo = $crawler->filter("section:contains('Размещение осуществляет') a");
        }
        $htmlDom = $this->client->get($linkOnCustomerInfo->attr('href'));
        $customerInfoCrawler = new Crawler($htmlDom->getBody()->getContents());
        $inn = $customerInfoCrawler->filter("section:contains('ИНН') .section__info")->text();
        $kpp = $customerInfoCrawler->filter("section:contains('КПП') .section__info")->text();
        $ogrn = $customerInfoCrawler->filter("section:contains('ОГРН') .section__info")->text();
        $customerData = [
            'name' => $name,
            'inn' => $inn,
            'kpp'=> $kpp,
            'ogrn' => $ogrn ,
            'region' => 'test',
            'region_id' => 0,
            'place' => 'test',
            'place_id' => 0,
            'cp_name' => $cpName,
            'cp_email' => $cpEmail,
            'cp_phone' => $cpPhone,
        ];
        return Customer::updateOrCreate(['inn' => $customerData['inn']], $customerData);
    }

    private function getDate($filter)
    {
        $dateHtml = $filter;

        if ($dateHtml->count() != 0) {
            if (preg_match(
                '/\d{1,2}\.\d{1,2}\.\d{4}/',
                $dateHtml->last()->text(),
                $dateMatch
            )) {
                return date_create_from_format('d.m.Y', $dateMatch[0]);
            }
            return null;
        }
        return null;
    }

    private function getObjects($crawler, $tender)
    {
        $tableIndex = $crawler->filter('div:contains("Информация об объекте закупки") tbody')->filter('tr')->each(function ($tr, $i) {
            return $tr->filter('td')->each(function ($td, $i) {
                return $td->html();
            });
        });
        $linkOnCustomerInfo = $crawler->filter("span:contains('Заказчик') a");
        if ($linkOnCustomerInfo->count() == 0) {
            $linkOnCustomerInfo = $crawler->filter("section:contains('Размещение осуществляет') a");
        }
        $htmlDom = $this->client->get($linkOnCustomerInfo->attr('href'));
        $customerInfoCrawler = new Crawler($htmlDom->getBody()->getContents());
        $customerAdditInfoLink = $customerInfoCrawler->filter("a:contains('ДОПОЛНИТЕЛЬНАЯ ИНФОРМАЦИЯ')")->attr('href');
        $customerAdditInfoLink = env('TENDER_DOMEN') . $customerAdditInfoLink;
        $htmlDom = $this->client->get($customerAdditInfoLink);
        $customerAdditInfoCrawler = new Crawler($htmlDom->getBody()->getContents());
        $textOkvad = $customerAdditInfoCrawler->filter("section:contains('ОКВЭД') .section__info")->text();
        preg_match('/((\d{1,3})+(\.\d{1,3})*)/m', $textOkvad, $okvadMatches);
        dump($tableIndex);
        //TODO:Надо пофиксить проблему с парсингом объектов закупки, а то там каждый раз рандомно заполняются
        foreach ($tableIndex as $object) {
            if (!empty($object[1])) {
                $object = new TenderObject([
                    'name' => $object[1],
                    'okvad2_classifier' => $okvadMatches[0],
                ]);
                $object->tender()->associate($tender);
                $object->save();
            }
        }
    }
}
