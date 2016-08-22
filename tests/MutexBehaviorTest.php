<?php

namespace njspok\yii\behaviors\tests;

use Yii;
use yii\console\Application;

class MutexBehaviorTest extends \PHPUnit_Framework_TestCase
{
    public function testAll()
    {
        Yii::$app = new Application([
            'id' => 'test',
            'basePath' => false
        ]);
        $controller = new SomeController('some', Yii::$app);

        $controller->runAction('do-without-mutex');

        $controller->runAction('do-with-mutex');
        $controller->runAction('do-with-mutex');

    }
}
