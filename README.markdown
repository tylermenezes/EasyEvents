# EasyEvents #

EasyEvents is an event library for PHP.

## Requirements

* PHP &ge; 5.5

# Adding Events

EasyEvents supports three methods of adding events.

## C#-Style

The first, C#-style, is to create an event object and assign it to a variable. In theory, this would look something like:

    // Won't work
    class CatMonitor
    {
        public $cat_detected_event = new \EasyEvents\Event();
    }

Unfortunately, PHP only allows constants in property initializers, so the code ends up looking something more like this:

    // Actual working code
    class CatMonitor
    {
        public $cat_detected_event = null;
        public function __construct()
        {
            $this->cat_detected_event = new \EasyEvents\Event();
        }
    }

This is messy, but usable. (It's worse if you want static events; this is left as an exercise to the reader.)

## Wordpress-Style

Wordpress allows you to bind to events globally. Wordpress-style events are accessed by calling the static "glob" function, and passing it the name of the event:

    \EasyEvents\Event::glob('event_name');

The event name can be anything, but in practice, it should probably be namespaced in some sense.

## Traits

You can also add events by adding the `\EasyEvents\Eventable` trait to your class. You can then access events by using:

* `$this->event(:name)`
* `$this->mutable_event(:name)`
* `static::static_event(:name)`
* `static::static_mutable_event(:name)`

(These can, of course, be accessed outside your class, as well.

# Using it

## Registering Event Handlers

Regardless of which method you use to get the event object, once you've got one, you can attach event handlers with the `register()` function:

    CatMonitor::static_event('cat_detected`)
        ->register(function($cat_size) {
            sms('Cat of size '.$cat_size.' detected!);
        });

Register takes two optional arguments: `priority`, an integer used to determine which order the handlers are called in (lower is sooner, default 10000), and `name`, which identifies the particular handler.

`name` is mainly used for removing handlers, which can be done with the `deregister()` method of the event object. `deregister()` takes one argument: either the handler function, or the `name` of the handler. Passing a `name` when binding a handler is useful in that you don't have to store and pass the lambda to cancel it later.

## Using Event Handlers

When you're ready to trigger an event, call the `apply()` method of the event object. Any arguments passed to `apply()` will be passed to all the event handlers.

To trigger the event we bound in the previous example, we might do:

    static::static_event('cat_detected')->apply(10);

(If you're using a `MutableEvent`, you can only pass one argument.)

# Mutable?

EasyEvents provides two types of events by default, `Event` and `MutableEvent`.

`Event` is used purely for notifications.

`MutableEvent` is used for filtering. `MutableEvent`s can only take one argument, and must return one value.

An example of where we might want to use a `MutableEvent` is in rendering a blog post:

    $post = static::static_mutable_event('render')
                    ->apply($post);

The post would be sent through the chain of filters, the output of each feeding into the input of the next, until the chain is complete. The filtered post is then returned from the `apply()` method.

Although `MutableEvent`s can only take one argument, the argument can be an object or array.