<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Repositories\NotificationRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Request as FacadesRequest;

class NotificationController extends Controller
{
    /**
     * NotificationRepository
     *
     * @var NotificationRepository
     */
    private NotificationRepository $NotificationRepository;

    /**
     * constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->NotificationRepository = new NotificationRepository;
    }

    /**
     * showing page data
     *
     * @return Response
     */
    public function index()
    {
        return view('stisla.notifications.index', [
            'data'        => $this->NotificationRepository->getMinePaginate(),
            'title'       => __('Notifikasi'),
            'countUnRead' => $this->NotificationRepository->myUnReadNotifCount(),
        ]);
    }

    /**
     * process read all notification by user login
     *
     * @return Response
     */
    public function readAll()
    {
        $unReads = $this->NotificationRepository->myUnReadNotifAll(['id', 'title', 'is_read']);

        $this->NotificationRepository->readAllMyNotif();

        $notificatinIds = $unReads->pluck('id')->toArray();
        $after          = $this->NotificationRepository->getWhereIn('id', $notificatinIds, ['id', 'title', 'is_read']);
        logExecute(__('Menandai Semua Notifikasi Sudah Dibaca'), UPDATE, $unReads, $after);

        if (FacadesRequest::is('notifications*'))
            return back()->with('successMessage', __('Semua notifikasi berhasil ditandai sebagai sudah dibaca'));
        return redirect()->route('notifications.index')->with('successMessage', __('Semua notifikasi berhasil ditandai sebagai sudah dibaca'));
    }

    /**
     * process read notification by user login
     *
     * @return Response
     */
    public function read(Notification $notification)
    {
        if ($notification->user_id != auth_id()) abort(404);

        $notification = $this->NotificationRepository->find($notification->id, ['id', 'title', 'is_read']);

        $after = $this->NotificationRepository->update(['is_read' => true], $notification->id, ['id', 'title', 'is_read']);

        logExecute(__('Menandai Notifikasi Sudah Dibaca'), UPDATE, $notification, $after);

        if (FacadesRequest::is('notifications*'))
            return back()->with('successMessage', __('Notifikasi berhasil ditandai sebagai sudah dibaca'));
        return redirect()->route('notifications.index')->with('successMessage', __('Notifikasi berhasil ditandai sebagai sudah dibaca'));
    }
}
