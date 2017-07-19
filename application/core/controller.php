<?php
namespace core;

use \libs as libs;
use \model as model;

class Controller
{
    /** @var null/object Main application */
    protected $app = null;

    /** @var null/object route */
    private $route = null;

    /** @var null/object Database Connection */
    public $db = null;

    /** @var null/object Language */
    public $language = null;

    /** @var null/int Language id */
    public $lang_id = null;

    /** @var string Language key */
    public $lang_key = DEFAULT_LANGUAGE;

    /** @var null/object Helper */
    public $helper = null;

    /** @var null/object Request */
    public $request = null;

    /** @var null/object View */
    public $view = null;

    /** @var null/object View */
    public $error = null;

    /** @var null/object User */
    public $user = null;


    /**
     * @param object \PDO
     * @param object \libs\view
     * @param object \libs\route
     * @param object \libs\error
     * @param object \libs\language
     * @param object \libs\helper
     */
    function __construct()
    {
        // get app instance
        $this->app      =& getInstance();
        // bind object references (for more convenient use)
        $this->db       =& $this->app->db;
        $this->view     =& $this->app->view;
        $this->route    =& $this->app->route;
        $this->error    =& $this->app->error;
        // language
        $this->language =& $this->app->language;
        // set current language key and id
        $this->lang_id  = $this->app->language->lang_id;
        $this->lang_key = $this->app->language->lang_key;
        // load helper
        $this->helper  = new libs\helper($this->lang_key);
        $this->request = new libs\request();
    }


}
