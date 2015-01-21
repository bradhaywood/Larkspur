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