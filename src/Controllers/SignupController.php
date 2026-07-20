<?php

namespace AbsCin\Controllers;

use AbsCin\Http\Response;
use AbsCin\Models\UsersModel;
use AbsCin\Views\View;
use AbsCin\Validation;

class SignupController extends BaseController {
    private UsersModel $um;
    private Validation $validation;

    protected function Init() {
        $this->um = new UsersModel($this->dbc);
        $rules = [
            "email" => [Validation::RULE_REQUIRED => [], Validation::RULE_EMAIL => []],
            "password" => [Validation::RULE_REQUIRED => []],
        ];
        $this->validation = new Validation($this->rules);
    }

    public function SignupPage(): Response {
        $errors = $this->validation->Decode($this->request->Get());
        foreach($errors as $error) {

        }
        $status = $this->request->GetKey("status");
        $alert_info = match($status) {
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
                                "result" => $alert_info[0],
                                "icon" => $alert_info[1],
                                "status" => $alert_info[2],
                                "message" => $alert_info[3],
                            ]
                        ))->Render(),
                    ]
                ))->Render(),
            ]
        );
        $content = $view->Render();
        return new Response($content);
    }

    public function Signup() {

    }
}
