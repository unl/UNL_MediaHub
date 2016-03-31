<?php
if (file_exists(dirname(__FILE__).'/../config.inc.php')) {
    require_once dirname(__FILE__).'/../config.inc.php';
} else {
    require dirname(__FILE__).'/../config.sample.php';
}

ini_set('magic_quotes_gpc', false);
ini_set('magic_quotes_runtime', false);


$routes = include __DIR__ . '/../data/routes.php';

$router = new RegExpRouter\Router(array('baseURL' => UNL_MediaHub_Controller::$url));

$router->setRoutes($routes);

if (isset($_GET['model'])) {
    unset($_GET['model']);
}

$controller = new UNL_MediaHub_Controller($router->route($_SERVER['REQUEST_URI'], $_GET));

$outputcontroller = new UNL_MediaHub_OutputController();
$outputcontroller->addGlobal('controller', $controller);
$outputcontroller->addGlobal('user', UNL_MediaHub_AuthService::getInstance()->getUser());
$outputcontroller->setTemplatePath(dirname(__FILE__).'/templates/html');

if (isset($cache)) {
    $outputcontroller->setCacheInterface($cache);
}

switch($controller->options['format']) {
    case 'xml':
    case 'mosaic-xml':
    case 'rss':
    case 'js':
        $format = $controller->options['format'];
        if ('rss' == $format) {
            $format = 'xml';
        }
        //Remove the old template path, as these should not fall back to html
        $outputcontroller->setTemplatePath();
        //Now add the right format path
        $outputcontroller->addTemplatePath(dirname(__FILE__).'/templates/'.$format);
        break;
    case 'json':
        header('Content-type:application/json');
        $outputcontroller->addTemplatePath(dirname(__FILE__).'/templates/'.$controller->options['format']);
        break;
    default:
}

echo $outputcontroller->render($controller);