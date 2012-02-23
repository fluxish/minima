<?php

namespace Minima;

require_once dirname(__FILE__).'/../../../minima/router.php';

/**
 * Test class for Router.
 * Generated by PHPUnit on 2011-09-24 at 15:51:11.
 */
class RouterTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Router
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Router;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        
    }

    /**
     * Test for Router::parse()
     * Correctly formatted uri. 
     * Requires a controller named Hello
     * @test
     */
    public function test_parse_correct()
    {
        $input = 'hello/action_name.frmt/param_string/value/param_number/2';
        $output = array(
            'route' => array(
                'controller' => 'hello',
                'action' => 'action_name',
                'format' => 'frmt'
            ),
            'segments' => array(
                'param_string' => 'value',
                'param_number' => 2
            )
        );

        $result = $this->object->parse($input);
        $this->assertEquals($output, $result);
    }
    
    /**
     * Test for Router::parse()
     * Uri without a defined action ("index" by default). 
     * @test
     */
    public function test_parse_default_action()
    {
        $input = 'hello.frmt/param_string/value/param_number/2';
        
        $output = array(
            'route' => array(
                'controller' => 'hello',
                'action' => 'index',
                'format' => 'frmt'
            ),
            'segments' => array(
                'param_string' => 'value',
                'param_number' => 2
            )
        );

        $result = $this->object->parse($input);
        $this->assertEquals($output, $result);
    }
    
    /**
     * Test for Router::parse()
     * Uri without defined controller and action ("hello" and "index" by default). 
     * @test
     */
    public function test_parse_default_controller()
    {
        $input = '';
        
        $output = array(
            'route' => array(
                'controller' => 'hello',
                'action' => 'index'
            ),
            'segments' => array()
        );

        $result = $this->object->parse($input);
        $this->assertEquals($output, $result);
    }
    
    /**
     * Test for Router::parse()
     * Uri with only segments
     * @test
     */
    public function test_parse_segments()
    {
        $input = 'param_string/value/param_number/2';
        
        $output = array(
            'route' => array(
                'controller' => 'hello',
                'action' => 'index'
            ),
            'segments' => array(
                'param_string' => 'value',
                'param_number' => 2
            )
        );

        $result = $this->object->parse($input);
        $this->assertEquals($output, $result);
    }
}

?>
