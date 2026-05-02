<?php
function validateUsername($u){
     return preg_match('/^[a-zA-Z0-9_]{3,20}$/',$u);
}
function validateEmail($e){ 
    return filter_var($e,FILTER_VALIDATE_EMAIL); 
}
function validatePassword($p){ 
    return preg_match('/^(?=.*[A-Z])(?=.*\d).{6,}$/',$p);
}
?>