<?php

namespace PHPixie\Tests\Security;

/**
 * @coversDefaultClass \PHPixie\Security\Builder
 */
class BuilderTest extends \PHPixie\Test\Testcase
{
    protected $database;
    
    protected $builder;
    
    public function setUp()
    {
        $this->database = $this->quickMock('\PHPixie\Database');
        
        $this->builder = new \PHPixie\Security\Builder($this->database);
    }
    
    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
    
    }
    
    /**
     * @covers ::password
     * @covers ::<protected>
     */
    public function testPassword()
    {
        $this->instanceTest('password', '\PHPixie\Security\Password');
    }
    
    /**
     * @covers ::random
     * @covers ::<protected>
     */
    public function testRandom()
    {
        $this->instanceTest('random', '\PHPixie\Security\Random');
    }
    
    /**
     * @covers ::tokens
     * @covers ::<protected>
     */
    public function testTokens()
    {
        $this->instanceTest('tokens', '\PHPixie\Security\Tokens', array(
            'builder' => $this->builder,
            'database' => $this->database
        ));
    }
    
    protected function instanceTest($method, $class, $attributeMap = array())
    {
        $instance = $this->builder->$method();
        
        $this->assertInstance($instance, $class, $attributeMap);
        $this->assertSame($instance, $this->builder->$method());
    }
}