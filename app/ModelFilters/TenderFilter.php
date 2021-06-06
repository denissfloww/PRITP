<?php

namespace App\ModelFilters;

use carono\okvad\Okvad2;
use EloquentFilter\ModelFilter;

class TenderFilter extends ModelFilter
{
    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relationMethod => [input_key1, input_key2]].
     *
     * @var array
     */
    public $relations = [];

    public function name($name)
    {
        return $this->where('name', 'ilike', "%$name%");
    }

    public function number($number)
    {
        return $this->where('number', $number);
    }

    public function description($description)
    {
        return $this->where('description', 'ilike', "%$description%");
    }

    public function sourceUrl($sourceUrl)
    {
        return $this->where('source_url', 'ilike', "%$sourceUrl%");
    }

    public function startRequestDate($startRequestDate)
    {
        return $this->where('start_request_date', $startRequestDate);
    }

    public function endRequestDate($endRequestDate)
    {
        return $this->where('end_request_date', $endRequestDate);
    }

    public function nmcPrice($nmcPrice)
    {
        return $this->where('nmc_price', $nmcPrice);
    }

    public function createdAt($createdAt)
    {
        return $this->where('created_at', $createdAt);
    }


    public function customer($id)
    {
        return $this->related('customer', 'customer_id', $id);
    }

    public function customerName($name)
    {
        return $this->related('customer', 'name', 'ilike', "%$name%");
    }

    public function customerInn($inn)
    {
        return $this->related('customer', 'inn', $inn);
    }

    public function customerOgrn($ogrn)
    {
        return $this->related('customer', 'ogrn', $ogrn);
    }

    public function customerKpp($kpp)
    {
        return $this->related('customer', 'kpp', $kpp);
    }

    public function customerLocation($location)
    {
        return $this->related('customer', 'location','ilike', "%$location%");
    }

    public function customerContactPhone($contactPhone)
    {
        return $this->related('customer', 'cp_phone','ilike', "%$contactPhone%");
    }

    public function customerContactName($customerName)
    {
        return $this->related('customer', 'cp_name','ilike', "%$customerName%");
    }


    public function type($id)
    {
        return $this->related('type', 'type_id', $id);
    }

    public function typeName($name){
        return $this->related('type', 'name','ilike', "%$name%");
    }


    public function currency($id)
    {
        return $this->related('currency', 'currency_id', $id);
    }

    public function currencyName($name)
    {
        return $this->related('currency', 'name', 'ilkie', "%$name%");
    }

    public function stage($id)
    {
        return $this->related('stage', 'stage_id', $id);
    }

    public function stageName($name)
    {
        return $this->related('stage', 'name','ilike', "%$name%");
    }

    public function objectsName($name)
    {
        return $this->related('objects', 'name','ilike', "%$name%");
    }

    public function objectsOkvad($okvad2Classifiers)
    {
        $okvad2Classifiers = explode(',', $okvad2Classifiers);
        return $this->whereHas('objects', function($query) use ($okvad2Classifiers)
        {
            return $query->whereIn('okvad2_classifier', $okvad2Classifiers);
        });
    }
}
