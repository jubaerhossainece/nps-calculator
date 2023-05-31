<?php

/*
|--------------------------------------------------------------------------
| Helper Functions
|--------------------------------------------------------------------------
| some helper functions for your project, which can save coding time.
| follow link: https://laravel-news.com/creating-helpers
|
*/

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


/**
 * api error response as json format
 *
 * @param string $message
 * @param int $code
 * @return JsonResponse
 */

if (!function_exists('errorResponseJson')) {
    function errorResponseJson(string $error_msg, int $error_code, $errors = null): JsonResponse
    {
        return response()->json([
            'status' => false,
            'status_code' => $error_code,
            'api' => url()->current(),
            'message' => $error_msg,
            'errors' => $errors,
        ], $error_code);
    }
    //    function errorResponseJson(Exception $exception, int $code = 500): JsonResponse
    //    {
    //        $error_code = $exception->getCode() == '0' ? $code : $exception->getCode();
    //        $error_msg = $exception->getMessage();
    //        $errors = null;
    //
    //        if (method_exists($exception,'errors')){
    //            $errors = $exception->errors();
    //        }
    //
    //        if (!config('app.debug')){
    //            $error_msg = 'Something went wrong!';
    //        }
    //
    //        return response()->json([
    //            'status' => false,
    //            'status_code' => $error_code,
    //            'api' => url()->current(),
    //            'message' => $error_msg,
    //            'errors' => $errors,
    //        ], $error_code);
    //    }
}


/**
 * api success response as json format
 *
 * @param $data
 * @param string $message
 * @param int $code
 * @return JsonResponse
 */
function successResponseJson($data = null, string $message = 'Success', int $code = 200): JsonResponse
{
    return response()->json([
        'status' => true,
        'status_code' => $code,
        'api' => url()->current(),
        'message' => $message,
        'data' => $data,
    ], $code);
}

function imageUpload(Request $request, $field, $upload_path = "", $exist_file = null)
{

    if ($request->hasFile($field)) {
        $image = $request->file($field);
        $image_name = $image->hashName();

        if ($exist_file != null) {
            $imagePath = $exist_file;

            if (Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
        }
        $path = $image->storeAs($upload_path, $image_name, 'public');
    } else {
        $path = $exist_file;
    }
    return $path;
}
