<?php

namespace njspok\yii\behaviors\tests;

use njspok\yii\behaviors\MutexBehavior;
use Yii;
use yii\console\Application;

class MutexBehaviorTest extends \PHPUnit_Framework_TestCase
{
    public function testAll()
    {
        // create test controller
        $controller = new SomeController('some', new Application([
            'id' => 'test',
            'basePath' => false
        ]));
        $behavior = $controller->getBehavior('mutex');

        // no mutex called
        $controller->runAction('do-without-mutex');
        $this->assertEmpty($behavior->mutex->log);

        // lock and unlock mutex called
        $controller->runAction('do-with-mutex');
        $this->assertEquals(['acquire', 'release'], $behavior->mutex->log);

        // lock and unlock mutex called again
        $controller->runAction('do-with-mutex');
        $this->assertEquals(
            ['acquire', 'release', 'acquire', 'release'],
            $behavior->mutex->log
        );
    }

    public function testSetGetMutex()
    {
        $behavior = new MutexBehavior();
        $mutex = new StubMutex();
        $behavior->setMutex($mutex);
        $this->assertEquals($mutex, $behavior->getMutex());
    }

    public function testSetGetAnnotationMark()
    {
        $behavior = new MutexBehavior();
        $behavior->setAnnotationMark('@my-mark');
        $mark = $behavior->getAnnotationMark();
        $this->assertEquals('@my-mark', $mark);
    }
}
