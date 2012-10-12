<?php

set_include_path(implode(PATH_SEPARATOR, array(
    realpath(__DIR__ . '/../library/'),
    get_include_path(),
)));

require_once 'Rapid/Loader.php';
\Rapid\Loader::setAutoLoaders();