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
        'barcode',
        'qr_code',
        'created_by_id',
        'last_updated_by_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'checkbox'         => 'array',
        'checkbox2'        => 'array',
        'select2_multiple' => 'array',
    ];

    /**
     * Get the user that created the CrudExample.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    /**
     * Get the user that updated the CrudExample.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lastUpdatedBy()
    {
        return $this->belongsTo(User::class, 'last_updated_by_id');
    }
}
