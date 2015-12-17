<?php
Class RuleException extends Exception {
	public function __construct($message, $code, Exception $previous = null) {
    
	        parent::__construct($message, $code, $previous);
	    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }	
	
	public function toJson() {
		return json_encode(array("status" => $this->code, "reason" => $this->message));
	}
}	
?>
