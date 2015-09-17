<?php

namespace PHPixie\Tests\Security;

/**
 * @coversDefaultClass \PHPixie\Security\Password
 */
class PasswordTest extends \PHPixie\Test\Testcase
{
    protected $password;
    
    public function setUp()
    {
        $this->password = new \PHPixie\Security\Password();
    }
    
    /**
     * @covers ::hash
     * @covers ::verify
     * @covers ::<protected>
     */
    public function testPassword()
    {
        $hash = $this->password->hash('test');
        
        $this->assertTrue($this->password->verify('test', $hash));
        $this->assertFalse($this->password->verify('blum', $hash));
    }
}