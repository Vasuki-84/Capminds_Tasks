<?php session_start();
 require 'includes/validation.php';

$u=$_POST['username']; 
$e=$_POST['email'];
$p=$_POST['password'];
$r=$_POST['remember']??'';

if(!validateUsername($u)||!validateEmail($e)||!validatePassword($p)){
$_SESSION['error']='Invalid input'; header('Location: login.php'); exit;
}

if($u==='admin'&&$e==='admin@example.com'&&$p==='Admin@123'){
$theme = ($u==='user1')?'dark':(($u==='user2')?'warm':'light');
$_SESSION=['username'=>$u,'email'=>$e,'theme'=>$theme];

if($r)
{ 
    setcookie('remember_username',$u,time()+60); setcookie('user_theme',$theme,time()+60); 
}
else setcookie('remember_username','',time()-3600);

header('Location: dashboard.php'); exit;
}
$_SESSION['error']='Invalid credentials'; header('Location: login.php');

?>
