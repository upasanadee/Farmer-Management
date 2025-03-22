<?php
session_start();
$conn = new mysqli("localhost", "root", "", "farmer_management");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $role = $_POST["role"];

    if (isset($_POST["register"])) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $hashed_password, $role);
        if ($stmt->execute()) {
            echo "Registration successful! Please login.";
        } else {
            echo "User already exists.";
        }
    } elseif (isset($_POST["login"])) {
        $stmt = $conn->prepare("SELECT password, role FROM users WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($hashed_password, $db_role);
            $stmt->fetch();
            if (password_verify($password, $hashed_password)) {
                $_SESSION["username"] = $username;
                $_SESSION["role"] = $db_role;
                header("Location: dashboard.php");
                exit();
            } else {
                echo "Invalid password.";
            }
        } else {
            echo "User not found.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login / Register</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Farmer Management System</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <select name="role">
                <option value="farmer">Farmer</option>
                <option value="customer">Customer</option>
            </select>
            <button type="submit" name="login">Login</button>
            <button type="submit" name="register">Register</button>
        </form>
    </div>
</body>
</html>
