<?php

namespace App\Tests\Util;

use App\Util\UserUtil;
use PHPUnit\Framework\TestCase;

class UserUtilTest extends TestCase
{
    public function testGeneratePassword()
    {
        $results = UserUtil::generatePlainPassword(10);

        $this->assertEquals(strlen($results), 10);
    }
}
