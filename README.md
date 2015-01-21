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

** index.php **
```php
require 'lib/Larkspur.php';
$app = new Larkspur();
$app->load_app('MyApp')->run();
```

** app/MyApp/Controller/Root.php **
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