<?php

namespace AbsCin\Controllers;

use AbsCin\Http\Response;
use AbsCin\Models\UsersModel;
use AbsCin\Views\View;

class AuthController extends BaseController {
    private UsersModel $um;

    protected function Init() {
        $this->um = new UsersModel($this->dbc);
    }

    public function Login(): Response {
        /* $status = $this->request->GetKey("status"); */
        /* $box_info = match($status) { */
        /*     "success" => ["success", "✔", "Success!", "Your account has been created. Go to <a href='/login'>Login</a> page."], */
        /*     "23000" => ["danger", "⚠", "Registration Failed", "Email Already registered"], */
        /*     default => ["danger", "⚠", "Registration Failed", "Error"], */
        /* }; */
        /* <?php if (isset($_GET["status"])) : ?> */
        $view = new View(
            "layouts/main",
            [
                "title" => "Login - AbsoluteCinema",
                "content" => (new View(
                    "auth/login",
                    [
                        "status" => $this->request->GetKey("status"),
                    ]
                ))->Render(),
            ]
        );
        $content = $view->Render();
        return new Response($content);
    }

    public function Signup(): Response {
        $status = $this->request->GetKey("status");
        $box_info = match($status) {
            "success" => ["success", "✔", "Success!", "Your account has been created. Go to <a href='/login'>Login</a> page."],
            "23000" => ["danger", "⚠", "Registration Failed", "Email Already registered"],
            default => ["danger", "⚠", "Registration Failed", "Error"],
        };
        $view = new View(
            "layouts/main",
            [
                "title" => "Signup - AbsoluteCinema",
                "content" => (new View(
                    "auth/signup",
                    [
                        "alert_box" => ($status == null) ? "" : (new View(
                            "partials/alert-box",
                            [
                                "result" => $box_info[0],
                                "icon" => $box_info[1],
                                "status" => $box_info[2],
                                "message" => $box_info[3],
                            ]
                        ))->Render(),
                    ]
                ))->Render(),
            ]
        );
        $content = $view->Render();
        return new Response($content);
    }
}
