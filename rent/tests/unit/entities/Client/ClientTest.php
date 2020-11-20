<?php

namespace rent\tests\unit\entities\Client;

use Codeception\Test\Unit;
use common\fixtures\ClientFixture;
use common\fixtures\UserFixture;
use rent\entities\Client\Client;
use rent\entities\Shop\Order\Status;

class ClientTest extends Unit
{
    /**
     * @var Client
     */
    protected $client;
    /**
     * @var \rent\tests\UnitTester
     */
    protected $tester;


    public function _before()
    {
        $this->tester->haveFixtures([
            'user' => [
                'class' => ClientFixture::class,
                'dataFile' => codecept_data_dir() . 'client.php'
            ]
        ]);
        $this->client=Client::findOne(1001);
    }

    public function testCreateSuccess()
    {
        $client = Client::create($name='Рога и Копыта',$status=Client::STATUS_ACTIVE);

        $this->assertEquals($name, $client->name);
        $this->assertEquals($status, $client->status);
    }
    public function testEditSuccess()
    {
        $client=$this->client;
        $client->edit($name='Рога и Копыта',$status=Client::STATUS_DELETED);

        $this->assertEquals($name, $client->name);
        $this->assertEquals($status, $client->status);
    }
}
