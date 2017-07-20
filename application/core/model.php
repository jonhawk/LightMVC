<?php
namespace core;

class Model
{
    /** @var null/object    \core\App   */
    protected $app = null;

    /** @var null/object    \PDO        */
    protected $db = null;

    /**
     * Construct and populate $app and $db
     */
    function __construct() {
        $this->app =& getInstance();
        $this->db  = $this->app->db;
    }
}