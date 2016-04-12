<?php 

namespace DaveRoss\RestServer;

class RestRequest {

    public $method;
    public $path;
    public $protocol;
    public $headers;
    public $payload;

    const FORMAT_TEXT = 0;
    const FORMAT_JSON = 1;

    function __construct($data) {
        list($this->method, $this->path, $this->protocol) = explode(' ', array_first($this->parsePreamble($data)), 3);
        $this->headers = $this->splitHeaders(array_slice($this->parsePreamble($data), 1));
        $this->payload = $this->parsePayload($data);
    }

    public function header($name) {
        return isset($this->headers[$name]) ? $this->headers[$name] : null;
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

    /**
     * @param string $input
     */
    private function parsePreamble($input) {
        return array_filter(explode("\n", array_first(explode("\n\n", $input))));
    }

    /**
     * @param string $input
     */
    private function parsePayload($input) {
        return array_last(array_pad(explode("\n\n", $input, 2), 2, ''));
    }

    private function splitHeaders(array $raw_headers) {
        $headers = array();
        foreach($raw_headers as $raw_header) {
            if (!empty(trim($raw_header))) {
                list($key, $value) = explode(':', trim($raw_header));
                if (trim($key.$value)) {
                    $headers[$key] = trim($value);
                }
            }
        }
        return $headers;
    }
}
