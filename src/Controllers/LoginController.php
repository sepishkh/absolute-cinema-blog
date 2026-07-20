<?php

namespace AbsCin\Controllers;

use AbsCin\Http\Response;
use AbsCin\Models\UsersModel;
use AbsCin\Views\View;
use AbsCin\Validation;

class LoginController extends BaseController {
    private UsersModel $um;
    private Validation $validation;

    protected function Init() {
        $this->um = new UsersModel($this->dbc);
        $rules = [
            "email" => [Validation::RULE_REQUIRED => [], Validation::RULE_EMAIL => []],
            "password" => [Validation::RULE_REQUIRED => []],
        ];
        $this->validation = new Validation($rules);
    }

    public function LoginPage(): Response {
        /* $status = $this->request->GetKey("status"); */
        /* $box_info = match($status) { */
        /*     "success" => ["success", "✔", "Success!", "Your account has been created. Go to <a href='/login'>Login</a> page."], */
        /*     "23000" => ["danger", "⚠", "Registration Failed", "Email Already registered"], */
        /*     default => ["danger", "⚠", "Registration Failed", "Error"], */
        /* }; */
        /* <?php if (isset($_GET["status"])) : ?> */
        $errors = $this->validation->Decode($this->request->Get());
        $error_list = "";
        foreach($errors as $error) {
            $msg = new View("partials/error-msg", ["msg" => $error]);
            $error_list .= $msg->Render();
        }
        $alert_info = match($status) {
            "success" => ["success", "✔", "Success!", "Your account has been created. Go to <a href='/login'>Login</a> page."],
            "23000" => ["danger", "⚠", "Registration Failed", "Email Already registered"],
            default => ["danger", "⚠", "Registration Failed", $error_list],
        };
        $view = new View(
            "layouts/main",
            [
                "title" => "Login - AbsoluteCinema",
                "content" => (new View(
                    "auth/login",
                    [
                        "alert_box" => (new View(
                            "partials/alert-box",
                            [
                                "result" => $alert_info[0],
                                "icon" => $alert_info[1],
                                "status" => $alert_info[2],
                                "error_list" => $alert_info[3],
                            ]
                        ))->Render(),
                    ]
                ))->Render(),
            ]
        );
        $content = $view->Render();
        return new Response($content);
    }

    public function Login(): Response {
        $errors = $this->validation->Validate($this->request->Post());
        if (count($errors) == 0) {
            // TODO: Attempt Login
        }
        if (count($errors) == 0) {
            return new Response("", 302, ["Location" => "/home"]);
        } else {
            return new Response("", 302, [
                "Location" => "/login?" . http_build_query($errors)
            ]);
        }
    }
}
