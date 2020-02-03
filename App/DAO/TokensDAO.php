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

    public function verifyRefreshToken(string $refreshToken): bool {
        $sql = "SELECT id
            FROM tokens
            WHERE refresh_token = :refresh_token
            AND active = 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue('refresh_token', $refreshToken);
        $stmt->execute();
        $token = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $token ? true : false;
    }

    public function updateTokenStatus(string $refreshToken): void {
        $sql = "UPDATE tokens
            SET
                active = 0
            WHERE refresh_token = :refresh_token";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue('refresh_token', $refreshToken);
        $stmt->execute();
    }
}
