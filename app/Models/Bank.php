<?php

namespace App\Models;

use App\Traits\UserTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory, UserTrait;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'bank_type',
        'created_by_id',
        'last_updated_by_id'
    ];
}
