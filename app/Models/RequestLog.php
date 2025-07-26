<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uri',
        'query_string',
        'method',
        'request_data',
        'ip',
        'user_agent',
        'user_id',
        'roles',
        'browser',
        'platform',
        'device',
        'is_ajax',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'log_requests';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'roles' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
