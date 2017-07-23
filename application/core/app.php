<?php
namespace core;

class App
{
    /** @var object             App         */
    private static $instance;

    /** @var null/object        \PDO        */
    public $db = null;

    /** @var null/object        Route       */
    public $route = null;

    /** @var null/object        Controller  */
    public $controller = null;

    /** @var null/object        Language    */
    public $language = null;

    /** @var null/object        View        */
    public $view = null;

    /** @var null/object        Error       */
    public $error = null;


    /**
     * Construct application
     * Order is important!
     */
    public function __construct()
    {
        // set instance of self
        self::$instance =& $this;

        // load view
        // this ones first, because error controller needs View
        $this->loadView();

        // load error controller
        // we load this early because other controllers might throw an error
        $this->loadError();

        // open database connection
        $this->openDatabaseConnection();

        // load language
        // at first we just load language class and it's config
        $this->loadLanguage();

        // load route
        // route needs to know, if language is enabled
        $this->loadRoute();

        // init language
        // to initialise language, route must first get language key form URL
        $this->initLanguage();

        // check language errors
        $this->checkLanguageErrors();

        // load main controller
        $this->loadController();
    }

    /**
     * Get instance
     */
    public static function &getInstance() {
        return self::$instance;
    }

    /**
     * Open database connection
     */
    private function openDatabaseConnection() {
        // set PDO connection options
        $options = array(\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ, \PDO::ATTR_ERRMODE => \PDO::ERRMODE_WARNING);
        // PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true (http://fischerlaender.de/php/pdo-mysql-and-nested-queries)
        try {
            $this->db = new \PDO(DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET, DB_USER, DB_PASS, $options);
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
        $this->route    = new Route();
    }

    /**
     * Load language
     */
    private function loadLanguage() {
        $this->language = new Language();
    }

    /**
     * Initialise language
     */
    private function initLanguage() {
        $this->language->init();
    }

    /**
     * Check for language errors (must load after error controller)
     */
    private function checkLanguageErrors() {
        // check if language library throwed an error
        if (!empty($this->error->exceptions['Language'])) {
            $this->error->show404();
        }
    }

    /**
     * Load view
     */
    private function loadView() {
        $this->view     = new View();
    }

    /**
     * Load error controller
     */
    private function loadError() {
        $this->error    = new Error();
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
                $reflection = new \ReflectionMethod($this->controller, $this->route->url_action);
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
