<?php


namespace App\Services;
use Orchestra\Parser\Xml\Facade as XmlParser;
use Symfony\Component\DomCrawler\Crawler;

//TODO: Подумать на какие сервисы можно разбить этот класс
//TODO: Переработать миграции и таблицы в бд, так как некоторую инфу не возможно вытащить
//TODO: Подумать что делать с регионами и адресами, как их хрнаить в бд?
class XmlTenderParser
{
    public function Parse(){
        $url = "https://zakupki.gov.ru/epz/order/extendedsearch/rss.html?morphology=on&pageNumber=1&sortDirection=false&recordsPerPage=_10&showLotsInfoHidden=false&sortBy=UPDATE_DATE&fz44=on&fz223=on&af=on&ca=on&pc=on&pa=on&priceContractAdvantages44IdNameHidden=%7B%7D&priceContractAdvantages94IdNameHidden=%7B%7D&currencyIdGeneral=-1&selectedSubjectsIdNameHidden=%7B%7D&OrderPlacementSmallBusinessSubject=on&OrderPlacementRnpData=on&OrderPlacementExecutionRequirement=on&orderPlacement94_0=0&orderPlacement94_1=0&orderPlacement94_2=0&contractPriceCurrencyId=-1&budgetLevelIdNameHidden=%7B%7D&nonBudgetTypesIdNameHidden=%7B%7D";

        $resp = $this->GetPage($url);
        if ($resp['error']) {
            echo "<p>", htmlspecialchars($resp['error']), "</p>";
            exit;
        }

        $items = new \SimpleXmlElement($resp['content']);

        foreach($items->channel->item as $item) {
            $item->description = trim(preg_replace(['/<[^>]*>/','/\s+/'],' ', $item->description));
//            $item->description = strip_tags($item->description);
            if ((bool) preg_match('/Размещение выполняется по: 223-ФЗ/m', $item->description)){
                $currency = $this->GetCurrency($item->description);
                $startRequestDate = preg_match('/Размещено:\s([0-9]{2}\.[0-9]{2}\.[0-9]{4})/m', $item->description, $matches);
                $updateDate = preg_match('/Обновлено:\s([0-9]{2}\.[0-9]{2}\.[0-9]{4})/m', $item->description, $matches);
                $updateDate = preg_match('/Обновлено:\s([0-9]{2}\.[0-9]{2}\.[0-9]{4})/m', $item->description, $matches);
                $htmlDom = $this->GetPage($item->link);
                $crawler = new Crawler($htmlDom['content']);
                $Customer = $this->GetCustomer($crawler);
                $Number = $crawler->filter("tr:contains('Реестровый номер извещения') td")->last()->text();
                break;
//
//                dump($matches[1]);
//                $htmlDom = $this->GetPage($item->link);
//                $crawler = new Crawler($htmlDom['content']);
//                $CustomerName = $crawler->filter("tr:contains('Наименование организации') td")->last()->text();

//                $Name = $crawler->filter("tr:contains('Наименование закупки') td")->last()->text();
//                $Name = $crawler->filter("tr:contains('Наименование закупки') td")->last()->text();
//                dump($Name);
            }

//            echo "<li><a href = '{$item->link}' title = '$item->title'>",
//            htmlspecialchars($item->title), "</a> - ", $item->description, "</li>";
        }



    }

    private function GetPage($url) {
        $userAgent = 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:56.0) Gecko/20100101 Firefox/56.0';

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $content = curl_exec($ch);

        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($content === false) {
            $error = curl_error($ch);
            $content = '';
        } elseif ($code != 200) {
            $error = 'Status: ' . $code;
            $content = '';
        } else {
            $error = false;
        }

        curl_close($ch);
        return compact('error', 'content');
    }

    //TODO: Подумать что делать в этом и подобных методах, создавать новую сущность через CreateOrUpdate и возвращать ее id?
    private function GetCustomer($crawler){
        $CustomerName = $crawler->filter("tr:contains('Наименование организации') td")->last()->text();
        $Inn = $crawler->filter("tr:contains('ИНН') td")->last()->text();
        $Kpp = $crawler->filter("tr:contains('КПП') td")->last()->text();
        $Ogrn = $crawler->filter("tr:contains('ОГРН') td")->last()->text();
        $CpName = $crawler->filter("tr:contains('Контактное лицо') td")->last()->text();
        $CpEmail = $crawler->filter("tr:contains('Электронная почта') td")->last()->text();
        $CpPhone = $crawler->filter("tr:contains('Телефон') td")->last()->text();
        dump($CpPhone);
        return 0;
    }

    private function GetCurrency($itemDecs){
        $currency = preg_match('/Валюта:\s(\S+)\s(\S+)/m', $itemDecs, $matches);
        $currency = $matches[1] . ' ' . $matches[2];
        dump($currency);
        return 0;
    }
}
