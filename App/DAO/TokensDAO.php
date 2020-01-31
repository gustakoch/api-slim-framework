<?php
namespace App\DAO;

use App\Models\TokenModel;

class TokensDAO extends Connection {
    public function __construct() {
        parent::__construct();
    }

    public function saveToken(TokenModel $token): void {
        $sql = "INSERT INTO tokens
            (id_user, token, refresh_token, expired_at)
            VALUES
            (:id_user, :token, :refresh_token, :expired_at)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue('id_user', $token->getIdUser());
        $stmt->bindValue('token', $token->getToken());
        $stmt->bindValue('refresh_token', $token->getRefreshToken());
        $stmt->bindValue('expired_at', $token->getExpiredAt());
        $stmt->execute();
    }

}
