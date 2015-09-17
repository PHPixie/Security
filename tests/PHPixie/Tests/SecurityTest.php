<?php

namespace PHPixie\Tests;

/**
 * @coversDefaultClass \PHPixie\Security
 */
class SecurityTest extends \PHPixie\Test\Testcase
{
    protected $database;
    
    protected $security;
    
    protected $builder;
    
    public function setUp()
    {
        $this->database = $this->quickMock('\PHPixie\Database');
        
        $this->security = $this->getMockBuilder('\PHPixie\Security')
            ->setMethods(array('buildBuilder'))
            ->disableOriginalConstructor()
            ->getMock();
        
        $this->builder = $this->quickMock('\PHPixie\Security\Builder');
        $this->method($this->security, 'buildBuilder', $this->builder, array(
            $this->database
        ), 0);
        
        $this->security->__construct(
            $this->database
        );
    }
    
    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstructor()
    {
        
    }
    
    /**
     * @covers ::buildBuilder
     * @covers ::<protected>
     */
    public function testBuildBuilder()
    {
        $this->security = new \PHPixie\Security(
            $this->database
        );
        
        $builder = $this->security->builder();
        $this->assertInstance($builder, '\PHPixie\Security\Builder', array(
            'database' => $this->database
        ));
    }
    
    /**
     * @covers ::builder
     * @covers ::<protected>
     */
    public function testBuilder()
    {
        $this->assertSame($this->builder, $this->security->builder());
    }
    
    /**
     * @covers ::password
     * @covers ::<protected>
     */
    public function testPassword()
    {
        $this->instanceTest('password');
    }
    
    /**
     * @covers ::random
     * @covers ::<protected>
     */
    public function testRandom()
    {
        $this->instanceTest('random');
    }
    
    /**
     * @covers ::tokens
     * @covers ::<protected>
     */
    public function testTokens()
    {
        $this->instanceTest('tokens');
    }
    
    protected function instanceTest($type)
    {
        $instance = $this->quickMock('\PHPixie\Security\\'.ucfirst($type));
        $this->method($this->builder, $type, $instance, array(), 0);
        $this->assertSame($instance, $this->security->$type());
    }
}