<?php

namespace App\Http\Controllers;

use App\Http\Requests\BankDepositRequest;
use App\Imports\GeneralImport;
use App\Models\BankDeposit;
use App\Repositories\BankDepositRepository;
use App\Repositories\BankRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BankDepositController extends StislaController
{
    /**
     * bank deposit repository
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

        $this->icon           = 'fa fa-money';
        $this->repository     = new BankDepositRepository;
        $this->bankRepository = new BankRepository;
        $this->prefix         = $this->viewFolder = 'bank-deposits';
        $this->pdfPaperSize   = 'A3';
        // $this->import     = new GeneralImport;

        $this->defaultMiddleware($this->title = 'Deposito Bank');
    }

    /**
     * prepare store data
     *
     * @param BankDepositRequest $request
     * @return array
     */
    public function getStoreData(BankDepositRequest $request)
    {
        $data = $request->only([
            'bank_id',
            'per_anum',
            // 'amount',
            'tax_percentage',
            // 'estimation',
            'time_period',
            'due_date',
            'status',
            'realization',
            // 'difference',
        ]);

        $perAnum = $data['per_anum'];
        $amount = $data['amount'] = rp_to_double($request->amount);

        if ($data['time_period'] === '1 Bulan')
            $perMonth           = $amount * $perAnum / 100 / 12;
        else if ($data['time_period'] === '14 Hari')
            $perMonth           = $amount * $perAnum / 100 / 12 / 2;
        else if ($data['time_period'] === '7 Hari')
            $perMonth           = $amount * $perAnum / 100 / 12 / 2 / 2;

        $data['tax']        = $tax = $perMonth * ($data['tax_percentage'] / 100);
        $data['estimation'] = $perMonth - $tax;

        if ($request->realization) {
            $data['realization'] = rp_to_double($request->realization);
            $data['difference'] = $data['estimation'] - $data['realization'];
        }
        // $data['currency_idr'] = rp_to_double($request->currency_idr);

        // if ($request->hasFile('file'))
        //     $data['file'] = $this->fileService->uploadBankDepositFile($request->file('file'));

        // if ($request->hasFile('image'))
        //     $data['image'] = $this->fileService->uploadBankDepositFile($request->file('image'));

        return $data;
    }

    /**
     * showing bank deposit page
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        return $this->prepareIndex($request, ['data' => $this->repository->getFullDataWith(['bank:id,name,bank_type'])]);
    }

    /**
     * showing add new bank deposit page
     *
     * @param Request $request
     * @return Response
     */
    public function create(Request $request)
    {
        $bank_options = $this->bankRepository->getSelectOptions();
        return $this->prepareCreateForm($request, ['bank_options' => $bank_options]);
    }

    /**
     * save new bank deposit to db
     *
     * @param BankDepositRequest $request
     * @return Response
     */
    public function store(BankDepositRequest $request)
    {
        return $this->executeStore($request);
    }

    /**
     * showing edit bank deposit page
     *
     * @param Request $request
     * @param BankDeposit $bankDeposit
     * @return Response
     */
    public function edit(Request $request, BankDeposit $bankDeposit)
    {
        $bank_options = $this->bankRepository->getSelectOptions();
        return $this->prepareDetailForm($request, $bankDeposit, false, ['bank_options' => $bank_options]);
    }

    /**
     * update data to db
     *
     * @param BankDepositRequest $request
     * @param BankDeposit $bankDeposit
     * @return Response
     */
    public function update(BankDepositRequest $request, BankDeposit $bankDeposit)
    {
        return $this->executeUpdate($request, $bankDeposit);
    }

    /**
     * show detail page
     *
     * @param Request $request
     * @param BankDeposit $bankDeposit
     * @return Response
     */
    public function show(Request $request, BankDeposit $bankDeposit)
    {
        return $this->prepareDetailForm($request, $bankDeposit, true);
    }

    /**
     * delete bank deposit from db
     *
     * @param BankDeposit $bankDeposit
     * @return Response
     */
    public function destroy(BankDeposit $bankDeposit)
    {
        // $this->fileService->deleteBankDepositFile($bankDeposit);
        return $this->executeDestroy($bankDeposit);
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
