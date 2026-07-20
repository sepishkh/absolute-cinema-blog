<?php

namespace AbsCin;

class Validation {
    public const RULE_REQUIRED  = 1;
    public const RULE_EMAIL     = 2; 
    public const RULE_MIN       = 4;
    public const RULE_MAX       = 8;
    public const RULE_MATCH     = 16;

    public const ERROR = [
        self::RULE_REQUIRED => "{?} field is required",
        self::RULE_EMAIL => "{?} field is not a valid Email",
        self::RULE_MIN => "Length of {?} field must be greater than {min}",
        self::RULE_MAX => "Length of {?} field must be less than {max}",
        self::RULE_MATCH => "Repeated password doesn't match",
    ];

    public function __construct(private array $rules) {}

    public function Encode(array $errors): int {
        $res = 0;
        foreach($errors as $error) {
            $res = $res | $error;
        }
        return $res;
    }

    public function Decode(array $errors): array {
        $res = [];
        foreach($errors as $field => $code) {
            foreach(self::ERROR as $err => $msg) {
                if(($code & $err) != $err) continue;
                $msg = str_replace('{?}', $field, $msg);
                foreach($this->rules[$field][$err] as $find => $replace) {
                    $msg = str_replace('{'.$find.'}', $replace, $msg);
                }
                array_push($res, $msg);
            }
        }
        return $res;
    }

    public function Validate(array $vars): array {
        $errors = [];
        foreach($this->rules as $key => $rules) {
            $field_errors = [];
            $value = $vars[$key];
            foreach($rules as $rule => $params) {
                /* echo "<br>"; */
                /* var_dump($rule, $params); */
                /* echo "<br>"; */
                if($rule == self::RULE_REQUIRED && !$value) {
                    array_push($field_errors , self::RULE_REQUIRED);
                }
                if($rule == self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    array_push($field_errors , self::RULE_EMAIL);
                }
                if($rule == self::RULE_MIN && strlen($value) < $rule[1]) {
                    array_push($field_errors , self::RULE_MIN);
                }
                if($rule == self::RULE_MAX && strlen($value) > $rule[1]) {
                    array_push($field_errors , self::RULE_MAX);
                }
            }
            if(count($field_errors) != 0) {
                $errors[$key] = $this->Encode($field_errors);
            }
        }
        return $errors;
    }
}
