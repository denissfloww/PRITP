<?php


namespace App\Services\TenderParser;

use App\Models\Customer;
use App\Models\TenderObject;
use carono\okvad\Okvad2;
use Dadata\DadataClient;
use GuzzleHttp;
use Symfony\Component\DomCrawler\Crawler;

class Fz233ParserService
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
        $customer = $this->parseCustomer($crawler);
        $tender->customer()->associate($customer);
        $tender->name = $this->parseName($crawler);
        $tender->end_request_date = $this->getDate($crawler->filter("tr:contains('Дата и время окончания подачи заявок') td"));
        $tender->result_date = $this->getDate($crawler->filter("tr:contains('Дата подведения итогов') td"));
        $tender->save();
        $this->getObjects($tender->number, $tender);
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

    private function parseName($crawler)
    {
        return $crawler->filter("tr:contains('Наименование закупки') td")->last()->text();
    }

    private function parseCustomer($crawler)
    {
        $cp_phone = $crawler->filter("tr:contains('Номер контактного телефона') td");
        if ($cp_phone->count() == 0) {
            $cp_phone = $crawler->filter("tr:contains('Телефон') td");
            if ($cp_phone->count() == 0) {
                $cp_phone = $crawler->filter("tr:contains('Контактный телефон') td");
            }
        }

        $cp_email = $crawler->filter("tr:contains('Электронная почта') td");
        if ($cp_email->count() == 0) {
            $cp_email = $crawler->filter("tr:contains('Адрес электронной почты') td");
        }

        $cp_name = $crawler->filter("tr:contains('Контактное лицо') td");


        if ($cp_name->count() == 0) {
            $cp_name = 'Отсутствуют';
        } else {
            $cp_name = $cp_name->last()->text();
        }

        $location = $this->getLocation($crawler);

        $customerData = [
            'name' => $crawler->filter("table:contains('Наименование организации') tr:contains('Наименование организации') a")->last()->text(),
            'inn' => $crawler->filter("table:contains('Наименование организации') tr:contains('ИНН') td")->last()->text(),
            'kpp'=> $crawler->filter("table:contains('Наименование организации') tr:contains('КПП') td")->last()->text(),
            'ogrn' => $crawler->filter("table:contains('Наименование организации') tr:contains('ОГРН') td")->last()->text(),
            'location' => $location,
            'json_location' => json_encode($this->getLocationForDaData($location)),
            'cp_name' => $cp_name,
            'cp_email' => $cp_email->last()->text(),
            'cp_phone' => $cp_phone->last()->text(),
        ];
        return Customer::updateOrCreate(['inn' => $customerData['inn']], $customerData);
    }

    private function getObjects($regNumber, $tender)
    {
        $source_url = 'https://zakupki.gov.ru/223/purchase/public/purchase/info/lot-list.html';
        $htmlDom = $this->client->get($source_url, [
            'query' =>
                [
                    'regNumber' => $regNumber
                ]
        ]);

        $crawler = new Crawler($htmlDom->getBody()->getContents());

        $tableIndex = $crawler->filter('table:contains("Классификация по ОКВЭД2")')->filter('tr')->each(function ($tr, $i) {
            return $tr->filter('td')->each(function ($td, $i) {
                return $td->html();
            });
        });


        $objectArray = preg_split('<br>', $tableIndex[1][5]);//TODO:Исправить этот ужас с индексами (временное решение)
        foreach ($objectArray as $object) {
            if (preg_match('/\w/m', $object)) {
                preg_match('/((\d{1,3})+(\.\d{1,3})*)/m', $object, $objectMatches);
                $objectOkvad2 = $objectMatches[1];
                $objectName = preg_split('/((\d{1,3})+(\.\d{1,3})*)/m', $object)[1];

                $object = new TenderObject([
                    'name' => $objectName,
                    'okvad2_classifier' => $objectOkvad2,
                ]);

                $object->tender()->associate($tender);
                $object->save();
            }
        }
    }

    private function getLocation($crawler)
    {
        $location = $crawler->filter("tr:contains('Место нахождения') td")->last()->text();
        return $location;
    }

    public function getLocationForDaData($location){
        $token = env('DADATA_SECRET');
        $secret = env("DADATA_TOKEN");
        //тут место для дадаты
        return $location;
//        $dadata = new DadataClient($token, $secret);
//        $response = $dadata->clean("address", $location);
//        return $response;
    }
}
