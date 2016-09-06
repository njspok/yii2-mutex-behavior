[![Build Status](https://travis-ci.org/njspok/yii2-mutex-behavior.svg?branch=master)](https://travis-ci.org/njspok/yii2-mutex-behavior/)

# yii2-mutex-behavior

This behavior add concurrent lock on action controller.
If lock acquire run same action again impossible.

## Requirements

- PHP 5.5.9 and later
- YII2 Framework

## How use
```php
class SomeController extends Controller
{
    public function behaviors()
    {
        return [
            [
                'class' => MutexBehavior::className(),
                'mutex' => FileMutex::className(),
                'annotationMark' => '@mark-mutex',
                // 'mutexName' => string or callable
            ]
        ];
    }
        
    /**
     * This action executed in any case
     */
    public function actionWithouMutex()
    {
		// some do
    }
    
    /**
     * This action not executed if already run
     * @mark-mutex
     */
    public function actionWithMutex()
    {
		// some do
    }
}
```

## Install
```
composer install
```

## Run tests
```
./vendor/bin/phpunit
```

## In the future
- trigger event if acquire lock fail
- configure mutex name