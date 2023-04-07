<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrudExample extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'text',
        'email',
        'number',
        'currency',
        'currency_idr',
        'select',
        'select2',
        'select2_multiple',
        'textarea',
        'radio',
        'checkbox',
        'checkbox2',
        'tags',
        'file',
        'image',
        'date',
        'time',
        'color',
        'summernote_simple',
        'summernote',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'checkbox'         => 'array',
        'checkbox2'         => 'array',
        'select2_multiple' => 'array',
    ];
}
