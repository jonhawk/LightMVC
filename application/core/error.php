<?php
namespace core;

class Error
{
    /** @var null/object    \core\App   */
    private $app = null;

    /** @var null/array     Exceptions           */
    public $exceptions  = null;

    /**
     * Construct
     */
    function __construct() {
        $this->app =& getInstance();
    }

    /**
     * This method handles the error page that will be shown when a page is not found
     */
    public function show404() {
        // load views
        header("HTTP/1.0 404 Not Found");
        $this->app->view->load('layout/header');
        $this->app->view->load('error/404');
        $this->app->view->load('layout/footer');
        die();
    }

    public function exception($msg, $class='not-provided') {
        $this->exceptions[$class][] = $msg;
        if (ENVIRONMENT == 'development' || ENVIRONMENT == 'dev') {
            echo "<p>Exception ({$class}): {$msg}</p>";
        }
    }

    public function showExceptions() {
        if (is_array($this->exceptions) && !empty($this->exceptions)) {
            foreach ($this->exceptions as $ekey => $eval) {
                foreach ($eval as $msg) {
                    echo "<p>Exception ({$ekey}): {$msg}</p>";

                }
            }
        }
    }

}
