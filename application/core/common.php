<?php
/**
 * Get instance of application
 */
function &getInstance()
{
    return \App::getInstance();
}

/**
 * Returns HTML escaped variable (works for arrays too)
 */
function htmlEscape($var) {
    if (is_array($var)) {
        return array_map('htmlEscape', $var);
    } else {
        return htmlspecialchars($var, ENT_QUOTES, "UTF-8");
    }
}

/**
 * Gets Youtube ID from URL
 */
function getYoutubeId($youtube_id) {
    preg_match('/(\\?v=|\/v\/|\.be\/)([-_a-zA-Z0-9]+)/i',$youtube_id,$matches);
    return end($matches);
}

/**
 * Checks if URL is valid
 */
function isValidUrl($url) {
    return preg_match('_^(?:(?:https?|ftp)://)(?:\S+(?::\S*)?@)?(?:(?!10(?:\.\d{1,3}){3})(?!127(?:\.\d{1,3}){3})(?!169\.254(?:\.\d{1,3}){2})(?!192\.168(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\x{00a1}-\x{ffff}0-9]+-?)*[a-z\x{00a1}-\x{ffff}0-9]+)(?:\.(?:[a-z\x{00a1}-\x{ffff}0-9]+-?)*[a-z\x{00a1}-\x{ffff}0-9]+)*(?:\.(?:[a-z\x{00a1}-\x{ffff}]{2,})))(?::\d{2,5})?(?:/[^\s]*)?$_ius',$url);
}

/**
 * Set HTTP Status Header
 */
function setStatusHeader($code = 200, $text = '') {
    // set status code messages
    $stati = array(
        200	=> 'OK',
        201	=> 'Created',
        202	=> 'Accepted',
        203	=> 'Non-Authoritative Information',
        204	=> 'No Content',
        205	=> 'Reset Content',
        206	=> 'Partial Content',

        300	=> 'Multiple Choices',
        301	=> 'Moved Permanently',
        302	=> 'Found',
        304	=> 'Not Modified',
        305	=> 'Use Proxy',
        307	=> 'Temporary Redirect',

        400	=> 'Bad Request',
        401	=> 'Unauthorized',
        403	=> 'Forbidden',
        404	=> 'Not Found',
        405	=> 'Method Not Allowed',
        406	=> 'Not Acceptable',
        407	=> 'Proxy Authentication Required',
        408	=> 'Request Timeout',
        409	=> 'Conflict',
        410	=> 'Gone',
        411	=> 'Length Required',
        412	=> 'Precondition Failed',
        413	=> 'Request Entity Too Large',
        414	=> 'Request-URI Too Long',
        415	=> 'Unsupported Media Type',
        416	=> 'Requested Range Not Satisfiable',
        417	=> 'Expectation Failed',

        500	=> 'Internal Server Error',
        501	=> 'Not Implemented',
        502	=> 'Bad Gateway',
        503	=> 'Service Unavailable',
        504	=> 'Gateway Timeout',
        505	=> 'HTTP Version Not Supported'
    );

    // check specified $code number
    if ($code == '' OR ! is_numeric($code)) { exit('Status codes must be numeric'); }

    // set text for $code
    if (isset($stati[$code]) AND $text == '') { $text = $stati[$code]; }

    // missing status message
    if ($text == '') { exit('No status text available.  Please check your status code number or supply your own message text.'); }

    // get server protocol
    $server_protocol = (isset($_SERVER['SERVER_PROTOCOL'])) ? $_SERVER['SERVER_PROTOCOL'] : FALSE;

    // if using cgi
    if (substr(php_sapi_name(), 0, 3) == 'cgi') {
        header("Status: {$code} {$text}", TRUE);

    // known server protocol
    } elseif ($server_protocol == 'HTTP/1.1' OR $server_protocol == 'HTTP/1.0') {
        header($server_protocol." {$code} {$text}", TRUE, $code);

    // unknown server protocol
    } else {
        header("HTTP/1.1 {$code} {$text}", TRUE, $code);
    }
}

/**
 * Returns number representing percentage from $total
 * @param int $current
 * @param int $total
 * @return float|int
 */
function getPercent($current, $total) {
    if (intval($current)!=0) {
        return round(intval($current)/intval($total)*100);
    } else {
        return 0;
    }
}

/**
 * Convert multidimensional array to object
 * @param array $array
 * @return \stdClass
 */
function arrayToObject($array) {
    $obj = new \stdClass;
    foreach($array as $k => $v) {
        if(strlen($k)) {
            if(is_array($v)) {
                $obj->{$k} = arrayToObject($v); //RECURSION
            } else {
                $obj->{$k} = $v;
            }
        }
    }
    return $obj;
}

/**
 * Convert object to array
 */
function objectToArray($object) {
    if(!is_object($object) && !is_array($object))
        return $object;

    return array_map('objectToArray', (array) $object);
}


/**
 * Print object properties
 * @param string $var
 * @param array $arrayOfObjectsToHide
 * @param int $fontSize
 */
function dbg($var, $arrayOfObjectsToHide=array(), $fontSize=11) {
    $text = print_r($var, true);
    $text = str_replace('<', '&lt;', $text);
    $text = str_replace('>', '&gt;', $text);

    foreach ($arrayOfObjectsToHide as $objectName) {
        $searchPattern = '#(\W'.$objectName.' Object\n(\s+)\().*?\n\2\)\n#s';
        $replace = "$1<span style=\"color: #FF9900;\">";
        $replace .= "--&gt; -- dbg() -- &lt;--</span>)";
        $text = preg_replace($searchPattern, $replace, $text);
    }

    // color code objects
    $text = preg_replace(
        '#(\w+)(\s+Object\s+\()#s',
        '<span style="color: #079700;">$1</span>$2',
        $text
    );
    // color code object properties
    $pattern = '#\[(\w+)\:(public|private|protected)\]#';
    $replace = '[<span style="color: #000099;">$1</span>:';
    $replace .= '<span style="color: #009999;">$2</span>]';
    $text = preg_replace($pattern, $replace, $text);

    echo '<pre style="
        font-size: '.$fontSize.'px;
        line-height: '.$fontSize.'px;
        background-color: #fff; padding: 10px;
        ">'.$text.'</pre>
    ';
}