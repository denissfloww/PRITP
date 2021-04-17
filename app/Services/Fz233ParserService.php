<?php


namespace App\Services;


use App\Models\Customer;
use App\Models\TenderObject;
use carono\okvad\Okvad2;
use GuzzleHttp;
use Symfony\Component\DomCrawler\Crawler;
use Carbon\Carbon;

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
        preg_match(
            '/\d{1,2}\.\d{1,2}\.\d{4}/',
            $crawler->filter("tr:contains('Дата и время окончания подачи заявок') td")->last()->text(),
            $dateMatch
        );


        $end_request_date = date_create_from_format('d.m.Y', $dateMatch[0]);
        $tender->end_request_date = $end_request_date;

        preg_match(
            '/\d{1,2}\.\d{1,2}\.\d{4}/',
            $crawler->filter("tr:contains('Дата подведения итогов') td")->last()->text(),
            $dateMatch
        );

        $result_date = date_create_from_format('d.m.Y', $dateMatch[0]);
        $tender->result_date = $result_date;

        $tender->save();
        $this->getObjects($tender->number, $tender);

    }

    private function parseName($crawler){
        return $crawler->filter("tr:contains('Наименование закупки') td")->last()->text();
    }

    private function parseCustomer($crawler)
    {
        $cp_email = $crawler->filter("tr:contains('Электронная почта') td");
        ddd($cp_email);
        if (empty($cp_email->nodes->get())){
            $cp_email = $crawler->filter("tr:contains('Адрес электронной почты') td");

        }

        $customerData = [
            'name' => $crawler->filter("tr:contains('Наименование организации') a")->last()->text(),
            'inn' => $crawler->filter("tr:contains('ИНН') td")->last()->text(),
            'kpp'=> $crawler->filter("tr:contains('КПП') td")->last()->text(),
            'ogrn' => $crawler->filter("tr:contains('ОГРН') td")->last()->text() ,
            'region' => 'test',
            'region_id' => 0,
            'place' => 'test',
            'place_id' => 0,
            'cp_name' => $crawler->filter("tr:contains('Контактное лицо') td")->last()->text(),
            'cp_email' => $cp_email->last()->text(),
            'cp_phone' => $crawler->filter("tr:contains('Телефон') td")->last()->text(),
        ];
        return Customer::updateOrCreate($customerData);


//        $customer = Customer::where('inn', $Inn);
//        if ($customer === null) {
//            $customer = Customer::create($customerData);
//        } else {
//            $customer->update($customerData);
//        }
//        $customer->save();
    }

    private function getObjects($regNumber, $tender){
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
        foreach ($objectArray as $object)
        {
            if(preg_match('/\w/m', $object))
            {
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
}
