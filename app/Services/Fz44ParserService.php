<?php


namespace App\Services;


use App\Models\Customer;
use carono\okvad\Okvad2;

class Fz44ParserService
{
    public function parse($tender)
    {
        $test = Okvad2::getByCode('01.24');
        dd($test);
    }
}
