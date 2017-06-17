<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      https://github.com/zendframework/zendframework for the canonical source repository
 * @copyright Copyright (c) 2005-2017 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZendTest\ProgressBar;

use Zend\ProgressBar\ProgressBar;

class Stub extends ProgressBar
{
    public function sleep($seconds)
    {
        $this->startTime -= $seconds;
    }

    public function getCurrent()
    {
        return $this->adapter->getCurrent();
    }

    public function getMax()
    {
        return $this->adapter->getMax();
    }

    public function getPercent()
    {
        return $this->adapter->getPercent();
    }

    public function getTimeTaken()
    {
        return $this->adapter->getTimeTaken();
    }

    public function getTimeRemaining()
    {
        return $this->adapter->getTimeRemaining();
    }

    public function getText()
    {
        return $this->adapter->getText();
    }
}