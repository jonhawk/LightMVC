<?php
namespace core;

class Request
{
    public $post = null;

    public $get = null;

    public $request = null;

    public $is_post = false;

    public $is_get = false;

    public $is_request = false;

    function __construct()
    {
        $this->is_post     = (isset($_POST)    && !empty($_POST)    ?true:false);
        $this->is_get      = (isset($_GET)     && !empty($_GET)     ?true:false);
        $this->is_request  = (isset($_REQUEST) && !empty($_REQUEST) ?true:false);
        $this->post        = $this->post();
        $this->get         = $this->get();
        $this->request     = $this->request();
    }

    public function post($return_object=true, $clean=true, $strip_tags=false)
    {
        if (isset($_POST)) {
            $post = ($clean ? $this->xss_clean($_POST, $strip_tags) : $_POST);
            if ($return_object)
                return arrayToObject($post);
            else
                return $post;
        } else
            return null;
    }

    public function get($return_object=true, $clean=true, $strip_tags=false)
    {
        if (isset($_GET)) {
            $get = ($clean ? $this->xss_clean($_GET, $strip_tags) : $_GET);
            if ($return_object)
                return arrayToObject($get);
            else
                return $get;
        } else
            return null;
    }

    public function request($return_object=true, $clean=true, $strip_tags=false)
    {
        if (isset($_REQUEST)) {
            $request = ($clean ? $this->xss_clean($_REQUEST, $strip_tags) : $_REQUEST);
            if ($return_object)
                return arrayToObject($request);
            else
                return $request;
        } else
            return null;
    }

    public function xss_clean(array $data, $strip_tags=false)
    {
        foreach($data as $k => $v)
        {
            if (is_array($v) || is_object($v))
                    $data[$k] = $this->xss_clean($v , $strip_tags);
            else {
                if ($strip_tags)
                    $data[$k] = filter_var($v, FILTER_SANITIZE_STRING);
                else
                    $data[$k] = htmlEscape($v);
            }
        }
        return $data;
    }
}