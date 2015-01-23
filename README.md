# Larkspur PHP Web Framework
## Introduction
Yes, I realise there is a mass of frameworks in PHP to choose from, but I wanted to develop one myself to learn PHP, and attempt to grasp 
the concepts of its OOP. Larkspur is a minimal (somewhat) framework that uses phpdoc to create the routes. For example:

```php
/**
 * @route /info/about-us
 * @method get
 */
public static function about()
{
	echo "A little about us";
}
```

In the above snippet, the function ```about()``` would get called when a user performs a GET request to ```/info/about-is```. Very basic stuff. 
This is the whole idea behind Larkspur -- making the routes human readable, easy to generate, and for developers to see what is going where. Not to 
mention forcing them to use comments ;)

## My first Larkspur app
As it stands, there is no real config. It's still a work in progress, ok?? So currently, you define which app you want to run in ```index.php``` 
and create your apps in the ```app/``` directory. Everything in ```lib``` is related to Larkspur, its modules and core code.. so best to stay away 
from there, unless you intend to install/write a new module, of course.

**index.php**
```php
require 'lib/Larkspur.php';
$app = new Larkspur();
$app->load_app('MyApp')->run();
```

**app/MyApp/Controller/Root.php**
```php
namespace MyApp\Controller;

class Root extends \Controller
{
	/**
	 * @route /
	 * @method get
	 */
	public static function index()
	{
		echo "<h1>My very first Larkspur App!</h1>";
	}
}
```

You now have a fully functional app which can be accessed from http://localhost:<port>!

## Dynamic routing
Larkspur supports basic dynamic routing, in where you can assign variable names inside your path, which will get caught 
and turned into parameters. An example:

```php
/**
 * @route /greet/:name
 * @method get
 */
public static function greet($name)
{
	echo "<h3>Hello there, $name";
}

/**
 * @route /code/:code/action/:action
 * @method get
 */
public static function perform_action($code, $action)
{
	echo "I'm going to perform '$action' on product '$code'";
}
```

In the first greet example, if the user browses to ```/greet/World```, they will be presented with "Hello there, World". 
The second example uses two variables, so the path might look like ```/code/TESTCODE/action/EDIT```.

## Bridging
Now you've learned the basics behind routing, we can look at another helpful feature: bridges. A bridge function will only return 
either true **or** false, nothing else. If a path has a bridge, then its bridge function is always called first. If it returns false, 
then the user will get a 403 not authorized message, otherwise it will follow through to the path they originally wanted.
You can also set stash values in bridge functions which will in turn be passed down to the action, but we'll look at those later.

```php
/**
 * If the name passed to /say is not World, then
 * return false. Otherwise, let's let them through
 */
public static function say_bridge()
{
	$args = get_func_args();
	if ($args[0] == "World")
		return true;

	return false;
}

/**
 * @route /say/:name
 * @bridge say_bridge
 * @method get
 */
public static function say($name)
{
	echo "Hello, $name";
}
```

As you can see, there's no strict phpdoc required for the bridge function, but it's nice to let people know what it's doing. We assigned 
```@bridge function_name``` to tell the action what method should be checked first. Obviously multiple actions can point to a single bridge, 
so use ```get_func_args()``` to grab the arguments if you need.

## Stash
Most frameworks will provide some kind of stash mechanism. This let's you create a temporary value that can be accessed in views, or between bridges and actions. There are two ways to access the stash

1. ```\Session::stash(array('foo' => 'bar')); // sets items in the stash```
2. ```\Session::stash('foo'); // retrieves. This will return 'bar'```

Let's take a look at a proper example where we set something in the bridge and access it from an action.

```php
/**
 * Set a value in stash to be accessed from actions using this bridge
 */
public static function set_value()
{
	\Session::stash(array('foo' => 'bar'));
	return true;
}

/**
 * @route /what-is/foo
 * @method get
 * @bridge set_value
 */
public static function what_is_foo()
{
	echo "Foo is: " . \Session::stash('foo');
}
```

## The auto method
The ```auto``` method is ran before every action in its controller. So you can stick things you want to check or set for
all actions.

```php
public static function auto()
{
    \Session::stash(array('title' => 'My lovely app'));
    echo "I will be ran first!<br>";
}

/**
 * @route /
 * @method get
 */
public static function index()
{
    echo "I will be ran second<br>";
    echo \Session::stash('title');
}
```

## Views
Printing your HTML from functions doesn't provide much flexibility, and it looks pretty ugly. Views allow you to use files in .html format 
to display your frontend to the user. At the moment Larkspur only supports Twig out-of-the-box. This will change in the future, of course.

**about.html**

```html
<!doctype html>
<html lang="en">
  <head>
    <title>{{ title }}</title>
  </head>
  <body>
  	<p>Hello, {{ name }}</p>
  </body>
</html>
```

**app/MyApp/Controller/Info.php**

```php
namespace MyApp\Controller;

class Info extends \Controller
{
	public static function about()
	{
		\Session::stash(
			array(
				'title' => 'About MyApp Company',
				'name'  => 'World'
			)
		);

		\View::detach();
	}
}
```

Once ```\View::detach()``` is called, it will tell Twig to process the template ```app/MyApp/views/about.html```, using the function 
name as the template name, followed by the .html prefix.
As you see, the contents of your stash is included in the template, however, you can also add your own variables by passing an 
associative array to the detach function.

```php
\View::detach(array('foo' => 'bar'));
```

If you have a stash and custom array in detach, it will merge them both.
