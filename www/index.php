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
$outputcontroller->setTemplatePath(dirname(__FILE__).'/templates/html');

if (isset($cache)) {
    $outputcontroller->setCacheInterface($cache);
}

header('X-Frame-Options: SAMEORIGIN');

switch($controller->options['format']) {
    case 'xml':
    case 'mosaic-xml':
    case 'rss':
    case 'js':
    case 'iframe':
        $format = $controller->options['format'];
        if ('rss' == $format) {
            //rss should be the same as xml
            $format = 'xml';
        }
        
        $outputcontroller->addTemplatePath(dirname(__FILE__).'/templates/'.$format);
        break;
    case 'json':
        $outputcontroller->addTemplatePath(dirname(__FILE__).'/templates/'.$controller->options['format']);
        break;
    default:
}

echo $outputcontroller->render($controller);