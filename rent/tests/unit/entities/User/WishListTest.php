<?php

namespace rent\tests\unit\entities\Client;

use Codeception\Test\Unit;
use common\fixtures\UserFixture;
use rent\entities\Client\Client;
use rent\entities\User\User;
use rent\tests\UnitTester;

/**
 * @property Client $client
 * @property User $user
 * @property UnitTester $tester
 */

class WishListTest extends Unit
{

    protected $client;
    protected $user;
    protected $tester;


    public function _before()
    {
        $this->tester->haveFixtures([
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'user.php'
            ],
        ]);
        $this->client=Client::findOne(1001);
        $this->user=User::findOne(1001);
    }

    public function testAddToWishListSuccess()
    {
        $user=$this->user;
        $user->addToWishList($product_id=1001);

        foreach ($user->wishlistItems as $wishlistItem) {
            $this->assertEquals($wishlistItem->product_id,$product_id);
        }
    }
    public function testAddDuplicateToWishListError()
    {
        $user=$this->user;
        $user->addToWishList($product_id=1001);

        $this->expectExceptionMessage('Item is already added.');
        $user->addToWishList($product_id=1001);
    }
    public function testRemoveFromWishListSuccess()
    {
        $user=$this->user;
        $user->addToWishList($product_id=1001);
        $user->removeFromWishList($product_id=1001);

        $this->assertEmpty($user->wishlistItems);
    }
    public function testRemoveFromWishListError()
    {
        $user=$this->user;
        $this->expectExceptionMessage('Item is not found.');
        $user->removeFromWishList($product_id=1001);
    }
}
