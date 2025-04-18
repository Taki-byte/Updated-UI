
<?php
session_start();
require_once 'db_connect.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];


    $sql = "SELECT user_id, username, password, membership_tier FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();


        if (password_verify($password, $user['password'])) {
           
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['membership_tier'] = $user['membership_tier'];

            
            $_SESSION['success'] = "Login successful!";
            header("Location: dashboard.php");
            exit();
        } else {
           
            $_SESSION['error'] = "Invalid password.";
            header("Location: login.php");
            exit();
        }
    } else {
     
        $_SESSION['error'] = "No account found with that email.";
        header("Location: login.php");
        exit();
    }

   
    $stmt->close();
    $conn->close();
} else {
    
    $_SESSION['error'] = "Invalid request.";
    header("Location: login.php");
    exit();
}
?>
