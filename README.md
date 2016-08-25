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
            ]
        ];
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