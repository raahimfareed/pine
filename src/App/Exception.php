<?php

namespace Pine\App;

class Exception extends \Exception {
    protected ?string $viewFile;
    protected int $statusCode;
    public function __construct(string $view, string $message, int $status, \Throwable $previous = null)
    {
        $this->viewFile = $view;
        $this->statusCode = $status;
        parent::__construct($message, $status, $previous);
    }

    public function getViewFile(): ?string
    {
        return $this->viewFile;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}

