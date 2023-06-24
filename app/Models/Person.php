<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'nik',
        'village_code',
        'district_code',
        'city_code',
        'province_code',
        'pic_id'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'persons';

    public function province()
    {
        return $this->belongsTo(Region::class, 'province_code', 'code');
    }

    public function city()
    {
        return $this->belongsTo(Region::class, 'city_code', 'code');
    }

    public function district()
    {
        return $this->belongsTo(Region::class, 'district_code', 'code');
    }

    public function village()
    {
        return $this->belongsTo(Region::class, 'village_code', 'code');
    }

    public function pic()
    {
        return $this->belongsTo(User::class, 'pic_id', 'id');
    }
}
