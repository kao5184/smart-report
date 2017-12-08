<?php

namespace App\Http;

use App\Exceptions\BusinessException;
use Illuminate\Http\JsonResponse;

class Response
{

    public static function success($data = null)
    {
        return JsonResponse::create([
            'success' => true,
            'errors' => [],
            'data' => $data,
        ]);
    }

    public static function raw($data = null)
    {
        return \Illuminate\Http\Response::create($data??'', 200);
    }

    public static function error($code = BusinessException::UNKNOWN_ERROR, $message = '', $http_code = 400)
    {
        return JsonResponse::create([
            'success' => false,
            'data' => null,
            'errors' => [
                [
                    'code' => $code,
                    'message' => $message,
                ],
            ],
        ], $http_code);
    }

    public static function errors($errors, $http_code = 400)
    {
        $fixed_errors = [];
        foreach ($errors as $error) {
            if (is_string($error)) {
                $fixed_errors[] = [
                    'code' => BusinessException::UNKNOWN_ERROR,
                    'message' => $error,
                ];
                continue;
            }
            if (is_array($error)) {
                $fixed_errors[] = [
                    'code' => array_get($error, 'code', BusinessException::UNKNOWN_ERROR),
                    'message' => array_get($error, 'message', BusinessException::UNKNOWN_ERROR),
                ];
            }
        }
        return JsonResponse::create([
            'success' => false,
            'data' => null,
            'errors' => $fixed_errors,
        ], $http_code);
    }
}
