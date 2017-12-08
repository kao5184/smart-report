<?php
namespace App\Utils;


use Carbon\Carbon;

class LogWriter
{
    public static function write($method, $url, $params, $user, $responseCode, $responseContent)
    {
        $MAX_CONTENT_SIZE = env('MAX_CONTENT_SIZE', 1024 * 64);
        if (strlen($responseContent) > $MAX_CONTENT_SIZE) {
            $responseContent = substr($responseContent, 0, $MAX_CONTENT_SIZE);
        }
        \DB::connection('refresh')->table('global_op_logs')->insert([
            'method' => $method,
            'url' => $url,
            'params' => @json_encode($params),
            'user' => $user,
            'response_code' => $responseCode,
            'response_content' => $responseContent,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
    }
}