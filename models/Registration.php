<?php

namespace uzziah;

use PDO;

class Registration
{
    private $con;
    private $table = 'registration';

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

        $query = "SELECT password, name, id FROM {$this->table} WHERE email = :email";
        $stmt = $this->con->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result !== false) {
            $storedPassword = $result['password'];
            if (password_verify($this->passw, $storedPassword)) {
                $this->name = $result['name'];
                $this->id = $result['id'];
                return true;
            } else {
                echo "Something went wrong";
            }
        }
        return false;
    }

    public function create()
    {
        if ($this->emailExists($this->email)) {
            return ['success' => false, 'message' => 'Email already in use'];
        }

        $query = "INSERT INTO {$this->table}
                  SET id = :id, name = :name, email = :email, password = :password";

        $stmt = $this->con->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->passw = htmlspecialchars(strip_tags($this->passw));

        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);

        if ($this->validatePassword($this->passw)) {
            $this->hash = password_hash($this->passw, PASSWORD_DEFAULT);
            $stmt->bindParam(':password', $this->hash);

            if ($stmt->execute()) {
                return ['success' => true];
            } else {
                return ['success' => false, 'message' => 'User not created. Error: ' . $stmt->errorInfo()[2]];
            }
        } else {
            echo "Something went wrong";
            die();
        }
    }

    private function emailExists($email)
    {
        $query = "SELECT COUNT(*) as count FROM {$this->table} WHERE email = :email";
        $stmt = $this->con->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['count'] > 0;
    }

    public function read()
    {
        $query = "SELECT id, name, email, created_at FROM {$this->table} ORDER BY created_at DESC";
        $stmt = $this->con->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    public function read_single()
    {
        $query = "SELECT id, name, email, created_at, password FROM {$this->table} WHERE name = ? LIMIT 0,1";
        $stmt = $this->con->prepare($query);
        $stmt->bindParam(1, $this->name);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);


        $this->id = $row['id'];
        $this->name = $row['name'];
        $this->email = $row['email'];
        $this->createAt = $row['created_at'];
        $this->passw = $row['password'];
    }

    public function delete()
    {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->con->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }

    public function update()
    {
        $query = "UPDATE {$this->table}
                  SET name = :name, email = :email
                  WHERE id = :id";

        $stmt = $this->con->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }

    private function validatePassword(string $password)
    {
        if (strlen($password) < 8) {
            echo "Please enter a password with at least 8 characters.";
            return false;
        }

        if (!preg_match('/[A-Z]/', $password)) {
            echo "Please enter a password with at least one uppercase letter.";
            return false;
        }

        if (!preg_match('/[0-9\W]/', $password)) {
            echo "Please enter a password with at least one digit or special character.";
            return false;
        }

        return true;
    }

    public function resetPassword()
    {
        $query = "UPDATE {$this->table}
                  SET token_reset = :token,
                    reset_time = :reset_time
                  WHERE email = :email";

        $stmt = $this->con->prepare($query);
        $stmt->bindParam(':token', $this->token);
        $stmt->bindParam(':reset_time', $this->reset_time);
        $stmt->bindParam(':email', $this->email);

        if ($stmt->execute() && $stmt->rowCount() > 0) {

            return true;
        }
        return false;
    }

    public function findByResetToken($token_hash)
    {
        $query = "SELECT * FROM {$this->table} WHERE token_reset = :token_hash";
        $stmt = $this->con->prepare($query);
        $stmt->bindParam(':token_hash', $token_hash);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

       

        $this->id = $row['id'];
        $this->name = $row['name'];
        $this->email = $row['email'];
        $this->createAt = $row['created_at'];
        $this->passw = $row['password'];
        $this->token = $row['token_reset'];
        $this->reset_time = $row['reset_time'];

        return true;
    }

    public function updatePassword($userId, $password_hash)
    {
        $query = "UPDATE {$this->table}
                  SET password = :password_hash,
                      token_reset = NULL,
                      reset_time = NULL
                  WHERE id = :id";

                $stmt = $this->con->prepare($query);

        if ($this->validatePassword($password_hash)) {
            $this->hash = password_hash($password_hash, PASSWORD_DEFAULT);
            $stmt->bindParam(':password_hash', $this->hash);
            $stmt->bindParam(':id', $userId);

            if ($stmt->execute()) {
                return ['success' => true];
            } else {
                return ['success' => false, 'message' => 'User not created. Error: ' . $stmt->errorInfo()[2]];
            }
        } else {
            echo "Something went wrong";
            die();
        }

    }
}