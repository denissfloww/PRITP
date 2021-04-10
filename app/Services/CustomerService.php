<?php

namespace App\Services;

use App\Models\Customer;
use Orchestra\Parser\Xml\Facade as XmlParser;
use Symfony\Component\DomCrawler\Crawler;

//TODO: Подумать на какие сервисы можно разбить этот класс
//TODO: Переработать миграции и таблицы в бд, так как некоторую инфу не возможно вытащить
//TODO: Подумать что делать с регионами и адресами, как их хрнаить в бд?
class CustomerService
{


    //TODO: Подумать что делать в этом и подобных методах, создавать новую сущность через CreateOrUpdate и возвращать ее id?
    public function ParseCustomerFz233($crawler)
    {
        $customerName = $crawler->filter("tr:contains('Наименование организации') a")->last()->text();
        $inn = $crawler->filter("tr:contains('ИНН') td")->last()->text();
        $kpp = $crawler->filter("tr:contains('КПП') td")->last()->text();
        $ogrn = $crawler->filter("tr:contains('ОГРН') td")->last()->text();
        $cpName = $crawler->filter("tr:contains('Контактное лицо') td")->last()->text();
        $CpEmail = $crawler->filter("tr:contains('Электронная почта') td")->last()->text();
        $CpPhone = $crawler->filter("tr:contains('Телефон') td")->last()->text();

        $customerData = [
            'name' => $customerName,
            'inn' => $inn,
            'kpp'=> $kpp,
            'ogrn' => $ogrn ,
            'region' => 'test',
            'region_id' => 0,
            'place' => 'test',
            'place_id' => 0,
            'cp_name' => $cpName,
            'cp_email' => $CpEmail,
            'cp_phone' => $CpPhone,
        ];
        $customer = Customer::updateOrCreate($customerData);
        dd($customer);


//        $customer = Customer::where('inn', $Inn);
//        if ($customer === null) {
//            $customer = Customer::create($customerData);
//        } else {
//            $customer->update($customerData);
//        }
//        $customer->save();

        return $customer->id;
    }
}
