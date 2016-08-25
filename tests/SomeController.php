<?php

namespace njspok\yii\behaviors\tests;

use njspok\yii\behaviors\MutexBehavior;

class SomeController extends \yii\base\Controller
{
    public function behaviors()
    {
        return [
            'mutex' => [
                'class' => MutexBehavior::class,
                'mutex' => StubMutex::class,
            ]
        ];
    }

    /**
     * This action non block
     */
    public function actionDoWithoutMutex()
    {

    }

    /**
     * This action block by mutex
     * @mutex
     */
    public function actionDoWithMutex()
    {

    }
}