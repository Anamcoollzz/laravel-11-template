<?php

use App\Models\ActivityLog;
use App\Repositories\SettingRepository;
use Illuminate\Http\JsonResponse;

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
