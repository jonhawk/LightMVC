<?php
namespace controller;

/**
 * Class Error
 *
 */
class Error extends Controller
{
    /**
     * PAGE: index
     * This method handles the error page that will be shown when a page is not found
     */
    public function show404()
    {
        // load views
        header("HTTP/1.0 404 Not Found");
        $this->view->load('_templates/header');
        $this->view->load('error/index');
        $this->view->load('_templates/footer');
        die();
    }

}
