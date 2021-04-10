<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Tender;
use App\Models\Currency;
use Monolog\Logger;
use Orchestra\Parser\Xml\Facade as XmlParser;
use phpDocumentor\Reflection\Types\Null_;
use Symfony\Component\DomCrawler\Crawler;
use GuzzleHttp;

//TODO: Подумать на какие сервисы можно разбить этот класс
//TODO: Переработать миграции и таблицы в бд, так как некоторую инфу не возможно вытащить
//TODO: Подумать что делать с регионами и адресами, как их хрнаить в бд?
class XmlTenderParser
{
    private CustomerService $customerService;
    private GuzzleHttp\Client $client;

    public function __construct(CustomerService $customerService)
    {
        $this->client = new GuzzleHttp\Client([
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:56.0) Gecko/20100101 Firefox/56.0',
            ]
        ]);
    }

    public function parse()
    {
        $url = env('TENDER_BASE_URI') . '?morphology=on&pageNumber=1&sortDirection=false&recordsPerPage=_10&showLotsInfoHidden=false&sortBy=UPDATE_DATE&fz44=on&fz223=on&af=on&ca=on&pc=on&pa=on&priceContractAdvantages44IdNameHidden=%7B%7D&priceContractAdvantages94IdNameHidden=%7B%7D&currencyIdGeneral=-1&selectedSubjectsIdNameHidden=%7B%7D&OrderPlacementSmallBusinessSubject=on&OrderPlacementRnpData=on&OrderPlacementExecutionRequirement=on&orderPlacement94_0=0&orderPlacement94_1=0&orderPlacement94_2=0&contractPriceCurrencyId=-1&budgetLevelIdNameHidden=%7B%7D&nonBudgetTypesIdNameHidden=%7B%7D';
        $res = $this->client->get($url);//Завиток работает

        $items = new \SimpleXmlElement($res->getBody()->getContents());//Завиток 2 работает

        $i=0;
        foreach ($items->channel->item as $item) {
            $item->description = trim(preg_replace(['/<[^>]*>/', '/\s+/'], ' ', $item->description));
            $item->description = strip_tags($item->description);
            $currency = $this->getCurrency($item->description);
            $startRequestDate = preg_match('/Размещено:\s([0-9]{2}\.[0-9]{2}\.[0-9]{4})/m', $item->description, $startRequestDateMatches);
            $updateDate = preg_match('/Обновлено:\s([0-9]{2}\.[0-9]{2}\.[0-9]{4})/m', $item->description, $matches);
            $updateDate = preg_match('/Обновлено:\s([0-9]{2}\.[0-9]{2}\.[0-9]{4})/m', $item->description, $matches);
            $stage = preg_match('/Этап размещения:\s(\S+)\s(\S+)/m', $item->description, $matches);
            $stage = $matches[1] .' '. $matches[2];

            $curr = $this->getCurrency($item,$i);
            dd($curr);
            $tender = Tender::updateOrCreate([
                'currency' => $this->getCurrency($item),
                'start_request_date' => $startRequestDateMatches[0],
                'stage' => $stage,
            ]);

            if (preg_match('/Размещение выполняется по: 223-ФЗ/m', $item->description)) {
                dd($tender);
            }


//                $currency = $this->GetCurrency($item->description);
//                $startRequestDate = preg_match('/Размещено:\s([0-9]{2}\.[0-9]{2}\.[0-9]{4})/m', $item->description, $matches);
//                $updateDate = preg_match('/Обновлено:\s([0-9]{2}\.[0-9]{2}\.[0-9]{4})/m', $item->description, $matches);
//                $updateDate = preg_match('/Обновлено:\s([0-9]{2}\.[0-9]{2}\.[0-9]{4})/m', $item->description, $matches);
//                $stage = preg_match('/Этап размещения:\s(\S+)\s(\S+)/m', $item->description, $matches);
//                $stage = $matches[1] .' '. $matches[2];
//                $htmlDom = $this->GetPage($item->link);
//                $crawler = new Crawler($htmlDom['content']);
//                $customer = $this->customerService->ParseCustomerFz233($crawler);
//
//
////                $Customer = $this->GetCustomer($crawler);
//                $Number = $crawler->filter("tr:contains('Реестровый номер извещения') td")->last()->text();
//                break;
////
////                dump($matches[1]);
////                $htmlDom = $this->GetPage($item->link);
////                $crawler = new Crawler($htmlDom['content']);
////                $CustomerName = $crawler->filter("tr:contains('Наименование организации') td")->last()->text();
//
////                $Name = $crawler->filter("tr:contains('Наименование закупки') td")->last()->text();
////                $Name = $crawler->filter("tr:contains('Наименование закупки') td")->last()->text();
////                dump($Name);
//            }
//
////            echo "<li><a href = '{$item->link}' title = '$item->title'>",
////            htmlspecialchars($item->title), "</a> - ", $item->description, "</li>";
//        }
//
//

        }
    }


    //TODO: Подумать что делать в этом и подобных методах, создавать новую сущность через CreateOrUpdate и возвращать ее id?
    private function getCustomer($crawler)
    {
        $CustomerName = $crawler->filter("tr:contains('Наименование организации') td")->last()->text();
        $Inn = $crawler->filter("tr:contains('ИНН') td")->last()->text();
        $Kpp = $crawler->filter("tr:contains('КПП') td")->last()->text();
        $Ogrn = $crawler->filter("tr:contains('ОГРН') td")->last()->text();
        $CpName = $crawler->filter("tr:contains('Контактное лицо') td")->last()->text();
        $CpEmail = $crawler->filter("tr:contains('Электронная почта') td")->last()->text();
        $CpPhone = $crawler->filter("tr:contains('Телефон') td")->last()->text();
        return 0;
    }

    private function getCurrency($itemDecs,$i)
    {
        $i+=1;
        echo $i;
        $check = preg_match('/Валюта:\s(\S+)\s(\S+)/m', $itemDecs, $matches);
        $currencyName = $matches[1] . ' ' . $matches[2];
        return $i;
//        return Currency::updateOrCreate([
//            'name' => $currencyName,
//        ]);

//        if (!$check) {
//            return null;
//        }
//        $currencyName = sprintf('%s %s', $matches[1], $matches[2]);
//        return Currency::updateOrCreate([
//            'name' => $currencyName,
//        ]);


    }
}
