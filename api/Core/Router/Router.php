<?php

namespace Opu\Core\Router;

class Router
{
    /**
     * Path
     *
     * @var string path of http request URL.
     */
    protected $path;

    /**
     * Method
     *
     * @var string http request method
     */
    protected $method;

    /**
     * Params
     *
     * @var array array of params in url
     */
    public $params;

    /**
     * Routes
     *
     * @var array array of routes
     */
    private $routes;

    /**
     * IsMatch
     *
     * @var boolean is the url matched
     */
    private $isMatch;

    /**
     * Set essential values on construct
     */
    public function __construct()
    {
        // Get current path and remove unwanted charecters.
        $this->path = $this->clean(explode('?', $_SERVER['REQUEST_URI'])[0]);

        // Get the request method
        $this->method = strtolower($_SERVER['REQUEST_METHOD']);

        // array of routes
        $this->routes = array();
    }

    /**
     * Remove starting, ending slash and extra slashes
     *
     * @param string $path path to clean
     *
     * @return string return cleaned path string
     */
    private function clean(string $path)
    {
        return trim(preg_replace('/(\/+)/', '/', $path), "/");
    }

    /**
     * Register new route
     *
     * @param string $method method of the request (all valid http request methods)
     * @param string $pattern the path to be matched
     * @param callable $callback callback function
     *
     * @return void registers the route
     */
    public function add(string $method, string $pattern, $callback): Router
    {
        $this->routes[$method][$pattern] = $callback;

        return $this;
    }

    /**
     * Callback handler
     *
     * @param callable $callback callback function
     *
     * @return void
     */
    private function getView($callback)
    {
        call_user_func($callback, $this->params);
    }

    /**
     * Start routing.
     *
     * @return void
     */
    public function run()
    {
        // All routes for current request type
        if (!isset($this->routes[$this->method])) {
            return;
        }

        $routesArr = $this->routes[$this->method];

        // Loop all routes for current request type
        foreach ($routesArr as $pattern => $callback) {

            // Clean pattern
            $pattern = $this->clean($pattern);

            // Last match status
            $this->isMatch = false;

            // Parts of pattern
            $patternarray = explode('/', $pattern);

            // Parts of path
            $patharray = explode('/', $this->path);

            // Value key pair for pattern and path
            $valueKey = array();

            // Build the value key pair
            foreach ($patharray as $key => $value) {
                if (isset($patternarray[$key])) {
                    $valueKey[$value] = $patternarray[$key];
                } else {
                    $valueKey[$value] = "";
                }
            }

            // Params value from request url
            $this->params = array();

            // Match parts of the path to pattern
            foreach ($valueKey as $key => $value) {
                // Ignore everyting after ? and mark as matched
                if ($value === '?') {
                    $this->isMatch = true;
                    break;
                    // Exact match, mark as matched
                } elseif ($key === $value) {
                    $this->isMatch = true;
                    // It is a param check if param value is valid
                } elseif (strpos($value, ':') === 0) {
                    $regex = array(
                        ':abc' => '(^[a-zA-Z]+)$',
                        // only alphabets
                        ':num' => '(^[0-9]+)$',
                        // only numbers
                        ':slug' => '(^[a-zA-Z0-9-_]+)$',
                        // only slug with alphabets, numbers and (-_)
                        ':any' => '(.*)' // any value
                    );

                    // Match the regex patten and param value
                    if (preg_match("/$regex[$value]/", $key)) {
                        // Matched, put value in params and mark as matched
                        array_push($this->params, $key);
                        $this->isMatch = true;
                    } else {
                        // Did not match
                        $this->isMatch = false;
                        break;
                    }
                } else {
                    // Did not match
                    $this->isMatch = false;
                    break;
                }
            }

            // If matched handle the callback else keep trying.
            if ($this->isMatch) {
                $this->getView($callback);
                break;
            } else {
                continue;
            }
        }
    }
}
