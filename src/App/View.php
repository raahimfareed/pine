<?php

namespace Pine\App;

class View {
    public function __construct(string $path, array $data = [])
    {
        $newPath = $path . ".leaf.html";

        $engine = new LeafTemplateEngine();

        $engine->render(__DIR__ . "/../views/" . $newPath, $data);
        // $lexer = new LeafTemplateLexer(file_get_contents(__DIR__ . "/../views/" . $newPath));
        // dump($lexer->tokenize());
    }
}
