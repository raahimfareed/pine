<?php

namespace Pine\App;

class LeafTemplateEngine {
    private $data = [];

    public function render($templatePath) {
        ob_start();

        include $templatePath;

        $content = ob_get_clean();
        $content = $this->parseTemplate($content);
        echo $content;
    }

    public function setData($data) {
        $this->data = $data;
    }

    public function parseTemplate($content) {
        return $content;
    }
}
