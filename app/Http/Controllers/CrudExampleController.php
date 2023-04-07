<?php

namespace App\Http\Controllers;

use App\Exports\CrudExampleExport;
use App\Http\Requests\CrudExampleRequest;
use App\Http\Requests\ImportExcelRequest;
use App\Imports\CrudExampleImport;
use App\Models\CrudExample;
use App\Repositories\CrudExampleRepository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CrudExampleController extends StislaController
{

    /**
     * crud example repository
     *
     * @var CrudExampleRepository
     */
    private CrudExampleRepository $crudExampleRepository;

    /**
     * constructor method
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->icon                  = 'fa fa-atom';
        $this->crudExampleRepository = new CrudExampleRepository;

        $this->defaultMiddleware('Contoh CRUD');
    }

    /**
     * get index data
     *
     * @return array
     */
    protected function getIndexData()
    {
        $isYajra = Route::is('crud-examples.index-yajra');
        $isAjax  = Route::is('crud-examples.index-ajax');
        $isAjaxYajra = Route::is('crud-examples.index-ajax-yajra');
        if ($isYajra || $isAjaxYajra) {
            $data = collect([]);
        } else {
            $data = $this->crudExampleRepository->getLatest();
        }

        $defaultData = $this->getDefaultDataIndex(__('Contoh CRUD'), 'Contoh CRUD', 'crud-examples');
        return array_merge($defaultData, [
            'data'         => $data,
            'isYajra'      => $isYajra,
            'isAjax'       => $isAjax,
            'isAjaxYajra'  => $isAjaxYajra,
            'yajraColumns' => $this->crudExampleRepository->getYajraColumns(),
        ]);
    }

    /**
     * prepare store data
     *
     * @param CrudExampleRequest $request
     * @return array
     */
    private function getStoreData(CrudExampleRequest $request)
    {
        $data = $request->only([
            'text',
            'email',
            "number",
            "select",
            "textarea",
            "radio",
            "date",
            'checkbox',
            'checkbox2',
            "time",
            'tags',
            "color",
            'select2',
            'select2_multiple',
            'summernote',
            'summernote_simple',
        ]);
        if ($request->hasFile('file')) {
            $data['file'] = $this->fileService->uploadCrudExampleFile($request->file('file'));
        }
        if ($request->hasFile('image')) {
            $data['image'] = $this->fileService->uploadCrudExampleFile($request->file('image'));
        }
        $data['currency'] = str_replace(',', '', $request->currency);
        $data['currency_idr'] = str_replace('.', '', $request->currency_idr);

        return $data;
    }

    /**
     * get detail data
     *
     * @param CrudExample $crudExample
     * @param bool $isDetail
     * @return array
     */
    private function getDetailData(CrudExample $crudExample, bool $isDetail = false)
    {
        $title       = __('Contoh CRUD');
        $defaultData = $this->getDefaultDataDetail($title, 'crud-examples', $crudExample, $isDetail);
        return array_merge($defaultData, [
            'selectOptions'   => get_options(10),
            'radioOptions'    => get_options(4),
            'checkboxOptions' => get_options(5),
            'fullTitle'       => $isDetail ? __('Detail Contoh CRUD') : __('Ubah Contoh CRUD'),
        ]);
    }

    /**
     * get export data
     *
     * @return array
     */
    protected function getExportData(): array
    {
        $times = date('Y-m-d_H-i-s');
        $data = [
            'isExport'   => true,
            'pdf_name'   => $times . '_crud_examples.pdf',
            'excel_name' => $times . '_crud_examples.xlsx',
            'csv_name'   => $times . '_crud_examples.csv',
            'json_name'  => $times . '_crud_examples.json',
        ];
        return array_merge($this->getIndexData(), $data);
    }

    /**
     * showing crud example page
     *
     * @return Response
     */
    public function index()
    {
        $data = $this->getIndexData();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'data'    => view('stisla.crud-examples.table', $data)->render(),
            ]);
        }

        return view('stisla.crud-examples.index', $data);
    }

    /**
     * datatable yajra index
     *
     * @return Response
     */
    public function yajraAjax()
    {
        $defaultData = $this->getDefaultDataIndex(__('Contoh CRUD'), 'Contoh CRUD', 'crud-examples');
        return $this->crudExampleRepository->getYajraDataTables($defaultData);
    }

    /**
     * showing add new crud example page
     *
     * @return Response
     */
    public function create()
    {
        $title      = __('Contoh CRUD');
        $fullTitle  = __('Tambah Contoh CRUD');
        $data       = $this->getDefaultDataCreate($title, 'crud-examples');
        $data       = array_merge($data, [
            'selectOptions'   => get_options(10),
            'radioOptions'    => get_options(4),
            'checkboxOptions' => get_options(5),
            'fullTitle'       => $fullTitle,
        ]);
        if (request()->ajax()) {
            return view('stisla.crud-examples.only-form', $data);
        }
        return view('stisla.crud-examples.form', $data);
    }

    /**
     * save new crud example to db
     *
     * @param CrudExampleRequest $request
     * @return Response
     */
    public function store(CrudExampleRequest $request)
    {
        $data   = $this->getStoreData($request);
        $result = $this->crudExampleRepository->create($data);
        logCreate("Contoh CRUD", $result);
        $successMessage = successMessageCreate("Contoh CRUD");

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage,
            ]);
        }

        return back()->with('successMessage', $successMessage);
    }

    /**
     * showing edit crud example page
     *
     * @param CrudExample $crudExample
     * @return Response
     */
    public function edit(CrudExample $crudExample)
    {
        $data = $this->getDetailData($crudExample);

        if (request()->ajax()) {
            return view('stisla.crud-examples.only-form', $data);
        }

        return view('stisla.crud-examples.form', $data);
    }

    /**
     * update data to db
     *
     * @param CrudExampleRequest $request
     * @param CrudExample $crudExample
     * @return Response
     */
    public function update(CrudExampleRequest $request, CrudExample $crudExample)
    {
        $data    = $this->getStoreData($request);
        $newData = $this->crudExampleRepository->update($data, $crudExample->id);
        logUpdate("Contoh CRUD", $crudExample, $newData);
        $successMessage = successMessageUpdate("Contoh CRUD");

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage,
            ]);
        }

        return back()->with('successMessage', $successMessage);
    }

    public function show(CrudExample $crudExample)
    {
        $data = $this->getDetailData($crudExample, true);

        if (request()->ajax()) {
            return view('stisla.crud-examples.only-form', $data);
        }

        return view('stisla.crud-examples.form', $data);
    }

    /**
     * delete crud example from db
     *
     * @param CrudExample $crudExample
     * @return Response
     */
    public function destroy(CrudExample $crudExample)
    {
        $this->fileService->deleteCrudExampleFile($crudExample);
        $this->crudExampleRepository->delete($crudExample->id);
        logDelete("Contoh CRUD", $crudExample);
        $successMessage = successMessageDelete("Contoh CRUD");

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage,
            ]);
        }

        return back()->with('successMessage', $successMessage);
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

        $excel = new CrudExampleExport($this->crudExampleRepository->getLatest());
        return $this->fileService->downloadExcel($excel, 'crud_examples_import.xlsx');
    }

    /**
     * import excel file to db
     *
     * @param ImportExcelRequest $request
     * @return Response
     */
    public function importExcel(ImportExcelRequest $request)
    {
        $this->fileService->importExcel(new CrudExampleImport, $request->file('import_file'));
        $successMessage = successMessageImportExcel("Contoh CRUD");
        return back()->with('successMessage', $successMessage);
    }

    /**
     * download export data as json
     *
     * @return BinaryFileResponse
     */
    public function json(): BinaryFileResponse
    {
        $data  = $this->getExportData();
        return $this->fileService->downloadJson($data['data'], $data['json_name']);
    }

    /**
     * download export data as xlsx
     *
     * @return Response
     */
    public function excel(): BinaryFileResponse
    {
        $data  = $this->getExportData();
        return $this->fileService->downloadExcelGeneral('stisla.crud-examples.table', $data, $data['excel_name']);
    }

    /**
     * download export data as csv
     *
     * @return Response
     */
    public function csv(): BinaryFileResponse
    {
        $data  = $this->getExportData();
        return $this->fileService->downloadCsvGeneral('stisla.crud-examples.table', $data, $data['csv_name']);
    }

    /**
     * download export data as pdf
     *
     * @return Response
     */
    public function pdf(): Response
    {
        $data  = $this->getExportData();
        return $this->fileService->downloadPdfA2('stisla.includes.others.export-pdf', $data, $data['pdf_name']);
    }
}
