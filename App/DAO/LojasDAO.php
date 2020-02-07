<?php
namespace App\DAO;

use App\Models\LojaModel;

class LojasDAO extends Connection {
    public function __construct() {
        parent::__construct();
    }

    public function getAllLojas(): array {
        $sql = "SELECT *
            FROM lojas";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        $lojas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return $lojas;
    }

    public function setLoja(LojaModel $loja): void {
        $sql = "INSERT INTO lojas
            VALUES
            (null, :name, :phone, :address)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue('name', $loja->getName());
        $stmt->bindValue('phone', $loja->getPhone());
        $stmt->bindValue('address', $loja->getAddress());
        $stmt->execute();
    }

    public function updateLoja(int $id, LojaModel $loja): bool {
        $sql = "UPDATE lojas
            SET
                name = :name,
                phone = :phone,
                address = :address
            WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue('id', $id);
        $stmt->bindValue('name', $loja->getName());
        $stmt->bindValue('phone', $loja->getPhone());
        $stmt->bindValue('address', $loja->getAddress());
        $stmt->execute();

        if ($stmt->rowCount())
            return true;

        return false;
    }

    public function deleteLoja(int $id): bool {
        $sql = "DELETE FROM lojas
            WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue('id', $id);
        $stmt->execute();

        if ($stmt->rowCount())
            return true;

        return false;
    }

}
