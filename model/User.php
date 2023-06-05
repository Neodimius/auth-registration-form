<?php

namespace model;

use PDO;
use PDOException;

class User
{
    public array $rules = [
        'firstName' => [
            'max' => 30,
            'min' => 3,
            'type' => 'text',
        ],
        'lastName' => [
            'max' => 30,
            'min' => 3,
            'type' => 'text'
        ],

        'email' => [
            'max' => 30,
            'min' => 5,
            'type' => 'email',
            'unique' => true
        ],
        'phone' => [
            'max' => 13,
            'min' => 12,
            'type' => 'phone',
        ],
        'password' => [
            'max' => 30,
            'min' => 8,
            'type' => 'password',
        ],
    ];

    public array $errors = [];
    private PDO $conn;

    public function __construct() {
        // Database connection
        $servername = "localhost";
        $username = "homestead";
        $password = "secret";
        $dbname = "user_registration";

        try {
            $this->conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Failed to connect to MySQL: " . $e->getMessage());
        }
    }

    public function validate($data): bool
    {
        foreach ($data as $key => $value) {
            if ($key === 'csrfToken') {
                continue;
            }

            if (strlen($value) < $this->rules[$key]['min']) {
                $this->errors[$key][] = 'Minimum length ' . $this->rules[$key]['min'];
            }

            if (strlen($value) > $this->rules[$key]['max']) {
                $this->errors[$key][] = 'Max length ' . $this->rules[$key]['max'];
            }

            if (isset($this->rules[$key]['unique']) && $key === 'email' && $this->checkEmailExists($value)) {
                $this->errors[$key][] = 'Email exists';
            }
        }

        return count($this->errors) <= 0;
    }

    public function registerUser($firstName, $lastName, $email, $phone, $password): bool
    {
        $hashedPass = $this->hashPassword($password);

        try {
            $stmt = $this->conn->prepare("INSERT INTO users (first_name, last_name, email, phone, password) VALUES (:firstName, :lastName, :email, :phone, :password)");
            $stmt->bindParam(':firstName', $firstName);
            $stmt->bindParam(':lastName', $lastName);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':password', $hashedPass);

            if ($stmt->execute()) {
                return true;
            }

            return false;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * @param $email
     * @param $password
     * @return bool
     */
    public function authorizeUser($email, $password): bool
    {
        try {
            $stmt = $this->conn->prepare("SELECT id, password, token FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $storedPassword = $row['password'];

                if (password_verify($password, $storedPassword)) {
                    $token = $this->generateToken();

                    // Update the token in the database
                    $updateStmt = $this->conn->prepare("UPDATE users SET token = :newToken WHERE id = :userId");
                    $updateStmt->bindParam(':newToken', $token);
                    $updateStmt->bindParam(':userId', $row['id']);
                    $updateStmt->execute();

                    $_SESSION['token'] = $token;
                    $_SESSION['user_id'] = $row['id'];

                    return true;
                }
            }

            return false;
        } catch (PDOException|\Exception $e) {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function isAuthorized(): bool
    {
        if (isset($_SESSION['token'], $_SESSION['user_id'])) {
            $token = $_SESSION['token'];
            $userId = $_SESSION['user_id'];

            try {
                $stmt = $this->conn->prepare("SELECT COUNT(*) FROM users WHERE id = :userId AND token = :token");
                $stmt->bindParam(':userId', $userId);
                $stmt->bindParam(':token', $token);
                $stmt->execute();

                $count = $stmt->fetchColumn();

                return ($count > 0);
            } catch (PDOException $e) {
                return false;
            }
        }

        return false;
    }

    /**
     * @param $email
     * @return bool
     */
    public function checkEmailExists($email): bool
    {
        try {
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            $count = $stmt->fetchColumn();

            return ($count > 0);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * @return void
     */
    public function logout(): void
    {
        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];

            try {
                $stmt = $this->conn->prepare("UPDATE users SET token = NULL WHERE id = :userId");
                $stmt->bindParam(':userId', $userId);
                $stmt->execute();
            } catch (PDOException $e) {

            }
        }

        session_destroy();
    }

    /**
     * @param $password
     * @return string
     */
    private function hashPassword($password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * @throws \Exception
     */
    private function generateToken(): string
    {
        return bin2hex(random_bytes(32));
    }
}