<?php

namespace ZendTest\ProgressBar\TestAsset;

class MockUp extends \Zend\ProgressBar\Adapter\AbstractAdapter
{
    // @codingStandardsIgnoreStart
    protected $_current;
    protected $_max;
    protected $_percent;
    protected $_timeTaken;
    protected $_timeRemaining;
    protected $_text;
    // @codingStandardsIgnoreEnd

    public function notify($current, $max, $percent, $timeTaken, $timeRemaining, $text)
    {
        $this->_current       = $current;
        $this->_max           = $max;
        $this->_percent       = $percent;
        $this->_timeTaken     = $timeTaken;
        $this->_timeRemaining = $timeRemaining;
        $this->_text          = $text;
    }

    public function finish()
    {
    }

    public function getCurrent()
    {
        return $this->_current;
    }

    public function getMax()
    {
        return $this->_max;
    }

    public function getPercent()
    {
        return $this->_percent;
    }

    public function getTimeTaken()
    {
        return $this->_timeTaken;
    }

    public function getTimeRemaining()
    {
        return $this->_timeRemaining;
    }

    public function getText()
    {
        return $this->_text;
    }
}
