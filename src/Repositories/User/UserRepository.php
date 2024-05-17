<?php

declare(strict_types=1);

namespace Masoretic\Repositories\User;

use Masoretic\DBAL\Database;

class UserRepository implements UserRepositoryInterface
{
    protected Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function create(array $user): int
    {
        try {
            return $this->database->getQueryBuilder()
            ->insert('users')
            ->values(
                [
                    'name' => ':name',
                    'email' => ':email',
                    'password' => ':password',
                    'activation_hash' => ':activation_hash',
                    'active' => ':active',
                    'deleted' => ':deleted',
                ]
            )
            ->setParameter('name', $user['name'])
            ->setParameter('email', $user['email'])
            ->setParameter('password', $user['password'])
            ->setParameter('activation_hash', $user['activationHash'])
            ->setParameter('active', 0)
            ->setParameter('deleted', 0)
            ->executeStatement();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function load(string $userId): array
    {
        $result = $this->database->getQueryBuilder()
            ->select('user_id, name, email', 'password, active')
            ->from('users')
            ->where('deleted = :deleted')
            ->andWhere('user_id = :user_id')
            ->setParameter('deleted', 0)
            ->setParameter('user_id', $userId)
            ->fetchAssociative();

        if ($result === false) {
            return [];
        }

        return $result;
    }

    public function loadByEmail(string $email): array
    {
        $result = $this->database->getQueryBuilder()
            ->select('user_id, name, email, password, active')
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

    public function loadByActivationHash(string $activationHash): array
    {
        $result = $this->database->getQueryBuilder()
            ->select('user_id, name, email, password, active')
            ->from('users')
            ->where('deleted = :deleted')
            ->andWhere('activation_hash = :activationHash')
            ->setParameter('deleted', 0)
            ->setParameter('activationHash', $activationHash)
            ->fetchAllAssociative();

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
            ->where('deleted = :deleted')
            ->setParameter('deleted', 0)
            ->fetchAllAssociative();
    }

    public function update(array $user): array
    {
        $this->database->getQueryBuilder()
            ->update('users')
            ->set('name', ':name')
            ->set('email', ':email')
            ->set('password', ':password')
            ->set('active', ':active')
            ->where('deleted = :deleted')
            ->andWhere('user_id = :user_id')
            ->setParameter('name', $user['name'])
            ->setParameter('email', $user['email'])
            ->setParameter('password', $user['password'])
            ->setParameter('active', $user['active'])
            ->setParameter('deleted', 0)
            ->setParameter('user_id', $user['userId'])
            ->executeStatement();

        return $this->load($user['userId']);
    }

    public function delete(string $userId): int
    {
        return $this->database->getQueryBuilder()
            ->update('users')
            ->set('deleted', ':deleted')
            ->where('user_id = :user_id')
            ->setParameter('deleted', 1)
            ->setParameter('user_id', $userId)
            ->executeStatement();
    }
}
