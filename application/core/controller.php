<?php
namespace core;

class Controller
{
    /** @var null/object        App                   */
    protected $app = null;

    /** @var null/object        Route                 */
    private $route = null;

    /** @var null/object        \PDO                  */
    public $db = null;

    /** @var null/object        Language              */
    public $language = null;

    /** @var null/int           Language->lang_id     */
    public $lang_id = null;

    /** @var string             Language->lang_key    */
    public $lang_key = DEFAULT_LANGUAGE;

    /** @var null/object        Helper                */
    public $helper = null;

    /** @var null/object        Request               */
    public $request = null;

    /** @var null/object        View                  */
    public $view = null;

    /** @var null/object        Error                 */
    public $error = null;

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
        $this->helper  = new Helper($this->lang_key);
        $this->request = new Request();
    }


}
