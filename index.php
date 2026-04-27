<?php

session_start();

$error= [
    'login' => $_SESSION['login_error'] ?? '',
    'register' =>$_SESSION['register_error'] ?? ''

];
 
$activeForm=$_SESSION['active_form']?? 'login';
session_unset();

function showError($error){
    return !empty($error) ? "<p class='error-message'>$error</p>" : '';
}
 function isActiveForm($formName,$activeForm){
    return $formName === $activeForm ? 'active' : '';
 }









?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="form-box  <?= isActiveForm('login',$activeForm); ?>" id="login-form">
            <form action="login_register.php" method="post">
                <h2>Login</h2>
                <?= showError($error['login']); ?>
                <input type="email" name="email" placeholder="email" required>
                <input type="password" name="password" placeholder="password" required>
                <button type="submit" name="login">Login</button>
                <p>Don't have a account <a href="#" onclick="showForm('Register-form')">Register</a></p>
            </form>
        </div>
       <div class="form-box <?= isActiveForm('register',$activeForm); ?>" id="Register-form">
            <form action="login_register.php" method="post">
                <h2>Register</h2>
                <?= showError($error['register']); ?>
                 <input type="text" name="name" placeholder="name" required>
                <input type="email" name="email" placeholder="email" required>
                <input type="password" name="password" placeholder="password" required>
                <select name="role" required>
                    <option value="">---select role---</option>
                    <option value="User">User</option>
                    <option value="Admin">Admin</option>
    
                </select>
                <button type="submit" name="register">Register</button>
                <p>Already Don't have a account <a href="#" onclick="showForm('login-form')">Login</a></p>
            </form>
        </div>




    </div>

  <script src="script.js"></script>





</body>
</html>