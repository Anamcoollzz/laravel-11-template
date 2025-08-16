<?php

namespace App\Imports;

use App\Models\CrudExample;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GeneralImport implements ToCollection, WithHeadingRow
{

    /**
     * To collection
     *
     * @return void
     */
    public function collection(Collection $rows)
    {
        $dateTime = date('Y-m-d H:i:s');
        $userId = Auth::id();
        foreach ($rows->chunk(30) as $chunkData) {
            $insertData = $chunkData->transform(function ($item) use ($dateTime, $userId) {
                $item->put('checkbox2', $item['checkbox_2']);
                $item = $item->except(['', 'checkbox_2', 'created_by', 'last_updated_by']);
                $item->put('created_at', $dateTime);
                $item->put('updated_at', $dateTime);
                // if (!$item['created_by'])
                $item->put('created_by_id', $userId);
                // else if ($item['created_by'] === '-')
                $item->put('created_by_id', $userId);
                // if ($item['last_updated_by'] === '-')
                // $item->put('last_updated_by_id', $userId);

                $item->put('currency', idr_to_double($item['currency']));
                $item->put('currency_idr', rp_to_double($item['currency_idr']));
                return $item;
            })->toArray();
            // dd($insertData);
            CrudExample::insert($insertData);
        }
    }
}
