<?php
namespace EasyEvents;

require_once(dirname(__FILE__).'/require.php');

/**
 * 
 *
 * @author      Tyler Menezes <tylermenezes@gmail.com>
 * @copyright   Copyright (c) 2013 Tyler Menezes. Released under the Perl Artistic License 2.0.
 *
 * @package     EasyEvents
 */
 trait Eventable
{
    protected $_easyevents_event = [];
    public function event($name)
    {
        if (!isset($this->_easyevents_event[$name])) {
            $this->_easyevents_event[$name] = new \EasyEvents\Event();
        }

        return $this->_easyevents_event[$name];
    }

     protected $_easyevents_mutable_event = [];
     public function mutable_event($name)
     {
         if (!isset($this->_easyevents_mutable_event[$name])) {
             $this->_easyevents_mutable_event[$name] = new \EasyEvents\MutableEvent();
         }

         return $this->_easyevents_mutable_event[$name];
     }

     protected static $_easyevents_event_static = [];
     public static function static_event($name)
     {
         if (!isset(static::$_easyevents_event_static[static::class])) {
             static::$_easyevents_event_static[static::class] = [];
         }

         if (!isset(static::$_easyevents_event_static[static::class][$name])) {
             static::$_easyevents_event_static[static::class][$name] = new \EasyEvents\Event();
         }

         return static::$_easyevents_event_static[static::class][$name];
     }

     protected static $_easyevents_mutable_event_static = [];
     public static function static_mutable_event($name)
     {
         if (!isset(static::$_easyevents_mutable_event_static[static::class])) {
             static::$_easyevents_mutable_event_static[static::class] = [];
         }

         if (!isset(static::$_easyevents_mutable_event_static[static::class][$name])) {
             static::$_easyevents_mutable_event_static[static::class][$name] = new \EasyEvents\MutableEvent();
         }

         return static::$_easyevents_mutable_event_static[static::class][$name];
     }
} 