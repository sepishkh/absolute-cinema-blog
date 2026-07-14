<?php

namespace AbsCin\Views;

class View {
    public function __construct(
        private string $template,
        private array $vars = [],
    ) {}

    // TODO: Function to return {{$key}} format

    public function Render(): string {
        $template_path = ROOT_PATH . "/views/$this->template.php";
        extract($this->vars);
        ob_start();
        require $template_path;
        $content = ob_get_clean();
        return $content;
    }
}
