<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Connection;

abstract class BaseModel extends \Illuminate\Database\Eloquent\Model
{
    protected $dateFormat = 'Y-m-d H:i:s';

    /**
     * @var BaseModel $instance
     */
    protected static $instance;

    /**
     * @param $db
     * @return Connection
     */
    public function connect($db = 'refresh')
    {
        return \DB::connection($db);
    }

    public function now()
    {
        return Carbon::now()->format($this->dateFormat);
    }

    /**
     * @return static
     */
    public static function instance()
    {
        if (!self::$instance) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    public function page($data = [], $limit)
    {
        usort($data, function ($a, $b) {
            if (array_get($a, 'similar') == array_get($b, 'similar')) return 0;
            return (array_get($a, 'similar') < array_get($b, 'similar')) ? 1 : -1;
        });

        return array_slice($data, 0, $limit);
    }

    public function floatScore($score)
    {
        while ($score > 2) {
            $score = $score / 10;
        }
        return $score;
    }
}
