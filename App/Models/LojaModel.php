<?php
namespace App\Models;

class LojaModel {
    /**
     * @var int
     */
    private $id;
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $phone;
    /**
     * @var string
     */
    private $address;

    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }
    /**
     * @param string $value
     * @return LojaModel
     */
    public function setName(string $value): LojaModel {
        $this->name = $value;
        return $this;
    }

    public function getPhone(): string {
        return $this->phone;
    }
    /**
     * @param string $value
     * @return LojaModel
     */
    public function setPhone(string $value): LojaModel {
        $this->phone = $value;
        return $this;
    }

    public function getAddress(): string {
        return $this->address;
    }
    /**
     * @param string $value
     * @return LojaModel
     */
    public function setAddress(string $value): LojaModel {
        $this->address = $value;
        return $this;
    }
}
