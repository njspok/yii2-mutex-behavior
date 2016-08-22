<?php

namespace njspok\yii\behaviors;

use yii\base\ActionEvent;
use yii\base\Controller;
use yii\mutex\Mutex;

// todo ActionMutexBehavior
class MutexBehavior extends \yii\base\Behavior
{
    /**
     * @var \yii\base\Controller
     */
    public $owner;

    /**
     * Mutex realization
     * @var \yii\mutex\Mutex
     */
    protected $mutex;

    public function events()
    {
        return [
            Controller::EVENT_BEFORE_ACTION => 'lock',
            Controller::EVENT_AFTER_ACTION => 'unlock',
        ];
    }

    public function lock(ActionEvent $event)
    {
        if ($this->getIsActionMarkAsMutex()) {
            $isAcquired = $this->mutex->acquire($this->getMutexName());
            if(!$isAcquired){
                $event->isValid = false;
            }
        }
    }

    public function unlock(ActionEvent $event)
    {
        if ($this->getIsActionMarkAsMutex()) {
            $this->mutex->release($this->getMutexName());
        }
    }

    public function getIsActionMarkAsMutex()
    {
        $method = new \ReflectionMethod(get_class($this->owner), $this->owner->action->actionMethod);
        $comment =  $method->getDocComment();
        return (strpos($comment, '@mutex') !== false);
    }

    public function setMutex($mutex)
    {
        if ($mutex instanceof Mutex) {
            $this->mutex = $mutex;
        } else {
            $this->mutex = \Yii::createObject($mutex);
        }
    }

    public function getMutex()
    {
        return $this->mutex;
    }

    protected function getMutexName()
    {
        return get_class($this->owner) . $this->owner->action->id;
    }
}