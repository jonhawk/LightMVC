<?php
namespace model;

class models
{
    /** @var null/object Main application */
    protected $app = null;

    /**
     * @var null Database Connection
     */
    protected $db = null;

    /**
     * Construct and populate $app and $db
     */
    function __construct()
    {
        $this->app =& getInstance();
        $this->db  = $this->app->db;
    }
}