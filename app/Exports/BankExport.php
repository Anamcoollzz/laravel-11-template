<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class BankExport implements FromView, ShouldAutoSize
{
    use Exportable;

    /**
     * data
     *
     * @var Collection
     */
    private Collection $data;

    /**
     * constructor method
     *
     * @param Collection $data
     * @return void
     */
    public function __construct(Collection $data)
    {
        $this->data = $data;
    }

    /**
     * export from view
     *
     * @return View
     */
    public function view(): View
    {
        return view('stisla.banks.table', [
            'data'     => $this->data,
            'isExport' => true
        ]);
    }
}
