<?php

namespace Utils;

class Validator {
    public function validateUsername($username) { //  A parameter is the variable written in the function definition.
        return strlen($username) >= 3 ? "Valid" : "Invalid";
    }

    public function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) ? "Valid" : "Invalid";
    }

    public function validatePassword($password) {
        return strlen($password) >= 6 ? "Valid" : "Weak";
    }
}