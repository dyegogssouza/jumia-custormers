<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'customer';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'phone'];

    /**
     * Array of countries.
     *
     * @var array
     */
    protected $country = [
        '237' => ['Cameroon', '/\(237\)\ ?[2368]\d{7,8}$/'], 
        '251' => ['Ethiopia', '/\(251\)\ ?[1-59]\d{8}$/'], 
        '212' => ['Morocco', '/\(212\)\ ?[5-9]\d{8}$/'], 
        '258' => ['Mozambique', '/\(258\)\ ?[28]\d{7,8}$/'], 
        '256' => ['Uganda', '/\(256\)\ ?\d{9}$/']
    ];

    /**
     * Array of codes of countries.
     *
     * @var array
     */
    protected $codes = [
       'Cameroon'   => '237',
       'Ethiopia'   => '251',
       'Morocco'    => '212',
       'Mozambique' => '258',
       'Uganda'     => '256',
    ];

    /**
     * Return array of countries.
     * 
     * @return array
     */
    public function getCountry()
    {
        return $this->country;
    }

     /**
     * Return array of codes of countries.
     * 
     * @return array
     */
    public function getCodes()
    {
        return $this->codes;
    }
}
