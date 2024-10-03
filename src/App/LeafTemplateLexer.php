<?php

namespace Pine\App;

enum TokenPattern {
    case Variable;
    case Directive;
    case Text;
}

class LeafTemplateLexer {
    private array $tokens = [];
    private int $position = 0;

    public function __construct(private string $template) {
    }

    public function tokenize() {
        $patterns = [
            TokenPattern::Variable->name => "/{{\s*(\w+)\s*}}/",
            TokenPattern::Directive->name => "/@(\w+)\s*(?:\(([^)]+)\))?/",
            TokenPattern::Text->name => "/([^{@]+)/", // Looks buggy idk
        ];

        while ($this->position < strlen($this->template)) {
            foreach ($patterns as $type => $pattern) {
                if (preg_match(
                    $pattern, 
                    $this->template, 
                    $matches, 
                    PREG_OFFSET_CAPTURE, 
                    $this->position)
                ) {
                    if ($matches[0][1] !== $this->position) {
                        continue;
                    }

                    $this->tokens[] = [
                        "type" => $type,
                        "value" => $matches[0][0],
                        "data" => isset($matches[1]) ? $matches[1][0] : null,
                        "params" => isset($matches[2]) ? $matches[2][0]: null,
                    ];

                    $this->position += strlen($matches[0][0]);
                    continue 2;
                }
            }

            $this->tokens[] = [
                "type" => TokenPattern::Text->name,
                "value" => $this->template[$this->position],
                "data" => null,
                "params" => null
            ];

            $this->position++;
        }

        return $this->tokens;
    }
}
