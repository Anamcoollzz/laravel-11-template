<?php

namespace App\Http\Controllers;

use App\Http\Requests\CrudExampleRequest;
use App\Imports\CrudExampleImport;
use App\Models\CrudExample;
use App\Repositories\CrudExampleRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CrudExampleController extends StislaController
{

    /**
     * constructor method
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->icon       = 'fa fa-atom';
        $this->repository = new CrudExampleRepository;
        $this->prefix     = $this->viewFolder            = 'crud-examples';
        $this->pdfPaperSize = 'A2';
        // $this->import     = new CrudExampleImport;

        $this->defaultMiddleware($this->title = 'Contoh CRUD');
    }

    /**
     * prepare store data
     *
     * @param CrudExampleRequest $request
     * @return array
     */
    public function getStoreData(CrudExampleRequest $request)
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
            'barcode',
            'qr_code',
        ]);

        $data['currency']     = idr_to_double($request->currency);
        $data['currency_idr'] = rp_to_double($request->currency_idr);

        if ($request->hasFile('file'))
            $data['file'] = $this->fileService->uploadCrudExampleFile($request->file('file'));

        if ($request->hasFile('image'))
            $data['image'] = $this->fileService->uploadCrudExampleFile($request->file('image'));

        return $data;
    }

    /**
     * showing crud example page
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        return $this->prepareIndex($request);
    }

    /**
     * showing add new crud example page
     *
     * @param Request $request
     * @return Response
     */
    public function create(Request $request)
    {
        return $this->prepareCreateForm($request);
    }

    /**
     * save new crud example to db
     *
     * @param CrudExampleRequest $request
     * @return Response
     */
    public function store(CrudExampleRequest $request)
    {
        return $this->executeStore($request);
    }

    /**
     * showing edit crud example page
     *
     * @param Request $request
     * @param CrudExample $crudExample
     * @return Response
     */
    public function edit(Request $request, CrudExample $crudExample)
    {
        return $this->prepareDetailForm($request, $crudExample);
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
        return $this->executeUpdate($request, $crudExample);
    }

    /**
     * show detail page
     *
     * @param Request $request
     * @param CrudExample $crudExample
     * @return Response
     */
    public function show(Request $request, CrudExample $crudExample)
    {
        return $this->prepareDetailForm($request, $crudExample, true);
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
        return $this->executeDestroy($crudExample);
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

        return $this->executeImportExcelExample();
    }
}
