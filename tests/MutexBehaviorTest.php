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

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetInvalidMutexName()
    {
        $behavior = new MutexBehavior();

        $this->setExpectedException('InvalidArgumentException');
        $behavior->mutexName = false;
        $behavior->mutexName = true;
        $behavior->mutexName = 0;
    }

    public function testSetGetMutexName()
    {
        $behavior = new MutexBehavior();

        // set string
        $behavior->mutexName = "some-name-for-mutex";
        $this->assertEquals("some-name-for-mutex", $behavior->mutexName);

        // set callable
        $name = function () {
            return "some-name-for-mutex";
        };
        $behavior->mutexName = $name;
        $this->assertEquals("some-name-for-mutex", $behavior->mutexName);
    }
}
