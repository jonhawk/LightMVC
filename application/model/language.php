<?php
namespace model;

class language
{
    /** @var null/object Main application */
    private $app = null;

    /** @var null Database Connection */
    private $db = null;

    /** @var int language id */
    public $lang_id = null;

    /** @var string language key */
    public $lang_key = null;

    /** @var string default language key */
    public $default_key = DEFAULT_LANGUAGE;

    /** @var array error status un message */
    public $error = null;

    /**
     * @param object $db A PDO database connection
     * @param string language key
     */
    function __construct()
    {
        $this->app =& getInstance();
        $this->db  = $this->app->db;
        $this->setLangId($this->app->route->lang_key);
    }

    /**
     * Set active language id
     * @param string
     */
    private function setLangId($lang_key) {

        if (is_null($lang_key)) {
            $this->setDefaultLang();
        } else {
            // check if specified language exists
            $sql = "SELECT * FROM ".DB_PRE."languages WHERE keyword = :lang_key LIMIT 1";
            $query = $this->db->prepare($sql);
            $parameters = array(':lang_key' => $lang_key);

            // useful for debugging: you can see the SQL behind above construction by using:
            // echo '[ PDO DEBUG ]: ' . Helper::debugPDO($sql, $parameters);  exit();

            $query->execute($parameters);

            if ($query->rowCount()>0) {
                $language = $query->fetch();
                $this->lang_id  = intval($language->id);
                $this->lang_key = $language->keyword;
            } else {
                $this->error = array('message' => 'Specified language doesn\'t exist!');
                $this->setDefaultLang();
            }
        }

    }

    /**
     * Set default language
     */
    private function setDefaultLang() {
        // check for default language
        $sql = "SELECT * FROM ".DB_PRE."languages WHERE is_main = 1 LIMIT 1";
        $query = $this->db->prepare($sql);
        $query->execute();

        if ($query->rowCount()>0) {
            $language = $query->fetch();
            $this->lang_id  = intval($language->id);
            $this->lang_key = $language->keyword;
        } else {
            $this->error = array('message' => 'No main language set in DB or no languages exist at all!');
        }
    }

    /**
     * Get translated word
     */
    public function word ($id) {
        // if input is int (for translate id)
        if (filter_var($id, FILTER_VALIDATE_INT)) {
            $where = 'tr.id = :translate_id';
            $parameters = array(':translate_id' => $id, ':lang_id' => $this->lang_id);

        // if input is string (for translate keyword)
        } elseif (mb_strlen($id)>0) {
            $where = 'tr.keyword = :translate_key';
            $parameters = array(':translate_key' => $id, ':lang_id' => $this->lang_id);

        } else {
            return '[[Invalid ID|Key]]';
        }

        // get translate from db
        $sql = "SELECT val.value FROM reviews_translates tr
                LEFT JOIN ".DB_PRE."translates_val val ON val.translate_id = tr.id AND val.language_id = :lang_id
                WHERE {$where} AND val.value IS NOT NULL LIMIT 1";

        $query = $this->db->prepare($sql);
        $query->execute($parameters);

        if ($query->rowCount() > 0) {
            $word = $query->fetch();
            return $word->value;
        } else {
            return '[[Missing translation]]';
        }
    }

    /**
     * Get translated word encoded
     */
    public function wordEnc ($id) {
        return htmlspecialchars($this->word($id), ENT_QUOTES, 'UTF-8', false);
    }
}