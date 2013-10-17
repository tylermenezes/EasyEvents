<?php

require_once(dirname(dirname(__FILE)).'/EasyEvents/require.php');

/**
 * 
 *
 * @author      Tyler Menezes <tylermenezes@gmail.com>
 * @copyright   Copyright (c) 2013 Tyler Menezes. Released under the Perl Artistic License 2.0.
 *
 */
class EventableTest extends PHPUnit_Framework_TestCase
{
    public $w = false;
    public $x = false;
    public $y = false;
    public $z = false;
    public function test_trait()
    {
        $cls = new EventableTestExample();
        $cls->event('foo')->register(function(){
            $this->w = true;
        });
        $cls->mutable_event('foo')->register(function(){
            $this->x = true;
        });
        EventableTestExample::static_event('foo')->register(function(){
            $this->y = true;
        });
        EventableTestExample::static_mutable_event('foo')->register(function(){
            $this->z = true;
        });

        $this->w = false;
        $this->x = false;
        $this->y = false;
        $this->z = false;
        $this->assertEquals(false, $this->w);
        $this->assertEquals(false, $this->x);
        $this->assertEquals(false, $this->y);
        $this->assertEquals(false, $this->z);
        $cls->event('foo')->apply();
        $this->assertEquals(true, $this->w);
        $this->assertEquals(false, $this->x);
        $this->assertEquals(false, $this->y);
        $this->assertEquals(false, $this->z);

        $this->w = false;
        $this->x = false;
        $this->y = false;
        $this->z = false;
        $this->assertEquals(false, $this->w);
        $this->assertEquals(false, $this->x);
        $this->assertEquals(false, $this->y);
        $this->assertEquals(false, $this->z);
        $cls->mutable_event('foo')->apply(true);
        $this->assertEquals(false, $this->w);
        $this->assertEquals(true, $this->x);
        $this->assertEquals(false, $this->y);
        $this->assertEquals(false, $this->z);

        $this->w = false;
        $this->x = false;
        $this->y = false;
        $this->z = false;
        $this->assertEquals(false, $this->w);
        $this->assertEquals(false, $this->x);
        $this->assertEquals(false, $this->y);
        $this->assertEquals(false, $this->z);
        EventableTestExample::static_event('foo')->apply();
        $this->assertEquals(false, $this->w);
        $this->assertEquals(false, $this->x);
        $this->assertEquals(true, $this->y);
        $this->assertEquals(false, $this->z);

        $this->w = false;
        $this->x = false;
        $this->y = false;
        $this->z = false;
        $this->assertEquals(false, $this->w);
        $this->assertEquals(false, $this->x);
        $this->assertEquals(false, $this->y);
        $this->assertEquals(false, $this->z);
        EventableTestExample::static_mutable_event('foo')->apply(true);
        $this->assertEquals(false, $this->w);
        $this->assertEquals(false, $this->x);
        $this->assertEquals(false, $this->y);
        $this->assertEquals(true, $this->z);
    }
}


class EventableTestExample
{
    use \EasyEvents\Eventable;
}