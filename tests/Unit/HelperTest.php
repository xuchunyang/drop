<?php

namespace Tests\Unit;

use App\Helper;
use PHPUnit\Framework\TestCase;

class HelperTest extends TestCase
{
    public function test_dirname()
    {
        $this->assertEquals('', Helper::dirname('example.com/index.html'));
        $this->assertEquals('images', Helper::dirname('example.com/images/logo.png'));
        $this->assertEquals('media/images', Helper::dirname('example.com/media/images/logo.png'));
    }
}
