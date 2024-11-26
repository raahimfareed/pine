<?php

namespace Pine\App;

class Response {
    private $is_json = false;

    public $body;
    public $status;
    public $_headers = [];

    public function __construct($status = 200, $body = []) {
        $this->status = $status;
        $this->body = $body;
    }

    public function headers(?array $to_append): array
    {
        if ($to_append === null) {
            return $this->_headers;
        }

        $type = gettype($to_append);
        if ($type === 'array') {
            $this->_headers = array_merge($this->_headers, $to_append);
        }

        return $this->_headers;
    }

    public function json() {
        if (!$this->is_json) {
            $this->is_json = true;
            $this->body = json_encode($this->body);
        }
        return $this;
    }

    public function isJson() {
        return $this->is_json;
    }
}
