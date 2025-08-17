<?php

namespace App\Repositories;

use App\Models\Notification;

class NotificationRepository extends Repository
{

    /**
     * constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->model = new Notification();
    }

    /**
     * myUnReadNotif
     *
     * @param integer $limit
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function myUnReadNotif($limit = 1000)
    {
        return $this->model->query()->where('is_read', 0)
            ->limit($limit)->latest()->where('user_id', auth_id())->get();
    }

    /**
     * myUnReadNotifAll
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function myUnReadNotifAll(array $columns = ['*'])
    {
        return $this->model->query()
            ->where('is_read', 0)
            ->latest()->where('user_id', auth_id())
            ->select($columns)
            ->get();
    }

    /**
     * myUnReadNotifCount
     *
     * @return integer
     */
    public function myUnReadNotifCount()
    {
        return $this->model->query()
            ->where('is_read', 0)
            ->where('user_id', auth_id())
            ->count();
    }

    /**
     * getPaginate
     *
     * @param integer $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     *
     * @throws \InvalidArgumentException
     */
    public function getPaginate($perPage = 20)
    {
        return $this->model->query()
            ->latest()->paginate($perPage);
    }

    /**
     * getMinePaginate
     *
     * @param integer $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     *
     * @throws \InvalidArgumentException
     */
    public function getMinePaginate($perPage = 20)
    {
        return $this->model->query()
            ->where('user_id', auth_id())->latest()
            ->paginate($perPage);
    }

    /**
     * readAllMyNotif
     *
     * @return int
     */
    public function readAllMyNotif()
    {
        return $this->model->query()->where('user_id', auth_id())
            ->update(['is_read' => 1]);
    }

    /**
     * create notification
     *
     * @param string $title
     * @param string $content
     * @param string|integer $userId
     * @param string $notificationType
     * @param string $icon
     * @param string $bgColor
     * @return Notification
     */
    public function createNotif(string $title, string $content, $userId, string $notificationType, $icon = 'bell', $bgColor = 'primary')
    {
        $data = [
            'title'             => $title,
            'content'           => $content,
            'user_id'           => $userId,
            'is_read'           => false,
            'notification_type' => $notificationType,
            'icon'              => $icon,
            'bg_color'          => $bgColor,
        ];
        return $this->create($data);
    }
}
