<?php

namespace njspok\yii\behaviors\tests;

use yii\mutex\Mutex;

class StubMutex extends Mutex
{
    protected function acquireLock($name, $timeout = 0)
    {
        // TODO: Implement acquireLock() method.
    }

    protected function releaseLock($name)
    {
        // TODO: Implement releaseLock() method.
    }
}
