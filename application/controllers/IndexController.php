<?php

namespace Controllers;

class IndexController extends \Rapid\Controller
{
    public function indexAction()
    {
        echo <<<HTML
<title>RapidPHP</title>
<p>It's very simple web-framework!</p>
HTML;

    }

    public function myAction()
    {
        echo 'Ok. it\'s mine';
    }
}