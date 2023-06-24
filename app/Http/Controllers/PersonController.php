<?php

namespace App\Http\Controllers;

use App\Exports\PersonExport;
use App\Http\Requests\PersonRequest;
use App\Http\Requests\ImportExcelRequest;
use App\Imports\PersonImport;
use App\Models\Person;
use App\Repositories\PersonRepository;
use App\Repositories\RegionRepository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PersonController extends StislaController
{

    /**
     * crud example repository
     *
     * @var PersonRepository
     */
    private PersonRepository $personRepository;

    /**
     * region repository
     *
     * @var RegionRepository
     */
    private RegionRepository $regionRepository;

    /**
     * constructor method
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->icon                  = 'fa fa-user-plus';
        $this->regionRepository = new RegionRepository;
        $this->personRepository = new PersonRepository;
        $this->viewFolder            = 'persons';

        $this->defaultMiddleware('Penduduk');
    }

    /**
     * get index data
     *
     * @return array
     */
    protected function getIndexData()
    {
        $isYajra = Route::is('persons.index-yajra');
        $isAjax  = Route::is('persons.index-ajax');
        $isAjaxYajra = Route::is('persons.index-ajax-yajra');
        if ($isYajra || $isAjaxYajra) {
            $data = collect([]);
        } else {
            $data = $this->personRepository->getFilter();
        }

        $defaultData = $this->getDefaultDataIndex(__('Penduduk'), 'Penduduk', 'persons');
        return array_merge($defaultData, [
            'data'         => $data,
            'isYajra'      => $isYajra,
            'isAjax'       => $isAjax,
            'isAjaxYajra'  => $isAjaxYajra,
            'yajraColumns' => $this->personRepository->getYajraColumns(),
        ]);
    }

    /**
     * prepare store data
     *
     * @param PersonRequest $request
     * @return array
     */
    private function getStoreData(PersonRequest $request)
    {
        $data = $request->only([
            'name',
            'nik',
            'province_code',
            'city_code',
            'district_code',
            'village_code',
        ]);
        if ($request->hasFile('file')) {
            $data['file'] = $this->fileService->uploadPersonFile($request->file('file'));
        }
        $data['pic_id'] = auth()->id();
        // $data['currency'] = str_replace(',', '', $request->currency);
        // $data['currency_idr'] = str_replace('.', '', $request->currency_idr);

        return $data;
    }

    /**
     * get detail data
     *
     * @param Person $person
     * @param bool $isDetail
     * @return array
     */
    private function getDetailData(Person $person, bool $isDetail = false)
    {
        $title       = __('Penduduk');
        $defaultData = $this->getDefaultDataDetail($title, 'persons', $person, $isDetail);
        $provinces = $this->regionRepository->getProvinces();
        $firstProvince = collect($provinces)->first();
        $cities = $this->regionRepository->getCities($person->province_code ?? $firstProvince['code']);
        $firstCity = collect($cities)->first();
        $districts = $this->regionRepository->getDistricts($person->city_code ?? $firstCity['code']);
        $firstDistrict = collect($districts)->first();
        $villages = $this->regionRepository->getVillages($person->district_code ?? $firstDistrict['code']);
        return array_merge($defaultData, [
            'selectOptions'   => get_options(10),
            'radioOptions'    => get_options(4),
            'checkboxOptions' => get_options(5),
            'fullTitle'       => $isDetail ? __('Detail Penduduk') : __('Ubah Penduduk'),
            'provinces'       => $provinces->pluck('name', 'code')->toArray(),
            'cities'          => $cities->pluck('name', 'code')->toArray(),
            'districts'       => $districts->pluck('name', 'code')->toArray(),
            'villages'        => $villages->pluck('name', 'code')->toArray(),
        ]);
    }

    /**
     * get export data
     *
     * @return array
     */
    protected function getExportData(): array
    {
        $times    = date('Y-m-d_H-i-s');
        $filename = $times . '_crud_examples';
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
                'data'    => view('stisla.persons.table', $data)->render(),
            ]);
        }

        return view('stisla.persons.index', $data);
    }

    /**
     * datatable yajra index
     *
     * @return Response
     */
    public function yajraAjax()
    {
        $defaultData = $this->getDefaultDataIndex(__('Contoh CRUD'), 'Contoh CRUD', 'persons');
        return $this->personRepository->getYajraDataTables($defaultData);
    }

    /**
     * showing add new crud example page
     *
     * @return Response
     */
    public function create()
    {
        $title      = __('Penduduk');
        $fullTitle  = __('Tambah Penduduk');
        $data       = $this->getDefaultDataCreate($title, 'persons');
        $provinces = $this->regionRepository->getProvinces();
        $firstProvince = collect($provinces)->first();
        $cities = $this->regionRepository->getCities($firstProvince['code']);
        $firstCity = collect($cities)->first();
        $districts = $this->regionRepository->getDistricts($firstCity['code']);
        $firstDistrict = collect($districts)->first();
        $villages = $this->regionRepository->getVillages($firstDistrict['code']);
        $data       = array_merge($data, [
            'selectOptions'   => get_options(10),
            'radioOptions'    => get_options(4),
            'checkboxOptions' => get_options(5),
            'fullTitle'       => $fullTitle,
            'provinces'       => $provinces->pluck('name', 'code')->toArray(),
            'cities'          => $cities->pluck('name', 'code')->toArray(),
            'districts'       => $districts->pluck('name', 'code')->toArray(),
            'villages'        => $villages->pluck('name', 'code')->toArray(),
        ]);
        if (request()->ajax()) {
            return view('stisla.persons.only-form', $data);
        }
        return view('stisla.persons.form', $data);
    }

    /**
     * save new crud example to db
     *
     * @param PersonRequest $request
     * @return Response
     */
    public function store(PersonRequest $request)
    {
        $data   = $this->getStoreData($request);
        $result = $this->personRepository->create($data);
        logCreate("Penduduk", $result);
        $successMessage = successMessageCreate("Penduduk");

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
     * @param Person $person
     * @return Response
     */
    public function edit(Person $person)
    {
        $data = $this->getDetailData($person);

        if (request()->ajax()) {
            return view('stisla.persons.only-form', $data);
        }

        return view('stisla.persons.form', $data);
    }

    /**
     * update data to db
     *
     * @param PersonRequest $request
     * @param Person $person
     * @return Response
     */
    public function update(PersonRequest $request, Person $person)
    {
        $data    = $this->getStoreData($request);
        $newData = $this->personRepository->update($data, $person->id);
        logUpdate("Contoh CRUD", $person, $newData);
        $successMessage = successMessageUpdate("Contoh CRUD");

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage,
            ]);
        }

        return back()->with('successMessage', $successMessage);
    }

    public function show(Person $person)
    {
        $data = $this->getDetailData($person, true);

        if (request()->ajax()) {
            return view('stisla.persons.only-form', $data);
        }

        return view('stisla.persons.form', $data);
    }

    /**
     * delete crud example from db
     *
     * @param Person $person
     * @return Response
     */
    public function destroy(Person $person)
    {
        // $this->fileService->deletePersonFile($person);
        $this->personRepository->delete($person->id);
        logDelete("Penduduk", $person);
        $successMessage = successMessageDelete("Penduduk");

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

        $excel = new PersonExport($this->personRepository->getLatest());
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
        $this->fileService->importExcel(new PersonImport, $request->file('import_file'));
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
        return $this->fileService->downloadExcelGeneral('stisla.persons.table', $data, $data['excel_name']);
    }

    /**
     * download export data as csv
     *
     * @return Response
     */
    public function csv(): BinaryFileResponse
    {
        $data  = $this->getExportData();
        return $this->fileService->downloadCsvGeneral('stisla.persons.table', $data, $data['csv_name']);
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
