<?php

use Illuminate\Http\RedirectResponse;

/**
 * response422
 *
 * @param mixed $errors
 * @param string $message
 * @return JsonResponse
 */
function response422($errors, string $message = null)
{
    if ($message === null) $message = __('Form tidak valid');
    return response()->json([
        'errors' => $errors,
        'message' => $message,
    ], 422);
}

/**
 * response200
 *
 * @param mixed $errors
 * @param string $message
 * @return JsonResponse
 */
function response200($data = null, string $message = null)
{
    if ($message === null) $message = __('Berhasil');
    return response()->json([
        'data' => $data,
        'message' => $message,
    ], 200);
}

/**
 * response404
 *
 * @param mixed $errors
 * @param string $message
 * @return JsonResponse
 */
function response404($data = null, string $message = null)
{
    if ($message === null) $message = __('Data tidak ditemukan.');
    return response()->json([
        'data' => $data,
        'message' => $message,
    ], 404);
}

/**
 * response200
 *
 * @param mixed $errors
 * @param string $message
 * @return JsonResponse
 */
function response500($data = null, string $message = null)
{
    if ($message === null) $message = __('Server Error');
    return response()->json([
        'data' => $data,
        'message' => $message,
    ], 500);
}

/**
 * back success
 *
 * @param string $successMessage
 * @return RedirectResponse
 */
function backSuccess($successMessage)
{
    return back()->with('successMessage', __($successMessage));
}
