<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Tender;
use App\Models\Currency;
use App\Models\TenderStage;
use App\Models\TenderType;
use carono\okvad\Okvad2;
use Illuminate\Support\Facades\Log;
use Monolog\Logger;
use Orchestra\Parser\Xml\Facade as XmlParser;
use phpDocumentor\Reflection\Types\Null_;
use Symfony\Component\DomCrawler\Crawler;
use GuzzleHttp;

//TODO: Подумать на какие сервисы можно разбить этот класс
//TODO: Переработать миграции и таблицы в бд, так как некоторую инфу не возможно вытащить
//TODO: Подумать что делать с регионами и адресами, как их хрнаить в бд?
class XmlTenderParserService
{
    private Fz233ParserService $fz233ParserService;
    private Fz44ParserService $fz44ParserService;
    private GuzzleHttp\Client $client;

    public function __construct(Fz233ParserService $fz233ParserService, Fz44ParserService $fz44ParserService)
    {
        $this->client = new GuzzleHttp\Client([
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:56.0) Gecko/20100101 Firefox/56.0',
            ]
        ]);

        $this->fz233ParserService = $fz233ParserService;
        $this->fz44ParserService = $fz44ParserService;
    }

    public function parse()
    {
        $url = env('TENDER_BASE_URI') . '?morphology=on&pageNumber=1&sortDirection=false&recordsPerPage=_10&showLotsInfoHidden=false&sortBy=UPDATE_DATE&fz223=on&af=on&ca=on&pc=on&pa=on&priceContractAdvantages44IdNameHidden=%7B%7D&priceContractAdvantages94IdNameHidden=%7B%7D&currencyIdGeneral=-1&selectedSubjectsIdNameHidden=%7B%7D&OrderPlacementSmallBusinessSubject=on&OrderPlacementRnpData=on&OrderPlacementExecutionRequirement=on&orderPlacement94_0=0&orderPlacement94_1=0&orderPlacement94_2=0&contractPriceCurrencyId=-1&budgetLevelIdNameHidden=%7B%7D&nonBudgetTypesIdNameHidden=%7B%7D';
        $res = $this->client->get($url);//Завиток работает

        $items = new \SimpleXmlElement($res->getBody()->getContents());//Завиток 2 работает

        foreach ($items->channel->item as $item) {
            $description = trim(preg_replace(['/<[^>]*>/', '/\s+/'], ' ', $item->description));
            $description = strip_tags($description);
            $source_url = (string)$item->link;

            if(!preg_match('/https:\/\/zakupki\.gov\.ru/m', $source_url)){
                $source_url = 'https://zakupki.gov.ru' . $source_url;
            }

            preg_match('/regNumber=(\d+)/m', $source_url, $numbersMatches);
            $number = $numbersMatches[1];

            $tender = $this->makeTender($description, $source_url, $number);


            if (preg_match('/Размещение выполняется по: 223-ФЗ/m', $description)) {

                $tenderType = $this->getTenderType('233-ФЗ', 'Федеральный закон от 18 июля 2011 года № 223-ФЗ «О закупках товаров, работ, услуг отдельными видами юридических лиц» — федеральный закон Российской Федерации, регламентирующий порядок осуществления закупок отдельными видами юридических лиц.');
                $tender->type()->associate($tenderType);
                $this->fz233ParserService->parse($tender);
            }
            elseif (preg_match('/Размещение выполняется по: 44-ФЗ/m', $description)){
                $tenderType = $this->getTenderType('44-ФЗ', 'Федеральный закон № 44-ФЗ от 5 апреля 2013 года «О контрактной системе в сфере закупок товаров, работ, услуг для обеспечения государственных и муниципальных нужд» — Федеральный закон Российской Федерации, регламентирующий порядок осуществления закупок товаров, работ и услуг для обеспечения государственных и');
                $tender->type()->associate($tenderType);
                $this->fz44ParserService->parse($tender);
            }

            Log::info('Tender created', ['id' => $tender->id, 'name' => $tender->name]);


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

    private function getTenderType(string $name, string $description)
    {
        return TenderType::updateOrCreate([
            'name' => $name,
            'description' => $description,
        ]);
    }

    private function makeTender($description, $source_url, $number)
    {
        preg_match('/Размещено:\s([0-9]{2}\.[0-9]{2}\.[0-9]{4})/m',$description, $startRequestDateMatches);
//        preg_match('/Обновлено:\s([0-9]{2}\.[0-9]{2}\.[0-9]{4})/m', $description, $matches);

        $tender = Tender::where(['number' => $number])->get();
//        if (instanceof($tender) )

        $tender = Tender::updateOrCreate(['number' => $number], [
            'start_request_date' => date_create_from_format('d.m.Y', $startRequestDateMatches[1]),
            'source_url' => $source_url,
        ]);
        $tender->currency()->associate($this->getCurrency($description));
        $tender->stage()->associate($this->getStage($description));


        return $tender;
    }

    private function getStage($description)
    {
        preg_match('/Этап размещения:\s(\S+)\s(\S+)/m', $description, $matches);
        $stage = $matches[1] .' '. $matches[2];

        return TenderStage::updateOrCreate([
            'name' => $stage,
        ]);
    }

    private function getCurrency($itemDecs)
    {
        $currencyName = 'Российский рубль';
        if(preg_match('/Валюта:\s(\S+)\s(\S+)/m', $itemDecs, $currencyMatches)) {
            $currencyName = $currencyMatches[1] . ' ' . $currencyMatches[2];
        }

        return Currency::updateOrCreate([
            'name' => $currencyName
        ]);
    }
}
