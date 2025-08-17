<?php

namespace App\Http\Controllers;

use App\Exports\BankExport;
use App\Http\Requests\BankRequest;
use App\Http\Requests\ImportExcelRequest;
use App\Imports\BankImport;
use App\Models\Bank;
use App\Repositories\BankRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BankController extends StislaController
{

    /**
     * bank repository
     *
     * @var BankRepository
     */
    private BankRepository $bankRepository;

    /**
     * constructor method
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->icon           = 'fa fa-university';
        $this->bankRepository = new BankRepository;
        $this->viewFolder     = 'banks';

        $this->defaultMiddleware('Bank');
    }

    /**
     * get index data
     *
     * @return array
     */
    protected function getIndexData()
    {
        $isYajra     = Route::is('banks.index-yajra');
        $isAjax      = Route::is('banks.index-ajax');
        $isAjaxYajra = Route::is('banks.index-ajax-yajra');

        if ($isYajra || $isAjaxYajra) {
            $data = collect([]);
        } else {
            $data = $this->bankRepository->getFullData();
        }

        $defaultData = $this->getDefaultDataIndex(__('Bank'), 'Bank', 'banks');
        $users = [];

        if (Route::is('banks.index') || Route::is('banks.index-ajax')) {
            $users = $this->userRepository->getLatest();
        }

        return array_merge($defaultData, [
            'data'         => $data,
            'isYajra'      => $isYajra,
            'isAjax'       => $isAjax,
            'isAjaxYajra'  => $isAjaxYajra,
            'yajraColumns' => $this->bankRepository->getYajraColumns(),
            'users'        => $users,
        ]);
    }

    /**
     * prepare store data
     *
     * @param BankRequest $request
     * @return array
     */
    private function getStoreData(BankRequest $request)
    {
        $data = $request->only([
            'name',
            'bank_type',
        ]);
        // if ($request->hasFile('file')) {
        //     $data['file'] = $this->fileService->uploadBankFile($request->file('file'));
        // }
        // if ($request->hasFile('image')) {
        //     $data['image'] = $this->fileService->uploadBankFile($request->file('image'));
        // }
        // $data['currency'] = str_replace(',', '', $request->currency);
        // $data['currency_idr'] = str_replace('.', '', $request->currency_idr);

        return $data;
    }

    /**
     * get export data
     *
     * @return array
     */
    protected function getExportData(): array
    {
        $times    = date('Y-m-d_H-i-s');
        $filename = $times . '_banks';
        $data     = [
            'isExport'   => true,
            'pdf_name'   => $filename . '.pdf',
            'excel_name' => $filename . '.xlsx',
            'csv_name'   => $filename . '.csv',
            'json_name'  => $filename . '.json',
        ];
        return array_merge($this->getIndexData(), $data);
    }

    /**
     * showing bank page
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $data = $this->getIndexData();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data'    => view('stisla.banks.table', $data)->render(),
            ]);
        }

        return view('stisla.banks.index', $data);
    }

    /**
     * datatable yajra index
     *
     * @return Response
     */
    public function yajraAjax()
    {
        $defaultData = $this->getDefaultDataIndex(__('Bank'), 'Bank', 'banks');
        return $this->bankRepository->getYajraDataTables($defaultData);
    }

    /**
     * showing add new bank page
     *
     * @param Request $request
     * @return Response
     */
    public function create(Request $request)
    {
        $title      = __('Bank');
        $fullTitle  = __('Tambah Bank');
        $data       = $this->getDefaultDataCreate($title, 'banks');
        $data       = array_merge($data, [
            'selectOptions'   => get_options(10, true),
            'select2Options'  => get_options(10),
            'radioOptions'    => get_options(4),
            'checkboxOptions' => get_options(5),
            'fullTitle'       => $fullTitle,
        ]);
        if ($request->ajax()) {
            return view('stisla.banks.only-form', $data);
        }
        return view('stisla.banks.form', $data);
    }

    /**
     * save new bank to db
     *
     * @param BankRequest $request
     * @return Response
     */
    public function store(BankRequest $request)
    {
        $data   = $this->getStoreData($request);

        $data['created_by_id'] = Auth::id();
        // $data['last_updated_by_id'] = null;

        $result = $this->bankRepository->create($data);
        logCreate("Bank", $result);
        $successMessage = successMessageCreate("Bank");

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage,
            ]);
        }

        return backSuccess($successMessage);
    }

    /**
     * showing edit bank page
     *
     * @param Request $request
     * @param Bank $bank
     * @return Response
     */
    public function edit(Request $request, Bank $bank)
    {
        $data = $this->getDetailData($bank);

        if ($request->ajax()) {
            return view('stisla.banks.only-form', $data);
        }

        return view('stisla.banks.form', $data);
    }

    /**
     * update data to db
     *
     * @param BankRequest $request
     * @param Bank $bank
     * @return Response
     */
    public function update(BankRequest $request, Bank $bank)
    {
        $data    = $this->getStoreData($request);

        // $data['created_by_id'] = auth_id();
        $data['last_updated_by_id'] = auth_id();

        $newData = $this->bankRepository->update($data, $bank->id);
        logUpdate("Bank", $bank, $newData);
        $successMessage = successMessageUpdate("Bank");

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage,
            ]);
        }

        return backSuccess($successMessage);
    }

    public function show(Bank $bank)
    {
        $data = $this->getDetailData($bank, true);

        if (request()->ajax()) {
            return view('stisla.banks.only-form', $data);
        }

        return view('stisla.banks.form', $data);
    }

    /**
     * delete bank from db
     *
     * @param Bank $bank
     * @return Response
     */
    public function destroy(Bank $bank)
    {
        // $this->fileService->deleteBankFile($bank);
        $this->bankRepository->delete($bank->id);
        logDelete("Bank", $bank);
        $successMessage = successMessageDelete("Bank");

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage,
            ]);
        }

        return backSuccess($successMessage);
    }

    /**
     * download import example
     *
     * @return BinaryFileResponse
     */
    public function importExcelExample(): BinaryFileResponse
    {
        // bisa gunakan file excel langsung sebagai contoh
        // $filepath = public_path('example.xlsx');
        // return response()->download($filepath);

        $excel = new BankExport($this->bankRepository->getLatest());
        return $this->fileService->downloadExcel($excel, 'banks_import.xlsx');
    }

    /**
     * import excel file to db
     *
     * @param ImportExcelRequest $request
     * @return Response
     */
    public function importExcel(ImportExcelRequest $request)
    {
        $this->fileService->importExcel(new BankImport, $request->file('import_file'));
        $successMessage = successMessageImportExcel("Bank");
        return backSuccess($successMessage);
    }

    /**
     * download export data as json
     *
     * @return Response
     */
    public function exportJson()
    {
        $filename = date('YmdHis') . '_bank.json';
        $data     = $this->bankRepository->getLatest();

        return $this->fileService->downloadJson($data, $filename);
    }

    /**
     * download export data as xlsx
     *
     * @return Response
     */
    public function exportExcel()
    {
        $data  = $this->getExportData();
        return $this->fileService->downloadExcelGeneral('stisla.banks.table', $data, $data['excel_name']);
    }

    /**
     * download export data as csv
     *
     * @return Response
     */
    public function exportCsv()
    {
        $data  = $this->getExportData();
        return $this->fileService->downloadCsvGeneral('stisla.banks.table', $data, $data['csv_name']);
    }

    /**
     * download export data as pdf
     *
     * @return Response
     */
    public function exportPdf()
    {
        $filename = date('YmdHis') . '_bank.pdf';
        $html     = view('stisla.banks.export-pdf', [
            'title'    => 'Bank',
            'data'     => $this->bankRepository->getLatest(),
            'isExport' => true,
        ])->render();
        // return $html;

        return $this->pdfService->downloadPdfA4($html, $filename);
    }
}
