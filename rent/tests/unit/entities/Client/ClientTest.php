<?php

namespace rent\tests\unit\entities\Client;

use Codeception\Test\Unit;
use common\fixtures\ClientFixture;
use common\fixtures\UserFixture;
use rent\entities\Client\Client;
use rent\entities\Client\UserAssignment;
use rent\entities\User\User;
use rent\tests\UnitTester;

/**
 * @property Client $client
 * @property User $user
 * @property UnitTester $tester
 */

class ClientTest extends Unit
{

    protected $client;
    protected $user;
    protected $tester;


    public function _before()
    {
        $this->tester->haveFixtures([
            'client' => [
                'class' => ClientFixture::class,
                'dataFile' => codecept_data_dir() . 'client.php'
            ],
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'user.php'
            ]

        ]);
        $this->client=Client::findOne(1000);
        $this->user=User::findOne(1001);
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

    public function testAssignUserSuccess()
    {
        $client=$this->client;
        $user=$this->user;
        $client->assignUser($user->id);

        /** @var UserAssignment $userAssignment */
        foreach ($client->userAssignments as $userAssignment) {
            $this->assertEquals($userAssignment->user_id,$user->id);
        }


    }
}
