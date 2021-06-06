<?php


namespace App\Services\ExcelExporter;
use App\Models\Tender;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Facades\Excel;


class ExportService implements FromArray
{
    protected $tenders;
    public function __construct($tenders)
    {
        $this->tenders = $tenders;
    }

    public function array(): array
    {
        $exportAttr = [
            [
            'Идентификационный номер',
            'Название',
            'Ссылка',
            'Дата начала подачи заявок',
            'Дата окончания подачи заявок',
            'Наименование заказчика',
            'ОГРН',
            'КПП',
            'Адрес',
            'ФИО контактного лица',
            'Электронная почта контактного лица',
            'Номер телефона контактного лица',
            'ФЗ',
            'Валюта',
            'Этап',
                ],
        ];
        foreach ($this->tenders as $tender){
            $exportAttr[] = [
                [
                    $tender->number,
                    $tender->name,
                    $tender->source_url,
                    $tender->start_request_date ?? null,
                    $tender->end_request_date,
                    $tender->customer->name,
                    $tender->customer->ogrn,
                    $tender->customer->kpp,
                    $tender->customer->location,
                    $tender->customer->cp_name,
                    $tender->customer->cp_email,
                    $tender->customer->cp_phone,
                    $tender->type->name,
                    $tender->currency->name,
                    $tender->stage->name,
                    ],
                ];
        }
        return $exportAttr;
    }
}
