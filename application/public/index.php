<?php
define('APPLICATION_PATH', __DIR__ . '/../');
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../'),
    realpath(APPLICATION_PATH . '/../library/'),
    get_include_path(),
)));

require_once APPLICATION_PATH . '/../library/Rapid/Loader.php';
\Rapid\Loader::setAutoLoaders();

$application = new \Rapid\Application(APPLICATION_PATH, 'development');
$application->addModule('admin/');
$application->run();