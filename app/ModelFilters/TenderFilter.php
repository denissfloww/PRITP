<?php

namespace App\ModelFilters;

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
        return $this->where('name', 'LIKE', "%$name%");
    }

    public function number($number)
    {
        return $this->where('number', $number);
    }

    public function description($description)
    {
        return $this->where('description', 'LIKE', "%$description%");
    }

    public function sourceUrl($sourceUrl)
    {
        return $this->where('source_url', 'LIKE', "%$sourceUrl%");
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

    public function type($id)
    {
        return $this->related('type', 'type_id', $id);
    }

    public function currency($id)
    {
        return $this->related('currency', 'currency_id', $id);
    }

    public function stage($id)
    {
        return $this->related('stage', 'stage_id', $id);
    }
}
