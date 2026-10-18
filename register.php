<?php
require_once('config/db.php');

$message = '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if(!empty($username) && !empty($email) && !empty($password)){
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if($stmt->fetch()){
            $message = 'This email is already taken!';
        }else{
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->execute([$username]);
            if($stmt->fetch()){
                $message = 'This username is already taken!';
            }else{
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                if($stmt->execute([$username, $email, $hashedPassword])){
                    $message = 'Registration was successful! You can now log in.';
                }else{
                    $message = 'Error during registration.';
                }
            }
        }
    }else{
        $message = 'Fill in all the fields!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>$haNews | Registration</title>
</head>
<body>
    <form action="register.php" method="POST">
        <div>
            <label>Username:</label><br>
            <input type="text" name="username" required>
        </div>
        <div>
            <label>Email:</label><br>
            <input type="email" name="email" required>
        </div>
        <div>
            <label>Password:</label><br>
            <input type="password" name="password" required>
        </div>
        <br>
        <button type="submit">Register</button>
    </form>

    <?php if (!empty($message)): ?>
        <p><strong><?php echo $message; ?></strong></p>
    <?php endif; ?>
</body>
</html>