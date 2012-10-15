<?php

namespace Controllers;

class IndexController extends \Rapid\Controller
{
    public function indexAction()
    {
        return array(
            'var' => 'It\'s very simple web-framework!',
        );
    }

    public function myAction()
    {
        echo 'Ok. it\'s mine';
    }
}