<?php

namespace Pine\App;

class Request {
    public array $body;
    public array $params = [];
    public array $headers;

    public string $uri;
    public string $method;


    public function __construct(?array $params)
    {
        if (!!$params) {
            $this->params = $params;
        }
        $this->uri = $_SERVER['REQUEST_URI'];
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->headers = getallheaders();
        $form_body = $_REQUEST;
        $entityBody = file_get_contents('php://input');
        $json_body = json_decode($entityBody, true);

        $final_array = [...($form_body ?? []), ...($json_body ?? [])];
        $this->body = $final_array;
    }
}
