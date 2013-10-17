<?php
namespace EasyEvents;

require_once(dirname(__FILE__).'/require.php');

/**
 * Represents an event which allows multiple event handlers to be registered.
 *
 * @author      Tyler Menezes <tylermenezes@gmail.com>
 * @copyright   Copyright (c) 2013 Tyler Menezes. Released under the Perl Artistic License 2.0.
 *
 * @package     EasyEvents
 */
class Event {
    protected $registered_handlers = [];
    protected static $global_events = [];

    /**
     * Creates a global event. Essentially a stop-gap until PHP supports non-constant code in property initializers,
     * because currently creating an event requires defining the name in the class, and then defining its type
     * elsewhere.
     *
     * @param $name     The name of the global event
     * @return static   The event object
     */
    public static function glob($name)
    {
        if (!isset(static::$global_events[static::class])) {
            static::$global_events[static::class] = [];
        }

        if (!isset(static::$global_events[static::class][$name])) {
            static::$global_events[static::class][$name] = new static();
        }

        return static::$global_events[static::class][$name];
    }

    public function __invoke()
    {
        call_user_func_array([$this, 'apply'], func_get_args());
    }

    /**
     * Executes all registered event handlers
     */
    public function apply()
    {
        $arguments = func_get_args();

        foreach ($this->get_prioritized_handlers() as $handler) {
            call_user_func_array($handler, $arguments);
        }
    }

    /**
     * Registers an event handler
     *
     * @param callable $lambda  The lambda to execute when the event fires.
     * @param int $priority     The priority, where lower priority is executed sooner. Default 10,000.
     * @param $name             The name of the handler. Used for allowing removal later without storing the lambda.
     */
    public function register(Callable $lambda, $priority = 10000, $name = null)
    {
        $this->registered_handlers[] = (object)[
            'lambda' => $lambda,
            'name' => $name,
            'priority' => $priority
        ];
    }

    /**
     * Removes a registered event handler
     *
     * @param $lambda_or_name The event handler lambda, or the name of a named event handler.
     */
    public function deregister($lambda_or_name)
    {
        $this->registered_handlers = array_filter($this->registered_handlers, function($elem) use ($lambda_or_name) {
            if (is_string($lambda_or_name)) {
                return $elem->name === $lambda_or_name;
            } else {
                return $elem->lambda === $lambda_or_name;
            }
        });
    }

    /**
     * Gets a list of handlers sorted by priority.
     *
     * @return array
     */
    protected function get_prioritized_handlers()
    {
        $local_handlers_copy = $this->registered_handlers;
        usort($local_handlers_copy, function($a, $b) {
            return $a->priority - $b->priority;
        });

        foreach ($local_handlers_copy as $elem) {
            yield $elem->lambda;
        }
    }
}