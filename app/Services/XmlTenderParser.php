<?php


namespace App\Services;
use Orchestra\Parser\Xml\Facade as XmlParser;

class XmlTenderParser
{
    public function Parse(){
        $url = "https://zakupki.gov.ru/epz/order/extendedsearch/rss.html?morphology=on&pageNumber=1&sortDirection=false&recordsPerPage=_10&showLotsInfoHidden=false&sortBy=UPDATE_DATE&fz44=on&fz223=on&af=on&ca=on&pc=on&pa=on&priceContractAdvantages44IdNameHidden=%7B%7D&priceContractAdvantages94IdNameHidden=%7B%7D&currencyIdGeneral=-1&selectedSubjectsIdNameHidden=%7B%7D&OrderPlacementSmallBusinessSubject=on&OrderPlacementRnpData=on&OrderPlacementExecutionRequirement=on&orderPlacement94_0=0&orderPlacement94_1=0&orderPlacement94_2=0&contractPriceCurrencyId=-1&budgetLevelIdNameHidden=%7B%7D&nonBudgetTypesIdNameHidden=%7B%7D";

        $resp = $this->url_get($url);
        if ($resp['error']) {
            echo "<p>", htmlspecialchars($resp['error']), "</p>";
            exit;
        }

        $items = new \SimpleXmlElement($resp['content']);

        echo "<ul>";
        foreach($items->channel->item as $item) {
            echo "<li><a href = '{$item->link}' title = '$item->title'>",
            htmlspecialchars($item->title), "</a> - ", $item->description, "</li>";
        }
        echo "</ul>";
    }

    private function url_get($url) {
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
}
