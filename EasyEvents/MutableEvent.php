<?php
namespace EasyEvents;

require_once(dirname(__FILE__).'/require.php');

/**
 * Represents an event which, when called, takes a value and returns a modified version of that value. When multiple
 * event handlers are registered, they will be chained; the result of one will be passed as the argument for the next.
 *
 * @author      Tyler Menezes <tylermenezes@gmail.com>
 * @copyright   Copyright (c) 2013 Tyler Menezes. Released under the Perl Artistic License 2.0.
 *
 * @package     EasyEvents
 */
 class MutableEvent extends Event
 {
     /**
      * Executes all registered event handlers
      */
     public function apply($arg)
     {
         if (count(func_get_args()) !== 1) {
             throw new \InvalidArgumentException('Mutable events must take and return exactly one argument.');
         }

         foreach ($this->get_prioritized_handlers() as $handler) {
             $arg = call_user_func_array($handler, [$arg]);
         }

         return $arg;
     }
} 