<?php

namespace Framework\Controller;

class Controller
{
    protected $renderer;
    protected $params;

    public function __construct($renderer, $params)
    {
        $this->renderer = $renderer;
        $this->params = $params;
    }

    protected function render(array $data = [], string $template = null)
    {
        if (null === $template && isset($this->params['controller'])) {
            $template = sprintf('%s/%s', $this->params['controller'], $this->params['action'] ?? 'index');
        }
        
        return $this->renderer->render(
            'layout',
            array_merge([
                'layout_breadcrumbs' => [
                    $this->params['controller'],
                    $this->params['action'],
                ],
                'layout_title' => $this->params['controller'] ?? '',
                'layout_stylesheet' => '',
                'layout_script' => '',
                'layout_body' => $this->renderer->render($template, $data),
            ], $data)
        );
    }
}
