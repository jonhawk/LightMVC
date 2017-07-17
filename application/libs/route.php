<?php
namespace libs;

class route
{
    /** @var null The controller */
    public $url_controller = null;

    /** @var null The method (of the above controller), often also named "action" */
    public $url_action = null;

    /** @var array URL parameters */
    public $action_params = array();

    /** @var array URL parameters */
    public $url_parts = array();

    /** @var string Language key */
    public $lang_key = null;

    /** @var array routes config */
    public $routes = array();

    /**
     * Construct
     */
    function __construct() {
        $this->loadRoutesConfig();
        $this->splitUrl();
    }

    /**
     * Get and split the URL
     */
    public function splitUrl() {
        if (isset($_GET['url'])) {

            // split URL
            $url = trim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_STRING);
            $url = explode('/', $url);

            // parse custom urls
            $url = $this->parseRoutes($url);

            if (LANGUAGE_ENABLED) {
                // set lang key
                $this->lang_key = isset($url[0]) ? $url[0] : null;
                // set controller
                $this->url_controller = isset($url[1]) ? $url[1] : null;
                // and action
                $this->url_action = isset($url[2]) ? $url[2] : null;

                // store URL parts
                $this->url_parts = array_values($url);

                // remove controller and action from URL parts
                unset($url[0], $url[1], $url[2]);
            } else {
                // set controller
                $this->url_controller = isset($url[0]) ? $url[0] : null;
                // and action
                $this->url_action = isset($url[1]) ? $url[1] : null;

                // store URL parts
                $this->url_parts = array_values($url);

                // remove controller and action from URL parts
                unset($url[0], $url[1]);
            }

            // store action params
            $this->action_params = array_values($url);

        }
    }

    /**
     * Load routes config file
     */
    private function loadRoutesConfig() {
        if (is_file(APP.'config/routes.php')) {
            include(APP.'config/routes.php');
        }
        $this->routes = (isset($route) ? $route : array());
    }

    /**
     * Load controller, action, params
     */
    public function getRoute(){
        // load default controller if none specified
        if (!$this->url_controller) {

            $this->url_controller = 'controller\home';
            $this->url_action = 'index';

        // check if specified controller file exists
        } elseif (file_exists(APP . 'controller/' . $this->url_controller . '.php')) {

            $this->url_controller = 'controller\\' . $this->url_controller;
            $this->url_action = (strlen($this->url_action) == 0 ? 'index' : $this->url_action);

        // perhaps specified controller is located in subdirectory?
        } elseif (strlen($this->url_action) != 0 AND file_exists(APP . 'controller/' . $this->url_controller . '/' . $this->url_action . '.php')) {

            $this->url_controller = 'controller\\' . $this->url_controller . '\\' . $this->url_action;
            if (isset($this->action_params[0]) AND strlen($this->action_params[0]) != 0) {
                $this->url_action = $this->action_params[0];
                unset($this->action_params[0]);
                array_values($this->action_params);
            } else {
                $this->url_action = 'index';
            }

        // or maybe there's a default controller in directory?
        } elseif (file_exists(APP . 'controller/' . $this->url_controller . '/home.php')) {

            $this->url_controller = 'controller\\' . $this->url_controller . '\\home';
            $this->url_action = (strlen($this->url_action) == 0 ? 'index' : $this->url_action);

        // if not, load default controller with params
        } elseif ($this->url_controller) {

            $this->action_params = array($this->url_controller);
            $this->url_controller = 'controller\home';
            $this->url_action = 'index';

        // if none of the above pass, reset controller param
        } else {
            $this->url_controller = null;
        }
    }

    /**
     *  Parses routes from routes.php config file
     */
    function parseRoutes($url_parts) {

        // turn the segment array into a URI string
        $uri = implode('/', $url_parts);

        // is there a literal match?  If so we're done
        if (isset($this->routes[$uri]))
        {
            return explode('/', $this->routes[$uri]);
        }

        // loop through the route array looking for wild-cards
        foreach ($this->routes as $key => $val)
        {
            // convert wild-cards to regex
            $key = str_replace(':any', '.+', str_replace(':num', '[0-9]+', $key));

            // does the regex match?
            if (preg_match('#^'.$key.'$#', $uri))
            {
                // do we have a back-reference?
                if (strpos($val, '$') !== FALSE AND strpos($key, '(') !== FALSE)
                {
                    $val = preg_replace('#^'.$key.'$#', $val, $uri);
                }

                return explode('/', $val);
            }
        }

        // match not found, default route returned
        return $url_parts;
    }

    /**
     * Build link
     */
    public function link($url='', $lang_key=null) {
        if (LANGUAGE_ENABLED) {
            if (is_null($lang_key)) {
                return URL . $this->lang_key . '/' . $url;
            } else {
                return URL . $lang_key . '/' . $url;
            }
        } else {
            return URL . $url;
        }

    }

}