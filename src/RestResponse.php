<?php

namespace DaveRoss\RestServer;

class RestResponse {
    
    public $statusCode = '200';
    public $statusDescription = 'OK';
    public $headers = array();
    public $payload = '';
    
    const FORMAT_TEXT = 0;
    const FORMAT_JSON = 1;
    
    public function __toString() {
        return $this->statusCode . ' ' . $this->statusDescription . "\n" .
               implode("\n", array_map(function($val, $key) {
                   return "$key: $val\n";
               }, $this->headers, array_keys($this->headers))) .
               "\n\n" . $this->payload;
    }
    
    public function setStatusCode($code) {
        $this->statusCode = intval($code);
        $this->statusCodeDescription = self::statusCodeDescription($code);
    }
    
    private static function statusCodeDescription($code) {
        if(200 === intval($code)) { return 'OK'; }
        return '';
    }
    
    public function header($key, $value) {
        $this->headers[$key] = $value;
    }
    
    
    /**
     * @param string payload
     * @param int $format
     */
    public function setPayload($payload, $format = self::FORMAT_TEXT) {
        if (self::FORMAT_JSON === $format) {
            $this->payload = json_decode($payload);
        } else {
            // Default is text 
            $this->payload = $payload;
        }
    }
    
}