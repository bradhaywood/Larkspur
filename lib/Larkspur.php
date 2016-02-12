<?php
header('X-Powered-By: Larkspur');
require_once('lib/Larkspur/Controller.php');
require_once('lib/Larkspur/Params.php');
require_once('lib/Larkspur/ErrorHandler.php');
require_once('lib/Larkspur/View.php');
require_once('lib/Larkspur/Model.php');
require_once('lib/Larkspur/Session.php');
require_once('lib/Larkspur/Request.php');

class Larkspur {
    protected $routes = array();
    public static $app = null;
    public function __construct() {
    }
    
    public function load_app($app) {
        self::$app = $app;
        \View::init();
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator("app/$app/Controller")) as $filename) {
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            if ($ext == "php") {
                include $filename;
                //$base = basename($filename, ".php");
                $base = str_replace("app/${app}/Controller/", "", $filename);
                $base = str_replace(".php", "", $base);
                $base = str_replace("/", "\\", $base);
                $cont = "${app}\Controller\\" . $base;
                $cont_obj = new $cont();
                //$cont
                foreach ($cont_obj->get_routes() as $route) {
                    //echo "(route: " . $route["route"] . ", method: ". $route["method"]. ")\n";
                    array_push($this->routes, $route);
                }
            }
        }

        // load Models
        foreach (glob("app/$app/Model/*.php") as $filename) {
            include $filename;
            $base = basename($filename, ".php");
            $mod  = "${app}\Model\\" . $base;
            $mod_obj = new $mod();
            // if function build exists TODO
            \Model::init($base, $mod_obj->build());
        }   
        
        return $this;
    }

    public function module($class, $mod=null) {
        if ($mod !== null) {
            require_once("app/$class/Module/${mod}.php");
            return true;
        }

        require_once("lib/Larkspur/Module/${class}.php");
    }

    public static function error($code) {
        $err = new Larkspur\ErrorHandler($code);
        $err->hissy_fit();
    }

    public function run() {
        if (method_exists(self::$app . '\Controller\Root', '__catchall')) {
            call_user_func(self::$app . '\Controller\Root::__catchall');
            return true;
        }

        $method = $_SERVER['REQUEST_METHOD'];
        $uri    = $_SERVER['REQUEST_URI'];

        if (strstr($uri, '?'))
            $uri = substr($uri, 0, strpos($uri, '?'));

        $doAuto = function($route) {
            $class = $route['class'];
            call_user_func("${class}::auto");
        };

        $doBridge = function($route, $finalArgs) {
            $class = $route['class'];
            $bridge = $route['bridge'];
            $set = call_user_func_array("${class}::${bridge}", $finalArgs);

            if ($set === true) { return true; }
            \Larkspur::error(403);
        };

        foreach ($this->routes as $route) {
            $matches = array();
            if (strstr($route['route'], ':')) {
                if (preg_match_all('/(\/:\w+|\d+[\/|$])/', $route['route'], $matches)) {
                    $finalUri = $route['route'];
                    foreach ($matches[0] as $match) {
                        $pat = '\\' . $match;
                        $finalUri = preg_replace('/' . $pat . '(\/|$)/U', '/(\w+|\d+)/', $finalUri);
                    }

                    $finalUri = rtrim($finalUri, "/");
                    $uri_matches = array();
                    $finalUri = str_replace('/', '\/', $finalUri);
                    // got a match
                    if (strtolower($method) != $route['method'])
                        \Larkspur::error(400);

                    if (preg_match_all("/". $finalUri . "/", $uri, $uri_matches)) {
                        array_shift($uri_matches);
                        $finalArgs = array();
                        foreach ($uri_matches as $arg) { array_push($finalArgs, $arg[0]); }

                        if (method_exists($route['class'], 'auto'))
                            $doAuto($route);

                        if ($route['bridge'] !== false)
                            $doBridge($route, $finalArgs);

                        call_user_func_array($route['action'], $finalArgs);
                        \Session::stash(array());
                        return true;
                    }
                }
            }

            if ($route['route'] == $uri) {
                if (strtolower($method) != $route['method'])
                    \Larkspur::error(400);

                if (method_exists($route['class'], 'auto'))
                    $doAuto($route);

                if ($route['bridge'] !== false)
                    $doBridge($route);

                call_user_func($route['action']);
                return true;
            }
        }

        // made it this far, then we found no matching route
        \Larkspur::error(404);
    }
}
