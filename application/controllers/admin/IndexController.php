<?php

namespace Controllers\Admin;
use \Rapid\Controller;

class IndexController extends \Rapid\Controller
{
    public function indexAction()
    {
        echo '<h1>My Admin Page</h1>';
    }
}