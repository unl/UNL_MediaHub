<?php
/**
 * This example demonstrates the usage of the UNL Templates.
 *
 * 
 * @package UNL_Templates
 */
ini_set('display_errors', true);
error_reporting(E_ALL);
set_include_path(dirname(dirname(__DIR__)).'/src'.PATH_SEPARATOR.dirname(dirname(__DIR__)).'/vendor/php');
require_once 'UNL/Templates.php';

// Optionally set the version you'd like to use
UNL_Templates::$options['version'] = 3.1;

$page = UNL_Templates::factory('Fixed', array('sharedcodepath' => 'sharedcode'));
$page->addScript('test.js');
$page->addScriptDeclaration('function sayHello(){alert("Hello!");}');
$page->addStylesheet('foo.css');
$page->addStyleDeclaration('.foo {font-weight:bold;}');
$page->titlegraphic     = 'Hello UNL Templates';
$page->pagetitle        = '<h1>This is my page title h1.</h1>';
$page->maincontentarea  = '<p>This is my main content.</p>';
$page->navlinks         = '<ul><li><a href="#">Hello world!</a></li></ul>';
$page->leftRandomPromo  = '';
$page->maincontentarea  .= highlight_file(__FILE__, true);
$page->loadSharedcodeFiles();
echo $page;
