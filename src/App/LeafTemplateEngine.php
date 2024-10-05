<?php

namespace Pine\App;

use Pine\Exceptions\ViewNotFound;

class LeafTemplateEngine {
    private $data = [];

    /**
     * @throws ViewNotFound
     */
    public function render($templatePath, array $data = []) {
        if (!file_exists($templatePath)) {
            throw new ViewNotFound($templatePath);
        }
        $this->data = $data;
        // $templateContent = file_get_contents($templatePath);
        //
        // $lexer = new LeafTemplateLexer($templateContent);
        // $tokens = $lexer->tokenize();
        //
        // $parser = new LeafTemplateParser($tokens);
        // $ast = $parser->parse();
        //
        // dump($ast);
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
        $variables = $this->extractTemplateVariables($content);
        $content = str_replace("@css", "<link rel=\"stylesheet\" type=\"text/css\" href=\"public/style.css\" />", $content);
        $content = str_replace("@js", "<script src=\"public/main.js\"></script>", $content);
        foreach ($variables as $var) {
            $content = str_replace("{{".$var."}}", $this->data[$var] ?? "", $content);
            $content = str_replace("{{ ".$var."}}", $this->data[$var] ?? "", $content);
            $content = str_replace("{{".$var." }}", $this->data[$var] ?? "", $content);
            $content = str_replace("{{ ".$var." }}", $this->data[$var] ?? "", $content);
        }
        return $content;
    }

    function extractTemplateVariables($templateString) {
        $variables = [];

        $pattern = '/{{\s*(\w+)\s*}}/';

        preg_match_all($pattern, $templateString, $matches);

        if (!empty($matches[1])) {
            $variables = array_unique($matches[1]);
        }

        return $variables;
    }
}
