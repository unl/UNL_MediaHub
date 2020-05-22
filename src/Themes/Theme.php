<?php

namespace Themes;

use UNL\Templates\Templates as UNL_Templates;


class Theme {

    const TYPE_APP = 'App';
    const TYPE_FIXED = 'Fixed';

    const THEME_UNL = 'unl';

    const CUSTOM_VERSION = 1;

    private $name;
    private $type;
    private $version;
    private $template;
    private $templateSavvy;
    private $WDNIncludePath;
    private $page;

    public function __construct($savvy, $name, $type = NULL, $version = NULL, $template = NULL) {
        $this->name = $name;
        $this->templateSavvy = clone $savvy;
        $this->templateSavvy->setTemplatePath(PATH_SEPARATOR.dirname(__FILE__) . '/' . $this->name . '/');

        if ($this->name == self::THEME_UNL) {
            $this->type = (!empty($type)) ? $type : UNL_Templates::VERSION_DEFAULT;
            $this->version = (!empty($version)) ? $version : UNL_Templates::VERSION_DEFAULT;
            $this->WDNIncludePath = \UNL_MediaHub::getRootDir() . '/www';
            $this->page = UNL_Templates::factory($this->type, $this->version);
        } else {
            $this->type = $type;
            $this->version = self::CUSTOM_VERSION;
            $this->template  = $template;
            $this->page = $this->setCustomThemePage();
        }
    }

    public function getName() {
        return $this->name;
    }

    public function getType() {
        return $this->type;
    }

    public function getPage() {
        return $this->page;
    }

    public function getWDNIncludePath() {
        return $this->WDNIncludePath;
    }

    public function renderThemeTemplate($context, $template){
        return $this->templateSavvy->render($context, $template);
    }

    public function addGlobal($name, $value){
        return $this->templateSavvy->addGlobal($name, $value);
    }

    private function setCustomThemePage() {

        $class = 'UNL\\Templates\\Custom' . $this->version . '\\' . $this->type;

        if (!class_exists($class)) {
            throw new Exception\UnexpectedValueException('Requested custom theme does not exist');
        }

        $instance = new $class;

        if (!$instance instanceof UNL_Templates) {
            throw new Exception\UnexpectedValueException('Template version must be an instance of Templates class');
        }

        $themeTemplate = dirname(__FILE__) . '/' . $this->name . '/' . $this->template;
        if (!file_exists($themeTemplate)) {
            throw new Exception\UnexpectedValueException('Requested custom theme template does not exist');
        }

        $instance->setTemplatePath($themeTemplate);

        return $instance;
    }
}
