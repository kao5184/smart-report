<?php
namespace App\Repositories;

use Carbon\Carbon;
use GuzzleHttp\ClientInterface;
use Illuminate\Database\Connection;
use GuzzleHttp\Client;

class BaseRefreshRepository
{
    /**
     * @var Connection $connection ;
     */
    protected $connection;
    /**
     * @var \AipOcr $aipOcr
     */

    /**
     * @var Client $guzzleClient
     */
    protected $guzzleClient;


    public function __construct()
    {
        $this->connection = \DB::connection('refresh');
    }

    /**
     * @param $db
     * @return Connection $connection ;
     */
    protected function connect($db = 'refresh')
    {
        return \DB::connection($db);
    }

    protected function pager($total, $pager)
    {
        return [
            'total' => $total,
            'items' => $pager
        ];
    }

    protected function now($tz = null)
    {
        return Carbon::now($tz)->format('Y-m-d H:i:s');
    }
}
