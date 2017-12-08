<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class BusinessException extends HttpException
{
    const UNKNOWN_ERROR      = 'UNKNOWN_ERROR';
    const SERVER_ERROR       = 'SERVER_ERROR';
    const PARAMETER_ERROR    = 'PARAMETER_ERROR';
    const RESOURCE_NOT_FOUND = 'RESOURCE_NOT_FOUND';
    const METHOD_NOT_ALLOWED = 'METHOD_NOT_ALLOWED';
    const FORBIDDEN          = 'FORBIDDEN';
    const UNAUTHORIZED       = 'UNAUTHORIZED';
    const BAD_REQUEST        = 'BAD_REQUEST';
    const IDS_OUT_OF_RANGE   = 'IDS_OUT_OF_RANGE';

    private $business_code;

    private static $status_code = [
        self::SERVER_ERROR       => 500,
        self::FORBIDDEN          => 403,
        self::PARAMETER_ERROR    => 422,
        self::UNAUTHORIZED       => 401,
        self::RESOURCE_NOT_FOUND => 404,
        self::METHOD_NOT_ALLOWED => 405,
        self::IDS_OUT_OF_RANGE   => 422,
    ];

    public function __construct($business_code = self::UNKNOWN_ERROR, $message = "", $status_code = 0)
    {
        $this->business_code = $business_code;
        if (!$status_code) {
            $status_code = array_get(self::$status_code, $business_code, 400);
        }
        parent::__construct($status_code, $message);
    }

    public static function businessCode($status_code)
    {
        $business_code = array_search($status_code, self::$status_code);
        if ($business_code !== false) {
            return $business_code;
        }

        return self::UNKNOWN_ERROR;
    }

    public function getBusinessCode()
    {
        return $this->business_code;
    }
}
