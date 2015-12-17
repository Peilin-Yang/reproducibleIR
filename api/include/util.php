<?php
class Util {
    
    const DATE_FORMAT = "Y-m-d H:i:s";
    
    public function __construct() {
    }

    // cleans $_POST like structure and returns same structure
    public function cleanPostData($post) {
        $result = array();

        foreach ($post as $key=>$value) {
            $result[$key] = htmlentities((isset($post[$key])) ? $post[$key] : null);
        }

        return $result;
    }

    public function getFromPost($post, $field) {
        return $this->getFromRequest($post, $field);
    }

    public function getFromGet($get, $field) {
        return $this->getFromRequest($get, $field);
    }

    private function getFromRequest($request, $field) {
        return htmlentities((isset($request[$field])) ? $request[$field] : null);
    }

    public function getFromPostGet($field) {
        return htmlentities((isset($_POST[$field])) ? $_POST[$field] : 
                ((isset($_GET[$field])) ? $_GET[$field] : null));
    }

    public function getMultipleFromRequest($request, $field, $skip_empty=false) {
        $result = array();
        if (isset($request[$field])) {
            for ($i=0; $i < count($request[$field]); $i++) {
                if ($skip_empty && empty($request[$field][$i])) {
                    continue;
                }
                $result[$i] = htmlentities($request[$field][$i]);
            }
        } 

        return $result;		
    }

    public function toJson($e) {
        if ($e instanceof RuleException) {
            return $e->toJson();
        }
        
        // TODO use some generic text instead of the actual exception message
        return json_encode(array('status' => '500', 'reason' => $e -> getMessage()));
    }
    
    /*
     * $s: date in "Y-m-d H:i:s"
     */
    public function strToUTCDate($string) {
        if (empty($string)) {
            return "";
        }
        
        $result = DateTime::createFromFormat(self::DATE_FORMAT, $string, new DateTimeZone("UTC"));
        
        return $result;
    }
    
    /*
     * return -1, 0, or 1 if $date1 is earlier than, equal, or later than $date2
     */
    public function compareStrDates($date1, $date2) {
        if (empty($date1) || empty($date2)) {
            throw new Exception("empty arguments in compareStrDates()");
        }
        
        $ObjectDate1 = $this->strToUTCDate($date1);
        $ObjectDate2 = $this->strToUTCDate($date2);
        
        if ($ObjectDate1 < $ObjectDate2) {
            return -1;
        }
        
        if ($ObjectDate1 == $ObjectDate2) {
            return 0;
        }
        
        return 1;
    }

    // in back end, we always use UTC time
    private function getSystemDateTimeUTC() {
        return gmdate(self::DATE_FORMAT);
    }

    public function now() {
        return $this->getSystemDateTimeUTC();
    }
    
    public function getUniqueId(){
        return uniqid("", false);
    }
}
	
$util = new Util();
?>