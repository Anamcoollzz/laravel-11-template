<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DropBoxService
{
    private $code = '';
    private $clientId = 's83a81s4f27p13d';
    private $clientSecret = '2rsdn8jsac4k16j';
    private $accessToken = 'sl.Ba8VjMCUWtpUD1pj7Swm1pcs8-Ip1jJKoTYq_4Tpl4OTm2ve-62AugP7EVaWhmg9qkl37s5gLaaxLKhlWeB19SbLVbm38qJlshN2R9YAQf2yfbR2NHH1S1xPx4diFBUUe1Q8zhs';
    private $refreshToken = '';

    public function getAccessToken($code, $clientId, $clientSecret)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.dropbox.com/oauth2/token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'code=' . ($code ?? $this->code) . '&grant_type=authorization_code&client_id=' . ($clientId ?? $this->clientId) . '&client_secret=' . ($clientSecret ?? $this->clientSecret),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded',
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response, true);
    }

    public function getRefreshToken()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.dropbox.com/oauth2/token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'refresh_token=' . $this->refreshToken . '&grant_type=refresh_token&client_id=' . $this->clientId . '&client_secret=' . $this->clientSecret,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded',
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
    }

    public function uploadFile($path, $folder = false)
    {
        $fp       = fopen($path, 'rb');
        $size     = filesize($path);
        $filename = basename($path);

        $cheaders = array(
            'Authorization: Bearer ' . (session('dropbox_access_token') ?? $this->accessToken),
            'Content-Type: application/octet-stream',
            'Dropbox-API-Arg: {"path":"' . ($folder ? $folder . '/' . $filename : '/' . $filename) . '", "mode":"add", "autorename":true, "mute":false, "strict_conflict":false}'
        );


        $ch = curl_init('https://content.dropboxapi.com/2/files/upload');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $cheaders);
        curl_setopt($ch, CURLOPT_PUT, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_INFILE, $fp);
        curl_setopt($ch, CURLOPT_INFILESIZE, $size);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        // dd($cheaders);

        curl_close($ch);
        fclose($fp);
        return json_decode($response, true);
    }

    public function deleteFile($path)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.dropboxapi.com/2/files/delete_v2',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode([
                'path' => $path,
            ]),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . (session('dropbox_access_token') ?? $this->accessToken),
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        // dd($response);
        curl_close($curl);
        $response = json_decode($response, true);
        if (isset($response['error_summary']) && Str::contains($response['error_summary'], 'path_lookup/not_found/')) {
            return [
                'success' => false,
                'errorMsg' => 'File not found',
            ];
        }
        // dd($response);
        return [
            'success' => true,
            'response' => $response
        ];
    }

    public function getListFile($folder = '/anam', $accessToken)
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.dropboxapi.com/2/files/list_folder',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                                        "include_deleted": false,
                                        "include_has_explicit_shared_members": true,
                                        "include_media_info": true,
                                        "include_mounted_folders": true,
                                        "include_non_downloadable_files": true,
                                        "path": "' . $folder . '",
                                        "recursive": false
                                    }',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . ($accessToken ?? $this->accessToken),
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response, true);
    }

    public function download($path, $filename)
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://content.dropboxapi.com/2/files/download',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            // CURLOPT_POSTFIELDS => json_encode([]),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . (session('dropbox_access_token') ?? $this->accessToken),
                'Dropbox-API-Arg: {"path":"' . $path . '"}',
                'Content-Type: text/plain; charset=utf-8'
            ),
        ));

        $response = curl_exec($curl);

        Storage::put('public/dropboxs/' . $filename, $response);

        curl_close($curl);

        return storage_path('app/public/dropboxs/' . $filename);
    }

    public function getMetaData($path)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.dropboxapi.com/2/files/get_metadata',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                                        "include_deleted": false,
                                        "include_has_explicit_shared_members": false,
                                        "include_media_info": false,
                                        "path": "' . $path . '"
                                    }',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . (session('dropbox_access_token') ?? $this->accessToken),
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response, true);
    }

    public function createFolder($folderName)
    {
        $folder = '/' . $folderName;
        $folder = str_replace('//', '/', $folder);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.dropboxapi.com/2/files/create_folder_v2',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                                        "autorename": true,
                                        "path": "' . $folder . '"
                                    }',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . (session('dropbox_access_token') ?? $this->accessToken),
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        // dd($response);

        curl_close($curl);
        return json_decode($response, true);
    }
}
