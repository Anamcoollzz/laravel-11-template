<?php

namespace App\Repositories;

use App\Models\BankDeposit;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

class BankDepositRepository extends Repository
{

    /**
     * constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->model = new BankDeposit();
    }

    /**
     * get data for yajra datatables
     *
     * @param mixed $params
     * @return Response
     */
    public function getYajraDataTables($additionalParams = null)
    {
        $query = $this->query()->when(request('order')[0]['column'] == 0, function ($query) {
            $query->latest();
        })
            ->with(['createdBy', 'lastUpdatedBy']);
        $editColumns = [
            'currency'         => fn(BankDeposit $item) => dollar($item->currency),
            'currency_idr'     => fn(BankDeposit $item) => rp($item->currency_idr),
            'select2_multiple' => '{{implode(", ", $select2_multiple)}}',
            'checkbox'         => '{{implode(", ", $checkbox)}}',
            'checkbox2'        => '{{implode(", ", $checkbox2)}}',
            'tags'             => 'stisla.crud-examples.tags',
            'file'             => 'stisla.crud-examples.file',
            'image'            => fn(BankDeposit $item) => view('stisla.crud-examples.image', ['file' => $item->image, 'item' => $item]),
            'barcode'          => fn(BankDeposit $item) => \Milon\Barcode\Facades\DNS1DFacade::getBarcodeHTML($item->barcode, 'C39', 1, 10),
            'qr_code'          => fn(BankDeposit $item) => \Milon\Barcode\Facades\DNS2DFacade::getBarcodeHTML($item->qr_code, 'QRCODE', 3, 3),
            'color'            => 'stisla.crud-examples.color',
            'created_at'       => '{{\Carbon\Carbon::parse($created_at)->addHour(7)->format("Y-m-d H:i:s")}}',
            'updated_at'       => '{{\Carbon\Carbon::parse($updated_at)->addHour(7)->format("Y-m-d H:i:s")}}',
            // 'created_by'       => fn(BankDeposit $crudExample) => $crudExample->createdBy ? $crudExample->createdBy->name : '-',
            // 'last_updated_by'  => fn(BankDeposit $crudExample) => $crudExample->lastUpdatedBy ? $crudExample->lastUpdatedBy->name : '-',
            'action'           => function (BankDeposit $crudExample) use ($additionalParams) {
                $isAjaxYajra = Route::is('crud-examples.index-ajax-yajra') || request('isAjaxYajra') == 1;
                $data = array_merge($additionalParams ? $additionalParams : [], [
                    'item'        => $crudExample,
                    'isAjaxYajra' => $isAjaxYajra,
                ]);
                return view('stisla.includes.forms.buttons.btn-action', $data);
            }
        ];
        $params = [
            'editColumns' => $editColumns,
            'rawColumns'  => ['tags', 'file', 'color', 'action', 'image', 'barcode', 'qr_code'],
            'addColumns'  => [
                'created_by' => function (BankDeposit $item) {
                    return $item->createdBy ? $item->createdBy->name : '-';
                },
                'last_updated_by' => function (BankDeposit $item) {
                    return $item->lastUpdatedBy ? $item->lastUpdatedBy->name : '-';
                }
            ]
        ];
        return $this->generateDataTables($query, $params);
    }

    /**
     * get yajra columns
     *
     * @return string
     */
    public function getYajraColumns()
    {
        return json_encode([
            [
                'data'       => 'DT_RowIndex',
                'name'       => 'DT_RowIndex',
                'searchable' => false,
                'orderable'  => false
            ],
            ['data' => 'text', 'name' => 'text'],
            ['data' => 'barcode', 'name' => 'barcode'],
            ['data' => 'qr_code', 'name' => 'qr_code'],
            ['data' => 'email', 'name' => 'email'],
            ['data' => 'number', 'name' => 'number'],
            ['data' => 'currency', 'name' => 'currency'],
            ['data' => 'currency_idr', 'name' => 'currency_idr'],
            ['data' => 'select', 'name' => 'select'],
            ['data' => 'select2', 'name' => 'select2'],
            ['data' => 'select2_multiple', 'name' => 'select2_multiple'],
            ['data' => 'textarea', 'name' => 'textarea'],
            ['data' => 'radio', 'name' => 'radio'],
            ['data' => 'checkbox', 'name' => 'checkbox'],
            ['data' => 'checkbox2', 'name' => 'checkbox2'],
            ['data' => 'tags', 'name' => 'tags'],
            ['data' => 'file', 'name' => 'file'],
            ['data' => 'image', 'name' => 'image'],
            ['data' => 'date', 'name' => 'date'],
            ['data' => 'time', 'name' => 'time'],
            ['data' => 'color', 'name' => 'color'],
            ['data' => 'created_at', 'name' => 'created_at'],
            ['data' => 'updated_at', 'name' => 'updated_at'],
            ['data' => 'created_by', 'name' => 'createdBy.name'],
            ['data' => 'last_updated_by', 'name' => 'lastUpdatedBy.name'],
            [
                'data' => 'action',
                'name' => 'action',
                'orderable' => false,
                'searchable' => false
            ],
        ]);
    }
}
