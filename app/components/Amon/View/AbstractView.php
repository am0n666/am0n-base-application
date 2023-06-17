<?php

namespace Amon\View;

use Bitty\Http\Response;
use Amon\View\ViewInterface;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractView implements ViewInterface
{
    abstract public function render(string $template, $data = []): string;

    public function renderResponse(string $template, $data = []): ResponseInterface
    {
        return new Response($this->render($template, $data));
    }
}
