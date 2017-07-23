<?php
namespace core;

class Language extends Model
{
    /** @var array      Language config             */
    public $config      = null;

    /** @var boolean    Language status             */
    public $enabled     = false;

    /** @var string     Language key                */
    public $lang_key    = null;

    /** @var array      Translatable words          */
    public $words       = null;

    function __construct()
    {
        parent::__construct();
        $this->config = $this->loadConfig();
        if(is_null($this->config)) {
            $this->app->error->exception("Language config file is empty", "Language");
        } else {
            $this->enabled = $this->config['enabled'];
        }
    }

    /**
     * Initialise language
     */
    public function init() {
        if ($this->enabled) {
            $this->setDefaultLanguage();
            $this->setLanguage($this->app->route->lang_key);
        }
    }

    /**
     * Load language config file
     */
    private function loadConfig() {
        if (is_file(APP.'config/language.php')) {
            return include(APP.'config/language.php');
        } else {
            $this->app->error->exception("Language config file doesn't exist", "Language");
            return null;
        }
    }

    /**
     * Load language translations
     */
    public function load($path, $lang=null) {
        $lang = (!is_null($lang) ? $lang : $this->lang_key);

        // check if language exists in config
        if (!$this->languageExists($lang))
            return null;

        // perhaps it was already loaded
        if (isset($words[$lang][$path]))
            return $words[$lang][$path];

        // otherwise load file and save it
        $words[$lang][$path] = $this->loadLanguageFile($path, $lang);
        return $words[$lang][$path];
    }

    /**
     * Load language file
     */
    private function loadLanguageFile($path, $lang) {
        $filepath = APP.'lang/'.$lang.'/'.$path.'.php';
        if (is_file($filepath)) {
            return include($filepath);
        } else {
            $this->app->error->exception("Specified language file doesn't exist", "Language");
            return null;
        }
    }

    /**
     * Checks if language key exists in config
     */
    private function languageExists($lang) {
        if (!in_array($lang, $this->config['languages'])) {
            $this->app->error->exception("Specified language doesn't exist", "Language");
            return false;
        } else {
            return true;
        }
    }

    /**
     * Set active language
     * @param string
     */
    private function setLanguage($lang) {
        if (is_null($lang) OR !$this->languageExists($lang)) {
            $this->setDefaultLanguage();
        } else {
            $this->lang_key = $lang;
        }
    }

    /**
     * Set default language
     */
    private function setDefaultLanguage() {
        if (isset($this->config['default']) && !empty($this->config['default'])) {
            if ($this->languageExists($this->config['default']))
                $this->lang_key =  $this->config['default'];
            else
                $this->app->error->exception("Default language set in config doesn't exist in language array", "Language");
        } else
            $this->app->error->exception("No default language set in config", "Language");
    }

}