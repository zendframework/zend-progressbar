<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZendTest\ProgressBar\Adapter;

use PHPUnit\Framework\TestCase;
use ZendTest\ProgressBar\TestAsset\JsPushStub;

/**
 * @group      Zend_ProgressBar
 */
class JsPushTest extends TestCase
{
    public function testJson()
    {
        $result = [];

        $adapter = new JsPushStub(['finishMethodName' => 'Zend\ProgressBar\ProgressBar\Finish']);
        $adapter->notify(0, 2, 0.5, 1, 1, 'status');
        $output = $adapter->getLastOutput();

        $matches = preg_match('#<script type="text/javascript">parent.'
            . preg_quote('Zend\\ProgressBar\\ProgressBar\\Update') . '\((.*?)\);</script>#', $output, $result);
        $this->assertEquals(1, $matches);

        $data = json_decode($result[1], true);

        $this->assertEquals(0, $data['current']);
        $this->assertEquals(2, $data['max']);
        $this->assertEquals(50, $data['percent']);
        $this->assertEquals(1, $data['timeTaken']);
        $this->assertEquals(1, $data['timeRemaining']);
        $this->assertEquals('status', $data['text']);

        $adapter->finish();
        $output = $adapter->getLastOutput();

        $matches = preg_match('#<script type="text/javascript">parent.'
            . preg_quote('Zend\ProgressBar\ProgressBar\Finish') . '\(\);</script>#', $output, $result);
        $this->assertEquals(1, $matches);
    }
}
