<?php
namespace App\DAO;

use App\Models\UserModel;

class UsersDAO extends Connection {
    public function __construct() {
        parent::__construct();
    }

    public function getUserByEmail(string $email): ?UserModel { // accepted UserModel ou null value return
        $sql = "SELECT id, name, email, password
            FROM users
            WHERE email = :email";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue('email', $email);
        $stmt->execute();

        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$user) return null;

        $userModel = new UserModel();
        $userModel->setId($user['id']);
        $userModel->setName($user['name']);
        $userModel->setEmail($user['email']);
        $userModel->setPassword($user['password']);

        return $userModel;
    }

}
