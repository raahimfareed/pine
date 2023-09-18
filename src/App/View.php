<?php

namespace Pine\App;

class View {
    public function __construct(string $path)
    {
        $newPath = $path . ".leaf.html";

        $engine = new LeafTemplateEngine();

        $engine->render(__DIR__ . "/../views/" . $newPath);
    }
}
