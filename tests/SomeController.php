<?php

namespace njspok\yii\behaviors\tests;

use njspok\yii\behaviors\MutexBehavior;

class SomeController extends \yii\base\Controller
{
    public function behaviors()
    {
        return [
            [
                'class' => MutexBehavior::class,
                'mutex' => StubMutex::class,
            ]

        ];
    }

    public function actionDoWithoutMutex()
    {

    }

    /**
     * @mutex
     */
    public function actionDoWithMutex()
    {

    }
}