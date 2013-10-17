<?php

require_once(dirname(dirname(__FILE)).'/EasyEvents/require.php');

/**
 * 
 *
 * @author      Tyler Menezes <tylermenezes@gmail.com>
 * @copyright   Copyright (c) 2013 Tyler Menezes. Released under the Perl Artistic License 2.0.
 *
 */
class MutableEventTest extends PHPUnit_Framework_TestCase
{
    public $x = null;
    public $y = null;
    public function test_event()
    {
        $this->x = null;
        \EasyEvents\MutableEvent::glob('my.test.2')->register(function($name){
            return $name . 'x';
        });
        \EasyEvents\MutableEvent::glob('my.test.2')->register(function($name){
            return $name . 'xx';
        });

        $x = \EasyEvents\MutableEvent::glob('my.test.2')->apply('test');
        $this->assertEquals('testxxx', $x);
    }

    public function test_event_ordering()
    {
        $this->x = null;
        \EasyEvents\MutableEvent::glob('my.test.o')->register(function($name){
            return $name . 'y';
        }, 2);
        \EasyEvents\MutableEvent::glob('my.test.o')->register(function($name){
            return $name . 'x';
        }, 1);
        \EasyEvents\MutableEvent::glob('my.test.o')->register(function($name){
            return $name . 'z';
        }, 3);

        $x = \EasyEvents\MutableEvent::glob('my.test.o')->apply('test');
        $this->assertEquals('testxyz', $x);
    }

    public function test_collisions()
    {
        $this->x = null;
        $this->y = null;
        \EasyEvents\Event::glob('my.test.3')->register(function($name){
            $this->x = $name;
        });
        \EasyEvents\MutableEvent::glob('my.test.3')->register(function($name){
            $this->y = $name;
        });

        \EasyEvents\Event::glob('my.test.3')->apply('test');
        $this->assertEquals('test', $this->x);
        $this->assertEquals(null, $this->y);

        \EasyEvents\MutableEvent::glob('my.test.3')->apply('test2');
        $this->assertEquals('test', $this->x);
        $this->assertEquals('test2', $this->y);
    }
}
 