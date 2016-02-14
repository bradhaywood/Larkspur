<?php
\Larkspur::module('Utils');

class Controller {
    protected $routes = array();
    public function __construct() {
        $class = get_called_class();
        foreach (get_class_methods($class) as $action) {
            $refmethod = new \ReflectionMethod($class, $action);
            $doc       = $refmethod->getDocComment();
            $route  = \Larkspur\Module\Utils::getDocComment($doc, '@route');
            $method = \Larkspur\Module\Utils::getDocComment($doc, '@method');
            $bridge = \Larkspur\Module\Utils::getDocComment($doc, '@bridge');

            if (!empty($route) and !empty($method)) {
                if (substr($route, 0, 1) != '/') {
                    $cpat = '#' . \Larkspur::$app . '\\\\Controller\\\\(.+)' . '#';
                    if (preg_match($cpat, $class, $matches)) {
                        $minclass = str_replace(
                            '\\',
                            '/',
                            strtolower($matches[1])
                        );
                        if ($route == 'index')
                            $route = "/${minclass}";
                        else
                            $route = "/${minclass}/${route}";
                    }
                }
                array_push($this->routes, array(
                    'class'  => $class,
                    'route'  => $route,
                    'action' => "${class}::${action}",
                    'method' => $method,
                    'bridge' => !empty($bridge) ? $bridge : false
                ));
            }
        }
    }

    public function detach() {
        \View::detach(get_called_class());
    }

    public function get_routes() { return $this->routes; }
}
?>
