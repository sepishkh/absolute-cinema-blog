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
        ob_start();
        require $template_path;
        $content = ob_get_clean();
        /* echo "<br>"; */
        /* echo $template_path; */
        /* echo "<br>"; */
        /* echo htmlspecialchars($content, ENT_HTML5, "UTF-8"); */
        /* echo "<br>"; */
        foreach($this->vars as $key => $val) {
            $content = str_replace('{{' . $key . '}}', $val, $content);
        }
        return $content;
    }
}
