<?php
namespace libs;

class view
{
    /** @var null/object Main application */
    private $app = null;

    /** @var null/object Language */
    private $language = null;

    /** @var null/object Route */
    private $route = null;

    /** @var null/object User */
    private $user = null;

    /** @var null/object Request */
    public $request = null;

    /**
     * Construct
     * @param object \libs\language
     * @param object \libs\route
     */
    function __construct()
    {
        $this->app      =& getInstance();
        $this->language =& $this->app->language;
        $this->route    =& $this->app->route;
        $this->user     =& $this->app->user;
        $this->request  = new \libs\request();
    }

    /**
     * Load view
     * @param string
     * @param array
     */
    public function load($path, $data=array()) {
        if (file_exists(APP . 'view/' . $path . '.php')) {
            extract($data, EXTR_OVERWRITE);
            require APP . 'view/' . $path . '.php';
        }
    }
}