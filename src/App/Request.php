<?php

namespace Pine\App;

class Request {
    private $__request_data = [];
    public function __construct()
    {
        $form_body = $_REQUEST;
        $entityBody = file_get_contents('php://input');
        $json_body = json_decode($entityBody, true);

        $final_array = [...($form_body ?? []), ...($json_body ?? [])];
        $this->__request_data = $final_array;

        foreach ($final_array as $key => $value) {
            $this->{$key} = $value;
        }
    }
}
