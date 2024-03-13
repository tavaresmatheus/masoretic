<?php

declare(strict_types=1);

namespace Masoretic\Models;

use DateTime;

class User
{
    protected ?string $id;
    protected string $name;
    protected string $email;
    protected string $password;
    protected ?DateTime $createdAt;
    protected ?DateTime $updatedAt;
    protected ?bool $deleted;

    public function __construct(
        ?string $id,
        string $name,
        string $email,
        string $password,
        ?DateTime $createdAt,
        ?DateTime $updatedAt,
        ?bool $deleted
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->deleted = $deleted;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    public function getDeleted(): bool
    {
        return $this->deleted;
    }
}