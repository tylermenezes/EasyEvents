<?php

require_once(dirname(dirname(__FILE)).'/EasyEvents/require.php');

/**
 * 
 *
 * @author      Tyler Menezes <tylermenezes@gmail.com>
 * @copyright   Copyright (c) 2013 Tyler Menezes. Released under the Perl Artistic License 2.0.
 */
class EventTest extends PHPUnit_Framework_TestCase
{
    private $x = null;
    public function test_event()
    {
        $this->x = null;
        $cls = new EventTestExample();
        $cls->event_echo->register(function($name) {
            $this->x = $name;
        });
        $this->assertEquals(null, $this->x);
        $cls->callEvent();
        $this->assertEquals('test', $this->x);
    }

    public function test_global_event()
    {
        $this->x = null;
        $this->assertEquals(null, $this->x);

        \EasyEvents\Event::glob('my.event')->register(function($name){
            $this->x = $name;
        });
        $cls = new EventTestExample();
        $cls->callGlobEvent();

        $this->assertEquals('test', $this->x);
    }
}

class EventTestExample
{
    public $event_echo;

    public function __construct()
    {
        $this->event_echo = new \EasyEvents\Event();
    }

    public function callEvent()
    {
        $this->event_echo->apply('test');
    }

    public function callGlobEvent()
    {
        \EasyEvents\Event::glob('my.event')->apply('test');
    }
}