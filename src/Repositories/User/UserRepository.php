<?php

declare(strict_types=1);

namespace Masoretic\Repositories\User;

use Masoretic\DBAL\Database;
use Masoretic\Models\User;

class UserRepository implements UserRepositoryInterface
{
    protected Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function create(User $user): int
    {
        try {
            return $this->database->getQueryBuilder()
            ->insert('users')
            ->values(
                [
                    'name' => ':name',
                    'email' => ':email',
                    'password' => ':password',
                    'deleted' => ':deleted',
                ]
            )
            ->setParameter('name', $user->getName())
            ->setParameter('email', $user->getEmail())
            ->setParameter('password', $user->getPassword())
            ->setParameter('deleted', 0)
            ->executeStatement();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function load(string $userId): array
    {
        return $this->database->getQueryBuilder()
            ->select('user_id, name, email')
            ->from('users')
            ->where('deleted = ?')
            ->andWhere('user_id = ?')
            ->setParameter('deleted', 0)
            ->setParameter('user_id', $userId)
            ->fetchOne();
    }

    public function loadByEmail(string $email): array
    {
        $result = $this->database->getQueryBuilder()
            ->select('user_id, name, email')
            ->from('users')
            ->where('deleted = :deleted')
            ->andWhere('email = :email')
            ->setParameter('deleted', 0)
            ->setParameter('email', $email)
            ->fetchAssociative();

        if ($result === false) {
            return [];
        }

        return $result;
    }

    public function list(): array
    {
        return $this->database->getQueryBuilder()
            ->select('user_id, name, email')
            ->from('users')
            ->where('deleted = ?')
            ->setParameter('deleted', 0)
            ->fetchAllAssociative();
    }

    public function update(User $user): array
    {
        $this->database->getQueryBuilder()
            ->update('users')
            ->values(
                [
                    'name' => '?',
                    'email' => '?',
                    'password' => '?',
                    'updatedAt' => '?',
                ]
            )
            ->where('deleted = ?')
            ->andWhere('user_id = ?')
            ->setParameter('deleted', 0)
            ->setParameter('user_id', $user->getId())
            ->setParameter('name', $user->getName())
            ->setParameter('email', $user->getEmail())
            ->setParameter('password', $user->getPassword())
            ->executeStatement();

        return $this->load($user->getId());
    }

    public function delete(User $user): int
    {
        return $this->database->getQueryBuilder()
            ->update('users')
            ->setValue('deleted', '?')
            ->where('deleted = ?')
            ->andWhere('user_id = ?')
            ->setParameter('deleted', 0)
            ->setParameter('user_id', $user->getId())
            ->executeStatement();
    }
}