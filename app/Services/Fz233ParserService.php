<?php


namespace App\Services;


use App\Models\Customer;
use carono\okvad\Okvad2;
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
//        set_time_limit(600);
        $test = Okvad2::getByCode('42.99');
        dd($test);
        $htmlDom = $this->client->get($tender->source_url);
        $crawler = new Crawler($htmlDom->getBody()->getContents());
        $customer = $this->parseCustomer($crawler);

    }

    private function parseCustomer($crawler)
    {
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
            'cp_email' => $crawler->filter("tr:contains('Электронная почта') td")->last()->text(),
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
}
