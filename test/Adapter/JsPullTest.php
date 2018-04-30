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
use ZendTest\ProgressBar\TestAsset\JsPullStub;

/**
 * @group      Zend_ProgressBar
 */
class JsPullTest extends TestCase
{
    public function testJson()
    {
        $adapter = new JsPullStub();
        $adapter->notify(0, 2, 0.5, 1, 1, 'status');
        $output = $adapter->getLastOutput();

        $data = json_decode($output, true);

        $this->assertEquals(0, $data['current']);
        $this->assertEquals(2, $data['max']);
        $this->assertEquals(50, $data['percent']);
        $this->assertEquals(1, $data['timeTaken']);
        $this->assertEquals(1, $data['timeRemaining']);
        $this->assertEquals('status', $data['text']);
        $this->assertFalse($data['finished']);

        $adapter->finish();
        $output = $adapter->getLastOutput();

        $data = json_decode($output, true);

        $this->assertTrue($data['finished']);
    }
}
