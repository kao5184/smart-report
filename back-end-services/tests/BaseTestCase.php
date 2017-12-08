<?php

namespace Tests;

use App\Models\User;
use Illuminate\Database\Connection;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Laravel\Lumen\Testing\TestCase;

class BaseTestCase extends TestCase
{
    use DatabaseTransactions;

    protected $apiHttpClient;

    public function setUp()
    {
        parent::setUp();

    }

    /**
     * @param $db
     * @return Connection
     */
    public function connect($db = 'refresh')
    {
        /**
         * @var Connection $connection ;
         */
        $connection = \DB::connection($db);
        $connection->beginTransaction();

        $this->beforeApplicationDestroyed(function () use ($connection) {
            $connection->rollBack();
        });

        return $connection;
    }

    public function getUser()
    {
        return new User([
            'id' => 10,
            'username' => 'username',
            'password' => 'password',
            'nickName' => 'nickName',
            'token' => 'aabbccddeeffgghhiijjkkllmmnn'
        ]);
    }

    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__ . '/../bootstrap/app.php';
    }

    public function dump()
    {
        $content = $this->response->getContent();
        $data = @json_decode($content);
        if ($data) {
            print_r("\r\n");

            print_r(\GuzzleHttp\json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            print_r("\r\n");
        } else {
            print_r("\r\n");
            print_r($content);
            print_r("\r\n");
        }
        die();
    }

    public function print()
    {
        $content = $this->response->getContent();
        $data = @json_decode($content);
        if ($data) {
            print_r("\r\n");

            print_r(\GuzzleHttp\json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            print_r("\r\n");
        } else {
            print_r("\r\n");
            print_r($content);
            print_r("\r\n");
        }
    }

    public function testRootDocumentSuccess()
    {
        $this->json('GET', '/');
        $this->assertResponseOk();
    }
}