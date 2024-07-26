<?php

namespace uzziah;

use PDO;

class Registration
{
    // DB stuff
    private $con;
    private $table = 'registration';

    // Registration properties 
    public $name;
    public $email;
    public $passw;
    public $id;
    private $hash;

    public $token;
    public $reset_time;
    public $createAt;

    public function __construct($db)
    {
        $this->con = $db;
    }

    public function login(string $email, string $password)
    {
        $this->email = $email;
        $this->passw = $password;
        $this->id;

        $pv = "SELECT password, name , id FROM registration WHERE email = :email";
        $stmt1 = $this->con->prepare($pv);
        $stmt1->bindParam(':email', $email);
        $stmt1->execute();
        $result = $stmt1->fetch(PDO::FETCH_ASSOC);

        if ($result !== false) {
            $storedPassword = $result['password'];
            $verif = password_verify($this->passw, $storedPassword);

            if ($verif === true) {
                $this->name = $result['name'];
                $this->id = $result['id'];
                return true;
            } else {
                echo "Something went wrong";
            }
        }
    }

    // Create new user
    public function create()
    {

        if ($this->emailExists($this->email)) {
            return ['success' => false, 'message' => 'Email already in use'];
        }
        // Create query
        $query = 'INSERT INTO ' . $this->table . '
             SET
                id=:id,
                name=:name,
                email=:email,
                password=:password';

        // Prepare statement 
        $stmt = $this->con->prepare($query);

        // Clean data
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->passw = htmlspecialchars(strip_tags($this->passw));

        // Bind data
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->hash);

        if ($this->validatePassword($this->passw)) {
            $this->hash = password_hash($this->passw, PASSWORD_DEFAULT);

            // Execute query 
            if ($stmt->execute()) {
                return ['success' => true];
            } else {
                return ['success' => false, 'message' => 'User not created. Error: ' . $stmt->error];
            }
        } else {
            echo "something went wrong";
            die();
        }
    }

    // Check if email already exists
    private function emailExists($email)
    {
        $query = 'SELECT COUNT(*) as count FROM ' . $this->table . ' WHERE email = :email';
        $stmt = $this->con->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['count'] > 0;
    }

    // Get posts
    public function read()
    {
        // Create query
        $query = 'SELECT id,name,email,created_at
        FROM
        ' . $this->table . '
        ORDER BY
       created_at DESC';

        // Prepare statement
        $stmt = $this->con->prepare($query);

        // Execute query
        $stmt->execute();

        return $stmt;
    }

    // Get a single post
    public function read_single()
    {
        // Create query
        $query = 'SELECT 
        id,name,email,created_at,password
        FROM
        ' . $this->table . '
        WHERE
        name=?
        LIMIT 0,1';

        // Prepare statement
        $stmt = $this->con->prepare($query);

        // Bind ID
        $stmt->bindParam(1, $this->name);

        // Execute query
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Set properties
        $this->id = $row['id'];
        $this->name = $row['name'];
        $this->email = $row['email'];
        $this->createAt = $row['created_at'];
        $this->passw = $row['password'];
    }

    // Delete 
    public function delete()
    {
        // Create query 
        $query = 'DELETE FROM ' . $this->table . '
        WHERE 
        id=:id';

        // Prepare statement
        $stmt = $this->con->prepare($query);
        // Clean data
        $this->id = htmlspecialchars(strip_tags($this->id));
        // Bind
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        } else {
            echo 'Error : ' . $stmt->error;
        }
    }

    // Update
    public function update()
    {
        // Create query
        $query = 'UPDATE ' . $this->table . '
        SET
        name=:name,
            email=:email
        WHERE 
            id=:id';

        // Prepare statement 
        $stmt = $this->con->prepare($query);

        // Clean data
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind data
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':id', $this->id);

        // Execute query 
        if ($stmt->execute()) {
            return true;
        } else {
            echo 'Error : ' . $stmt->error;
        }
    }

    private function validatePassword(string $password)
    {
        // Check if the password has at least 8 characters
        if (strlen($password) < 8) {
            echo "Please enter a password with at least 8 characters.";
            return false;
        }

        // Check if the password contains at least one uppercase letter
        if (!preg_match('/[A-Z]/', $password)) {
            echo "Please enter a password with at least one uppercase letter.";
            return false;
        }

        // Check if the password contains at least one digit or special character
        if (!preg_match('/[0-9\W]/', $password)) {
            echo "Please enter a password with at least one digit or special character.";
            return false;
        }

        // All conditions are satisfied
        return true;
    }

    public function resetpassword()
    {
        $sql = "UPDATE user
        SET token_reset = ?,
            reset_time = ?
        WHERE email = ?";

        $stmt = $this->con->prepare($sql);

        $stmt->bind_param("sss", $this->token, $this->reset_time, $this->email);

        $stmt->execute();

        if ($stmt->rowcount > 0) {
            echo "Password reset successfully.";
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->email = $row['email'];
            $this->token = $row['reset_token'];
            $this->reset_time = $row['reset_time'];
        } else {
            echo "Error: Unable to reset password.";
        }

        // Close the statement
        $stmt->close();
    }

    public function setResetToken($email, $token_hash, $expiry)
    {
        $sql = "UPDATE {$this->table}
                SET reset_token_hash = :token_hash,
                    reset_token_expires_at = :reset_time
                WHERE email = :email";

        $stmt = $this->con->prepare($sql);
        $stmt->bindParam(':token_hash', $token_hash);
        $stmt->bindParam(':reset_time', $expiry);
        $stmt->bindParam(':email', $email);

        return $stmt->execute();
    }

    public function findByResetToken($token_hash)
    {
        $sql = "SELECT * FROM {$this->table}
                WHERE reset_token_hash = :token_hash";

        $stmt = $this->con->prepare($sql);
        $stmt->bindParam(':token_hash', $token_hash);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updatePassword($userId, $password_hash)
    {
        $sql = "UPDATE {$this->table}
                SET password_hash = :password_hash,
                    reset_token_hash = NULL,
                    reset_token_expires_at = NULL
                WHERE id = :id";

        $stmt = $this->con->prepare($sql);
        $stmt->bindParam(':password_hash', $password_hash);
        $stmt->bindParam(':id', $userId);

        return $stmt->execute();
    }
}
