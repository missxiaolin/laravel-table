<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2018/5/31
 * Time: 上午10:47
 */

namespace Tests\Test;

use Lin\Swoole\Common\File\File;
use Tests\Test\App\ExceptionJob;
use Tests\Test\App\ManyJob;
use Tests\Test\App\Queue;
use Tests\Test\App\TestJob;
use Tests\TestCase;

class BaseTest extends TestCase
{
    public function testTable()
    {
        $this->assertTrue(extension_loaded('swoole'));
    }
}