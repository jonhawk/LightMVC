<?php
namespace Hello;

class HelloClass
{
    private $hello = 'Hello World!';

    function displayHello() {
        echo    $this->hello;
    }

    function getHello() {
        return  $this->hello;
    }

    function setHello($h) {
        $this->hello = $h;
    }
}