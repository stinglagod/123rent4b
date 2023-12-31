<?php

namespace rent\repositories;

use rent\entities\User\User;
use Yii;
use yii\caching\TagDependency;

class UserRepository
{
    public function findByUsernameOrEmail($value): ?User
    {
        return User::find()->andWhere(['or', ['username' => $value], ['email' => $value]])->one();
    }

    public function findByNetworkIdentity($network, $identity): ?User
    {
        return User::find()->joinWith('networks n')->andWhere(['n.network' => $network, 'n.identity' => $identity])->one();
    }

    public function get($id): User
    {
        return $this->getBy(['id' => $id]);
    }

    public function getByEmailConfirmToken($token): User
    {
        return $this->getBy(['email_confirm_token' => $token]);
    }

    public function getByEmail($email): User
    {
        return $this->getBy(['email' => $email]);
    }

    public function getByPasswordResetToken($token): User
    {
        return $this->getBy(['password_reset_token' => $token]);
    }

    public function existsByPasswordResetToken(string $token): bool
    {
        return (bool) User::findByPasswordResetToken($token);
    }

    public function save(User $user): void
    {
        if (!$user->save()) {
            throw new \RuntimeException('Saving error.');
        }
        TagDependency::invalidate(Yii::$app->cache, 'users');
    }

    public function remove(User $user): void
    {
        if (!$user->delete()) {
            throw new \RuntimeException('Removing error.');
        }
        TagDependency::invalidate(Yii::$app->cache, 'users');
    }
    public function find($entityOrId):?User
    {
        if (is_a($entityOrId,User::class)) {
            return $entityOrId;
        } else {
            return User::findOne($entityOrId);
        }
    }
###
    private function getBy(array $condition): User
    {
        if (!$user = User::find()->andWhere($condition)->limit(1)->one()) {
            throw new NotFoundException('User not found.');
        }
        return $user;
    }
}