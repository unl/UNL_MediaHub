<?php

namespace DCF\Theme;

use UNL\Templates\Templates as UNL_Templates;

class DCF_Theme extends UNL_Templates {

    public static function factory($type, $version = '', $customConfig = NULL) {
        if (strtolower($type) == 'custom') {
            return static::custom_factory($type, $version, $customConfig);
        } else {
            return parent::factory($type, $version);
        }
    }

    private static function custom_factory($type, $version = '', $customConfig = NULL) {
        if (!$version && !empty(static::$options['version'])) {
            $version = static::$options['version'];
        } elseif (!$version) {
            $version = self::VERSION_DEFAULT;
        }

        $version = str_replace('.', 'x', $version);

        $class = 'UNL\\Templates\\Custom' . $version . '\\' . $type;

        if (!class_exists($class)) {
            throw new Exception\UnexpectedValueException('Requested template does not exist');
        }

        $instance = new $class;

        if (!$instance instanceof UNL_Templates) {
            throw new Exception\UnexpectedValueException('Template version must be an instance of Templates class');
        }

        return $instance;
    }
}

class DCF_Theme_Config {
    private $themeName;
    private $version;
    private $templatePath;

    public function __construct($config) {
        $this->themeName = $config['themeName'];
        $this->version = $config['version'];
        $this->templatePath = $config['$templatePath'];
    }
}

