<?php

namespace njspok\yii\behaviors\tests;

use yii\mutex\Mutex;

/**
 * Stub mutex do nothing, only log acquire and release mutex.
 * $log variable show mutex status.
 */
class StubMutex extends Mutex
{
    public $log = [];

    protected function acquireLock($name, $timeout = 0)
    {
        $this->log[] = 'acquire';
        return true;
    }

    protected function releaseLock($name)
    {
        $this->log[] = 'release';
        return true;
    }
}
