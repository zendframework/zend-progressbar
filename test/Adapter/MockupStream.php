<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZendTest\ProgressBar\Adapter;

class MockupStream
{

    private $position;

    private $test;

    public static $tests = [];

    // @codingStandardsIgnoreStart
    public function stream_open($path, $mode, $options, &$opened_path)
    {
        // @codingStandardsIgnoreEnd
        $url = parse_url($path);
        $this->test = $url["host"];
        $this->position = 0;

        static::$tests[$url["host"]] = '';
        return true;
    }

    // @codingStandardsIgnoreStart
    public function stream_read($count)
    {
        // @codingStandardsIgnoreEnd
        $ret = substr(static::$tests[$this->test], $this->position, $count);
        $this->position += strlen($ret);
        return $ret;
    }

    // @codingStandardsIgnoreStart
    public function stream_write($data)
    {
        // @codingStandardsIgnoreEnd
        $left = substr(static::$tests[$this->test], 0, $this->position);
        $right = substr(static::$tests[$this->test], $this->position + strlen($data));
        static::$tests[$this->test] = $left . $data . $right;
        $this->position += strlen($data);
        return strlen($data);
    }

    // @codingStandardsIgnoreStart
    public function stream_tell()
    {
        // @codingStandardsIgnoreEnd
        return $this->position;
    }

    // @codingStandardsIgnoreStart
    public function stream_eof()
    {
        // @codingStandardsIgnoreEnd
        return $this->position >= strlen(static::$tests[$this->test]);
    }

    // @codingStandardsIgnoreStart
    public function stream_seek($offset, $whence)
    {
        // @codingStandardsIgnoreEnd
        switch ($whence) {
            case SEEK_SET:
                if ($offset < strlen(static::$tests[$this->test]) && $offset >= 0) {
                    $this->position = $offset;
                    return true;
                } else {
                    return false;
                }
                break;

            case SEEK_CUR:
                if ($offset >= 0) {
                    $this->position += $offset;
                    return true;
                } else {
                    return false;
                }
                break;

            case SEEK_END:
                if (strlen(static::$tests[$this->test]) + $offset >= 0) {
                    $this->position = strlen(static::$tests[$this->test]) + $offset;
                    return true;
                } else {
                    return false;
                }
                break;

            default:
                return false;
        }
    }

    public function __destruct()
    {
        unset(static::$tests[$this->test]);
    }
}
