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
     * This is a sample page method which shows how to handle basic things.
     */
    public function index($type='all', $tag=null)
    {
        // check for valid type, otherwise show 404
        if (!empty($type) && !in_array($type, ['all','image','video']))
            $this->error->show404();

        // load some classes and save them in array
        $data['title']          = 'My Posts';
        $data['posts']          = new \model\posts($tag);
        $data['media']          = new \model\media($type);

        // load view with prepared data
        $this->view->load('_templates/header', $data);
        $this->view->load('posts', $data);
        $this->view->load('_templates/footer', $data);
    }

}
