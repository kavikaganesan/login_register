<?php
session_start();
include 'config.php';

/* ================= REGISTER ================= */
if (isset($_POST['register'])) {

    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';

    // Validate input
    if (empty($name) || empty($email) || empty($password) || empty($role)) {
        $_SESSION['register_error'] = "All fields are required";
        $_SESSION['active_form'] = 'register';
        header("Location: index.php");
        exit();
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check email using prepared statement
    $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $checkemail = $stmt->get_result();

    if ($checkemail->num_rows > 0) {
        $_SESSION['register_error'] = 'Email is already registered';
        $_SESSION['active_form'] = 'register';
    } else {
        // Insert user safely
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $hashedPassword, $role);

        if (!$stmt->execute()) {
            die("Insert Error: " . $stmt->error);
        }
    }

    header("Location: index.php");
    exit();
}

/* ================= LOGIN ================= */
if (isset($_POST['login'])) {

    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $_SESSION['login_error'] = "All fields are required";
        $_SESSION['active_form'] = 'login';
        header("Location: index.php");
        exit();
    }

    // Fetch user safely
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {

            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];

            if ($user['role'] === 'admin') {
                header("Location: admin_page.php");
            } else {
                header("Location: user_page.php");
            }
            exit();
        }
    }

    // Common error (wrong email OR password)
    $_SESSION['login_error'] = 'Incorrect email or password';
    $_SESSION['active_form'] = 'login';
    header("Location: index.php");
    exit();
}
?>