<?php

use App\Models\User;
use App\Repositories\SettingRepository;
use Illuminate\Support\Facades\Auth;

function active_template()
{
    return config('app.template');
}

function is_stisla_template()
{
    return active_template() === 'stisla';
}

function since()
{
    return SettingRepository::since();
}

function year()
{
    return since();
}

function app_name()
{
    return SettingRepository::appName();
}

function developer_name()
{
    return SettingRepository::developerName();
}

function developer_whatsapp()
{
    return SettingRepository::developerWhatsapp();
}

include 'LogHelper.php';
include 'ResponseHelper.php';
include 'MessageHelper.php';
include 'FileHelper.php';
include 'DateTimeHelper.php';
include 'ArrayHelper.php';
include 'NumberHelper.php';

if (!function_exists('encode_id')) {
    /**
     * make secure id
     *
     * @param $val
     * @return string
     */
    function encode_id($val = '')
    {
        $params = ['val' => $val];
        $secure = preg_replace('/[=]+$/', '', base64_encode(serialize($params)));
        return $secure;
    }
}

if (!function_exists('decode_id')) {
    /**
     * decode encrypted id
     *
     * @param string
     * @return int
     */
    function decode_id($val = '')
    {
        $secure = unserialize(base64_decode($val));
        return $secure['val'];
    }
}

/**
 * convert idr to double
 *
 * @param string $value
 * @return float
 */
function idr_to_double($value)
{
    return str_replace(',', '', $value);
}

/**
 * convert rp to double
 *
 * @param string $value
 * @return float
 */
function rp_to_double($value)
{
    return str_replace('.', '', $value);
}

/**
 * get user login model
 *
 * @return User
 */
function auth_user()
{
    return Auth::user() ?? auth('api')->user();
}
