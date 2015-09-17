<?php

namespace PHPixie\Tests\Security;

/**
 * @coversDefaultClass \PHPixie\Security\Tokens
 */
class TokensTest extends \PHPixie\Test\Testcase
{
    protected $builder;
    protected $database;
    
    protected $tokens;
    
    public function setUp()
    {
        $this->builder = $this->quickMock('\PHPixie\Security\Builder');
        $this->database = $this->quickMock('\PHPixie\Database');
        
        $this->tokens = new \PHPixie\Security\Tokens(
            $this->builder,
            $this->database
        );
    }
    
    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
        
    }
    
    /**
     * @covers ::token
     * @covers ::<protected>
     */
    public function testToken()
    {
        $params = array(
            'series'    => 'pixie',
            'userId'    => 5,
            'challenge' => 'test',
            'expires'   => 17,
            'string'    => 'trixie'
        );
        
        $token = call_user_func_array(array($this->tokens, 'token'), $params);
        $this->assertInstance($token, '\PHPixie\Security\Tokens\Token', $params);
        
        unset($params['string']);
        $token = call_user_func_array(array($this->tokens, 'token'), $params);
        $params['string'] = null;
        $this->assertInstance($token, '\PHPixie\Security\Tokens\Token', $params);
    }
    
    /**
     * @covers ::handler
     * @covers ::<protected>
     */
    public function testHandler()
    {
        $this->tokens = $this->tokensMock(array('buildStorage'));
        
        $random = $this->quickMock('\PHPixie\Security\Random');
        $this->method($this->builder, 'random', $random, array(), 0);
        
        $configData = $this->sliceData();
        
        $storageConfig = $this->sliceData();
        $this->method($configData, 'slice', $storageConfig, array('storage'));
        
        $storage = $this->getStorage();
        $this->method($this->tokens, 'buildStorage', $storage, array($storageConfig), 0);
        
        $handler = $this->tokens->handler($configData);
        $this->assertInstance($handler, '\PHPixie\Security\Tokens\Handler', array(
            'tokens'  => $this->tokens,
            'random'  => $random,
            'storage' => $storage
        ));
    }
    
    /**
     * @covers ::sqlStorage
     * @covers ::<protected>
     */
    public function testSqlStorage()
    {
        $connection = $this->abstractMock('\PHPixie\Database\Type\SQL\Connection');
        
        $configData = $this->sliceData();
        $this->method($configData, 'getRequired', 'pixie', array('table'), 0);
        
        $storage = $this->tokens->sqlStorage($connection, $configData);
        $this->assertInstance($storage, '\PHPixie\Security\Tokens\Storage\Database\SQL', array(
            'tokens'     => $this->tokens,
            'connection' => $connection
        ));
    }
    
    /**
     * @covers ::mongoStorage
     * @covers ::<protected>
     */
    public function testMongoStorage()
    {
        $connection = $this->quickMock('\PHPixie\Database\Driver\Mongo\Connection');
        
        $configData = $this->sliceData();
        $this->method($configData, 'getRequired', 'pixie', array('collection'), 0);
        
        $storage = $this->tokens->mongoStorage($connection, $configData);
        $this->assertInstance($storage, '\PHPixie\Security\Tokens\Storage\Database\Mongo', array(
            'tokens'     => $this->tokens,
            'connection' => $connection
        ));
    }
    
    /**
     * @covers ::buildStorage
     * @covers ::<protected>
     */
    public function testBuildStorage()
    {
        $this->tokens = $this->tokensMock(array(
            'databaseStorage'
        ));
        
        foreach(array('database') as $type) {
            $configData = $this->sliceData();
            $this->method($configData, 'get', $type, array('type', 'database'), 0);
            
            $storage = $this->getStorage();
            $this->method($this->tokens, $type.'Storage', $storage, array($configData), 0);
            $this->assertSame($storage, $this->tokens->buildStorage($configData));
        }
        
        $configData = $this->sliceData();
        $this->method($configData, 'get', 'invalid', array('type', 'database'), 0);
        
        $tokens = $this->tokens;
        $this->assertException(function() use($tokens, $configData){
            $tokens->buildStorage($configData);
        }, '\PHPixie\Security\Exception');
    }
    
    /**
     * @covers ::databaseStorage
     * @covers ::<protected>
     */
    public function testDatabaseStorage()
    {
        $this->tokens = $this->tokensMock(array(
            'sqlStorage',
            'mongoStorage'
        ));
        
        $this->databaseStorageTest();
        $this->databaseStorageTest('sql');
        $this->databaseStorageTest('mongo');
    }
    
    protected function databaseStorageTest($type = null)
    {
        $configData = $this->sliceData();
        $this->method($configData, 'get', 'pixie', array('connection', 'default'), 0);
        
        $method = null;
        
        if($type === 'sql') {
            $class  = '\PHPixie\Database\Type\SQL\Connection';
            $method = 'sqlStorage';
            
        }elseif($type === 'mongo') {
            $class  = '\PHPixie\Database\Driver\Mongo\Connection';
            $method = 'mongoStorage';
            
        }else{
            $class = '\PHPixie\Database\Connection';
        }
        
        $connection = $this->abstractMock($class);
        $this->method($this->database, 'get', $connection, array('pixie'), 0);
        
        if($method !== null) {
            $storage = $this->getStorage();
            $this->method($this->tokens, $method, $storage, array($connection, $configData), 0);
            $this->assertSame($storage, $this->tokens->databaseStorage($configData));
            
        }else{
            $tokens = $this->tokens;
            $this->assertException(function() use($tokens, $configData){
                $tokens->databaseStorage($configData);
            }, '\PHPixie\Security\Exception');
        }
    }
    
    protected function sliceData()
    {
        return $this->quickMock('\PHPixie\Slice\Data');
    }
    
    protected function getStorage()
    {
        $this->quickMock('\PHPixie\Security\Tokens\Storage');
    }
    
    protected function tokensMock($methods)
    {
        return $this->getMock(
            '\PHPixie\Security\Tokens',
            $methods,
            array($this->builder, $this->database)
        );
    }
}