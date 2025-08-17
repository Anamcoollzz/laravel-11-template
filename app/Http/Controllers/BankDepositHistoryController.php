<?php

namespace App\Http\Controllers;

use App\Http\Requests\BankDepositHistoryRequest;
use App\Imports\GeneralImport;
use App\Models\BankDepositHistory;
use App\Repositories\BankDepositHistoryRepository;
use App\Repositories\BankRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BankDepositHistoryController extends StislaController
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

        $this->icon           = 'fa fa-dollar';
        $this->repository     = new BankDepositHistoryRepository;
        $this->bankRepository = new BankRepository;
        $this->prefix         = $this->viewFolder = 'bank-deposit-histories';
        $this->pdfPaperSize   = 'A3';
        // $this->import     = new GeneralImport;

        $this->defaultMiddleware($this->title = 'Riwayat Deposito Bank');
    }

    /**
     * prepare store data
     *
     * @param BankDepositHistoryRequest $request
     * @return array
     */
    public function getStoreData(BankDepositHistoryRequest $request)
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
        //     $data['file'] = $this->fileService->uploadBankDepositHistoryFile($request->file('file'));

        // if ($request->hasFile('image'))
        //     $data['image'] = $this->fileService->uploadBankDepositHistoryFile($request->file('image'));

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
        // return $this->repository->getFullDataWith(['bankdeposit:id,bank_id', 'bankdeposit.bank']);
        return $this->prepareIndex($request, ['data' => ($this->repository->getFullDataWith(['bankdeposit:id,bank_id', 'bankdeposit.bank:id,name,bank_type']))]);
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
     * @param BankDepositHistoryRequest $request
     * @return Response
     */
    public function store(BankDepositHistoryRequest $request)
    {
        return $this->executeStore($request);
    }

    /**
     * showing edit bank deposit page
     *
     * @param Request $request
     * @param BankDepositHistory $bankDeposit
     * @return Response
     */
    public function edit(Request $request, BankDepositHistory $bankDeposit)
    {
        $bank_options = $this->bankRepository->getSelectOptions();
        return $this->prepareDetailForm($request, $bankDeposit, false, ['bank_options' => $bank_options]);
    }

    /**
     * update data to db
     *
     * @param BankDepositHistoryRequest $request
     * @param BankDepositHistory $bankDeposit
     * @return Response
     */
    public function update(BankDepositHistoryRequest $request, BankDepositHistory $bankDeposit)
    {
        return $this->executeUpdate($request, $bankDeposit);
    }

    /**
     * show detail page
     *
     * @param Request $request
     * @param BankDepositHistory $bankDeposit
     * @return Response
     */
    public function show(Request $request, BankDepositHistory $bankDeposit)
    {
        return $this->prepareDetailForm($request, $bankDeposit, true);
    }

    /**
     * delete bank deposit from db
     *
     * @param BankDepositHistory $bankDeposit
     * @return Response
     */
    public function destroy(BankDepositHistory $bankDeposit)
    {
        // $this->fileService->deleteBankDepositHistoryFile($bankDeposit);
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
