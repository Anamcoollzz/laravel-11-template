<?php

use App\Models\ActivityLog;

/**
 * logExecute
 *
 * @param string $title
 * @param string $activityType
 * @param mixed $before
 * @param mixed $after
 * @return ActivityLog
 */
function logExecute(string $title, string $activityType, $before = null, $after = null)
{
    $before = is_string($before) ? $before : json_encode($before);
    $after  = is_string($after) ? $after : json_encode($after);
    $user   = auth_user();

    // override plain text password
    request()->merge([
        'new_password'              => bcrypt(request()->new_password),
        'new_password_confirmation' => bcrypt(request()->new_password_confirmation),
        'old_password'              => bcrypt(request()->old_password),
        'password'                  => bcrypt(request()->password),
    ]);

    $generalService = new \App\Services\GeneralService;
    $browser = $generalService->getBrowser();

    if ($user)
        $roles = ($user->roles->pluck('name')->toArray());
    else $roles = [];
    return ActivityLog::create([
        'title'         => $title,
        'user_id'       => $user->id ?? null,
        // 'role_id'       => $user->roles[0]['id'],
        'roles'         => $roles,
        'request_data'  => json_encode(request()->all()),
        'before'        => $before,
        'activity_type' => $activityType,
        'after'         => $after,
        'ip'            => request()->ip(),
        'user_agent'    => request()->header('User-Agent'),
        'browser'       => $browser,
        'platform'      => $generalService->getPlatform(),
        'device'        => $generalService->getDevice(),
    ]);
}

/**
 * logTitleCreate
 *
 * @param string $nextText
 * @return string
 */
function logTitleCreate(string $nextText)
{
    return __('Tambah ' . $nextText . ' Baru');
}

/**
 * logTitleUpdate
 *
 * @param string $nextText
 * @return string
 */
function logTitleUpdate(string $nextText)
{
    return __('Perbarui ' . $nextText);
}

/**
 * logTitleDelete
 *
 * @param string $nextText
 * @return string
 */
function logTitleDelete(string $nextText)
{
    return __('Hapus ' . $nextText);
}

/**
 * logLogout
 *
 * @return ActivityLog
 */
function logLogout()
{
    $title        = __('Keluar Dari Sistem');
    $activityType = LOGOUT;
    $before       = null;
    $after        = null;
    return logExecute($title, $activityType, $before, $after);
}

/**
 * logLogin
 *
 * @return ActivityLog
 */
function logLogin()
{
    $title        = __('Masuk Ke Sistem');
    $activityType = LOGIN;
    $before       = null;
    $after        = null;
    return logExecute($title, $activityType, $before, $after);
}

/**
 * logRegister
 *
 * @param mixed $after
 * @return ActivityLog
 */
function logRegister($after)
{
    $title        = __('Registrasi Pengguna');
    $activityType = CREATE;
    $before       = null;
    return logExecute($title, $activityType, $before, $after);
}

/**
 * logForgotPassword
 *
 * @param mixed $before
 * @param mixed $after
 * @return ActivityLog
 */
function logForgotPassword($before, $after)
{
    $title        = __('Lupa Kata Sandi');
    $activityType = FORGOT_PASSWORD;
    return logExecute($title, $activityType, $before, $after);
}


/**
 * logCreate
 *
 * @param string $nextTextTitle
 * @param mixed $after
 * @return ActivityLog
 */
function logCreate(string $nextTextTitle, $after)
{
    $title        = __('Tambah ' . $nextTextTitle . ' Baru');
    $activityType = CREATE;
    $before       = null;
    $after        = $after;
    return logExecute($title, $activityType, $before, $after);
}

/**
 * logUpdate
 *
 * @param string $nextTextTitle
 * @param mixed $before
 * @param mixed $after
 * @return ActivityLog
 */
function logUpdate(string $nextTextTitle, $before, $after)
{
    $title        = __('Perbarui ' . $nextTextTitle);
    $activityType = UPDATE;
    $before       = $before;
    $after        = $after;
    return logExecute($title, $activityType, $before, $after);
}

/**
 * logDelete
 *
 * @param string $nextTextTitle
 * @param mixed $before
 * @return ActivityLog
 */
function logDelete(string $nextTextTitle, $before)
{
    $title        = __('Hapus ' . $nextTextTitle);
    $activityType = DELETE;
    $before       = $before;
    $after        = null;
    return logExecute($title, $activityType, $before, $after);
}
