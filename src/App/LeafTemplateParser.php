<?php

namespace Pine\App;

class LeafTemplateParser {
    private $position = 0;
    private $ast = [];

    public function __construct(private $tokens)
    {}

    public function parse() {
        while ($this->position < count($this->tokens)) {
            $token = $this->tokens[$this->position];

            switch($token['type']) {
                case TokenPattern::Text->name:
                    $this->ast[] = [
                        'type' => 'text',
                        'content' => $token['value']
                    ];
                    break;
                case TokenPattern::Variable->name:
                    $this->ast[] = [
                        'type' => 'text',
                        'name' => $token['data']
                    ];
                    break;
                case TokenPattern::Directive->name:
                    $this->parseDirective($token);
                    break;
            }

            $this->position++;
        }
    }

    public function parseDirective($token) {
        switch($token['data']) {
            case "if":
                $condition = $token['params'];
                $this->ast[] = [
                    "type" => "if",
                    "condition" => $condition,
                    "children" => $this->parseUntil(["elseif", "else", "endif"])
                ];
                break;
            case "elseif":
                break;
            case "else":
                break;
            case "endif":
                break;
            case "foreach":
                break;
            case "endforeach":
                break;
            case "css":
                $this->ast[] = ["type" => "css"];
                break;
            case "js":
                $this->ast[] = ["type" => "js"];
                break;
        }
    }

    public function parseUntil($endDirectives) {
        // TODO: Make parse until for end directives
        $children = [];

        while ($this->position < count($this->tokens)) {
            $token = $this->tokens[$this->position];
            if ($token['type'] === TokenPattern::Directive->name && in_array($token['data'], $endDirectives)) {
                break;
            }

            switch ($token['type']) {
                case TokenPattern::Text->name:
                    $this->ast[] = [
                        'type' => 'text',
                        'content' => $token['value']
                    ];
                    break;
                case TokenPattern::Variable->name:
                    $this->ast[] = [
                        'type' => 'text',
                        'name' => $token['data']
                    ];
                    break;
                case TokenPattern::Directive->name:
                    $children[] = $this->parseDirective($token);
                    break;
            }

            $this->position++;
        }

        return $children;
    }
}
