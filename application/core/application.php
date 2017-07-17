<?php

class App
{
    /** @var object self instance */
    private static $instance;

    /** @var null/object database connection */
    public $db = null;

    /** @var null/object route */
    public $route = null;

    /** @var null/object controller */
    public $controller = null;

    /** @var null/object language */
    public $language = null;

    /** @var null/object view */
    public $view = null;

    /** @var null/object error controller */
    public $error = null;


    /**
     * Construct application
     * Order is important!
     * *Database connection must be first
     * *Route must load before language
     * *View must load after language
     * *Error must load after view and language
     * *Language errors must be checked after error controller
     * *Main controller must load last
     */
    public function __construct()
    {
        // set instance of self
        self::$instance =& $this;

        // open database connection
        $this->openDatabaseConnection();

        // load route
        $this->loadRoute();

        // load language
        $this->loadLanguage();

        // load view
        $this->loadView();

        // load error controller
        $this->loadError();

        // check language errors
        $this->checkLanguage();

        // load main controller
        $this->loadController();
    }

    /**
     * Get instance
     */
    public static function &getInstance()
    {
        return self::$instance;
    }

    /**
     * Open database connection
     */
    private function openDatabaseConnection() {
        // set PDO connection options
        $options = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING);
        // PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true (http://fischerlaender.de/php/pdo-mysql-and-nested-queries)
        try {
            $this->db = new PDO(DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET, DB_USER, DB_PASS, $options);
            // this is necessary because before this PHP version PDO didn't support charset in constructor
            if (version_compare('5.3.6', phpversion())>=0) {
                $this->db->exec("set names utf8");
            }
        } catch (\PDOException $e) {
            exit('Database connection could not be established.');
        }

    }

    /**
     * Load route and split URL
     */
    private function loadRoute() {
        $this->route    = new libs\route();
    }

    /**
     * Load language
     * @param string language key
     */
    private function loadLanguage() {
        $this->language = new model\language();
    }

    /**
     * Load view
     */
    private function loadView() {
        $this->view     = new libs\view();
    }

    /**
     * Load error controller
     */
    private function loadError() {
        $this->error    = new controller\Error();
        /*  */

    }

    /**
     * Check for language errors (must load after error controller)
     */
    private function checkLanguage() {
        // check if language library throwed an error
        if (!is_null($this->language->error)) {
            $this->error->show404();
        }
    }

    /**
     * Load main controller
     */
    private function loadController() {
        $this->route->getRoute();
        if ($this->route->url_controller) {

            // load controller
            $this->controller = new $this->route->url_controller();

            // check if specified method exists in controller
            if (method_exists($this->controller, $this->route->url_action)) {

                // check if method is public
                $reflection = new ReflectionMethod($this->controller, $this->route->url_action);
                if ($reflection->isPublic()) {

                    // call method
                    if (!empty($this->route->action_params))
                        // with parameters
                        call_user_func_array(array($this->controller, $this->route->url_action), $this->route->action_params);
                    else
                        // w/o parameters
                        $this->controller->{$this->route->url_action}();

                } else {
                    // method exists, but isn't public
                    $this->error->show404();
                }
            } else {
                // method doesn't exist, show error
                $this->error->show404();
            }
        } else {
            // controller doesn't exist, show error
            $this->error->show404();
        }
    }

    /**
     * Includes other files
     */
    public function loadFile($path, $required=false, $once=false) {
        if ($required) {
            if ($once) {
                require_once(APP.$path);
            } else {
                require(APP.$path);
            }
        } else {
            if ($once) {
                include_once(APP.$path);
            } else {
                include(APP.$path);
            }
        }
    }
}
