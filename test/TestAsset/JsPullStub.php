<?php

namespace ZendTest\ProgressBar\TestAsset;

class JsPullStub extends \Zend\ProgressBar\Adapter\JsPull
{
    // @codingStandardsIgnoreStart
    protected $_lastOutput = null;
    // @codingStandardsIgnoreEnd

    public function getLastOutput()
    {
        return $this->_lastOutput;
    }

    // @codingStandardsIgnoreStart
    protected function _outputData($data)
    {
        // @codingStandardsIgnoreEnd
        $this->_lastOutput = $data;
    }
}
