<?php
namespace controller;

use \core\Controller as Controller;

/**
 * Class Home
 *
 */
class Home extends Controller {

    /**
     * PAGE: index
     */
    public function index($type='')
    {
        if (!in_array($type, ['image','video','']))
            $this->error->show404();

        $helloClass     = new \Hello\HelloClass();
        $sampleLib      = new \lib\sample();
        $sampleModel    = new \model\sample();

        $data['hello']          = $helloClass->getHello();
        $data['title']          = $helloClass->getHello();
        // load view with prepared data
        $this->view->load('layout/header', $data);
        $this->view->load('index', $data);
        $this->view->load('layout/footer', $data);
    }

}
