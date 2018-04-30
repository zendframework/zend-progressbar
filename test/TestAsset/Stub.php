<?php

namespace ZendTest\ProgressBar\TestAsset;

class Stub extends \Zend\ProgressBar\ProgressBar
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
