<?php
class User
{

    // Register new user to the db 
    public static function register($name, $email, $phone, $gender, $password, $conn)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (name, email, phone, gender, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $email, $phone, $gender, $hashedPassword]);
        return true;
    }

    // Check if email or name exists
    public static function checkUserExists($email, $conn)
    {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? OR name = ?");
        $stmt->execute([$email, $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    // Verify password
    public static function verifyPassword($password, $hashedPassword)
    {
        return password_verify($password, $hashedPassword);
    }

    // Login a user
    public static function login($email, $password, $conn)
    {
        $user = self::checkUserExists($email, $conn);
        if ($user && self::verifyPassword($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_phone'] = $user['phone'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_gender'] = $user['gender'];
            return true;
        }
        return false;
    }

// to update/reset the password's token
    public static function updateResetToken($email, $token, $pdo)
    {
        $query = "UPDATE users SET reset_token = :token WHERE email = :email";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR); // Bind the email
        $stmt->bindValue(':token', $token, PDO::PARAM_STR); // Bind the token
        $stmt->execute();
    }

    // reset/update password
    public static function resetPassword($token, $newPassword, $pdo)
    {
        $query = "SELECT email FROM users WHERE reset_token = :token";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':token', $token, PDO::PARAM_STR); // Bind the token
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch the result

        if ($result) {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $updateQuery = "UPDATE users SET password = :password, reset_token = NULL WHERE reset_token = :token";
            $stmt = $pdo->prepare($updateQuery);
            $stmt->bindValue(':password', $hashedPassword, PDO::PARAM_STR); // Bind the hashed password
            $stmt->bindValue(':token', $token, PDO::PARAM_STR); // Bind the token
            $stmt->execute();
            return true;
        }
        return false;
    }
}
