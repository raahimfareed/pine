<?php

namespace Pine\App;

class Request {
    public array $body = [];
    public array $headers = [];

    public function __construct()
    {
        $this->headers = getallheaders();
        $form_body = $_REQUEST;
        $entityBody = file_get_contents('php://input');
        $json_body = json_decode($entityBody, true);

        $final_array = [...($form_body ?? []), ...($json_body ?? [])];
        $this->body = $final_array;
    }
}
