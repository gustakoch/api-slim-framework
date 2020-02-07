<?php
namespace App\Models;

class TokenModel {
    /**
     * @var int
     */
    private $id;
    /**
     * @var int
     */
    private $id_user;
    /**
     * @var string
     */
    private $token;
    /**
     * @var string
     */
    private $refresh_token;
    /**
     * @var int
     */
    private $active;
    /**
     * @var string
     */
    private $expired_at;

    /**
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * @param int $id
     * @return self
     */
    public function setId(int $id): self {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getIdUser(): int
    {
        return $this->id_user;
    }

    /**
     * @param int $id_user
     * @return self
     */
    public function setIdUser(int $id_user): self {
        $this->id_user = $id_user;
        return $this;
    }

    /**
     * @return string
     */
    public function getToken(): string {
        return $this->token;
    }

    /**
     * @param string $token
     * @return self
     */
    public function setToken(string $token): self {
        $this->token = $token;
        return $this;
    }

    /**
     * @return int
     */
    public function getActive() {
        return $this->active;
    }

    /**
     * @param int $active
     * @return self
     */
    public function setActive(int $active): self {
        $this->active = $active;
        return $this;
    }

    /**
     * @return string
     */
    public function getExpiredAt(): string {
        return $this->expired_at;
    }

    /**
     * @param string $expired_at
     * @return self
     */
    public function setExpiredAt(string $expired_at): self {
        $this->expired_at = $expired_at;
        return $this;
    }
}
