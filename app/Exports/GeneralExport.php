<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class GeneralExport implements FromView, ShouldAutoSize
{
    use Exportable;

    /**
     * data
     *
     * @var mixed
     */
    private $data;

    /**
     * view
     *
     * @var string
     */
    private string $view;

    /**
     * constructor method
     *
     * @param string $view
     * @param mixed $data
     * @return void
     */
    public function __construct(string $view, $data)
    {
        $this->view = $view;
        $this->data = $data;
    }

    /**
     * export from view
     *
     * @return View
     */
    // public function view(): View
    // {
    //     $data = $this->data;
    //     dd($data);
    //     // if ($data instanceof \Illuminate\Support\Collection)
    //     //     $data = $data->toArray();

    //     // return view($this->view, array_merge(['data' => $data, 'is_export'], ['is_export' => true]));
    //     echo view($this->view, array_merge(['data' => $data], ['is_export' => true]))->render();
    // }

    /**
     * export from view
     *
     * @return View
     */
    public function view(): View
    {
        return view($this->view, $this->data);
    }
}
