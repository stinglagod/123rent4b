<?php

namespace rent\jobs;

abstract class Job implements \yii\queue\JobInterface
{
    public function execute($queue): void
    {
        $listener = $this->resolveHandler();
        $listener($this, $queue);
    }

    private function resolveHandler(): callable
    {
        return [\Yii::createObject(static::class . 'Handler'), 'handle'];
    }
}