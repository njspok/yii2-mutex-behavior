<?php

namespace njspok\yii\behaviors;

use yii\base\ActionEvent;
use yii\base\Controller;
use yii\mutex\Mutex;

/**
 * This behavior add event acquire mutex before action
 * and release mutex after action executed. If mutex acquire
 * run same action again impossible.
 *
 * The annotation mark specify in PHPDOC for mark action as protected by mutex.
 *
 * Mutex name specify unique mutex lock.
 * Name maybe depends different parameters what give flexibility configuration.
 * For example, specified mutex name may locked:
 * - only one action
 * - all controllers actions
 * - one action in depend action parameters
 */
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

    /**
     * Mutex name
     * @var string|null|\Closure
     */
    protected $mutexName;

    /**
     * String marking action as blocked by mutex.
     * @var string
     */
    protected $annotationMark = '@mutex';

    public function events()
    {
        return [
            Controller::EVENT_BEFORE_ACTION => 'lock',
            Controller::EVENT_AFTER_ACTION => 'unlock',
        ];
    }

    /**
     * Try get lock.
     * Mark event as invalid if try lock fail.
     * @param ActionEvent $event
     */
    public function lock(ActionEvent $event)
    {
        if ($this->getIsActionMarkAsMutex()) {
            $isAcquired = $this->mutex->acquire($this->getMutexName());
            if(!$isAcquired){
                $event->isValid = false;
            }
        }
    }

    /**
     * Release lock.
     * @param ActionEvent $event
     */
    public function unlock(ActionEvent $event)
    {
        if ($this->getIsActionMarkAsMutex()) {
            $this->mutex->release($this->getMutexName());
        }
    }

    /**
     * Current action marked for lock?
     * @return bool
     */
    protected function getIsActionMarkAsMutex()
    {
        $method = new \ReflectionMethod(get_class($this->owner), $this->owner->action->actionMethod);
        $comment =  $method->getDocComment();
        return (strpos($comment, $this->annotationMark) !== false);
    }

    /**
     * Set mutex realization.
     * Param $mutex may be specified for \Yii::createObject().
     * @param Mutex|array $mutex
     * @throws \yii\base\InvalidConfigException
     */
    public function setMutex($mutex)
    {
        if ($mutex instanceof Mutex) {
            $this->mutex = $mutex;
        } else {
            $this->mutex = \Yii::createObject($mutex);
        }
    }

    /**
     * Get mutex.
     * @return Mutex
     */
    public function getMutex()
    {
        return $this->mutex;
    }

    /**
     * Set name annotation mark.
     * @param string $mark
     */
    public function setAnnotationMark($mark)
    {
        $this->annotationMark = $mark;
    }

    /**
     * Get name annotation mark.
     * @return string
     */
    public function getAnnotationMark()
    {
        return $this->annotationMark;
    }

    /**
     * Get mutex name.
     * @return string
     */
    public function getMutexName()
    {
        if ($this->mutexName) {
            if (is_string($this->mutexName)) {
                return $this->mutexName;
            }

            if (is_callable($this->mutexName)) {
                return call_user_func($this->mutexName);
            }
        }

        return get_class($this->owner) . $this->owner->action->id;
    }

    /**
     * Set mutex name.
     * @param null|string|\Closure $value
     */
    public function setMutexName($value)
    {
        if (is_string($value) || is_callable($value) || is_null($value)) {
            $this->mutexName = $value;
            return;
        }

        throw new \InvalidArgumentException("Mutex name must be a string or callable or null");
    }
}
