<?php

namespace Pine\App;

class Response {
    private $is_json = false;

    public $body;
    public $status;

    public function __construct($status = 200, $body = []) {
        $this->status = $status;
        $this->body = $body;
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
