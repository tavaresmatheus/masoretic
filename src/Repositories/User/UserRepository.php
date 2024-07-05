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

    /**
     * @param array<string, string> $user
     * @return int
     */
    public function create(array $user): int
    {
        try {
            return (int) $this->database->getQueryBuilder()
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

    /**
     * @param int $userId
     * @return array<string, mixed>
     */
    public function load(int $userId): array
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

    /**
     * @param string $email
     * @return array<string, mixed>
     */
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

    /**
     * @param string $activationHash
     * @return array<string, mixed>
     */
    public function loadByActivationHash(string $activationHash): array
    {
        $result = $this->database->getQueryBuilder()
            ->select('user_id, name, email, password, active')
            ->from('users')
            ->where('deleted = :deleted')
            ->andWhere('activation_hash = :activationHash')
            ->setParameter('deleted', 0)
            ->setParameter('activationHash', $activationHash)
            ->fetchAssociative();

        if ($result === false) {
            return [];
        }

        return $result;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function list(): array
    {
        return $this->database->getQueryBuilder()
            ->select('user_id, name, email')
            ->from('users')
            ->where('deleted = :deleted')
            ->setParameter('deleted', 0)
            ->fetchAllAssociative();
    }

    /**
     * @param array<string, string|int> $user
     * @return array<string, mixed>
     */
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

        return $this->load((int) $user['userId']);
    }

    /**
     * @param int $userId
     * @return int
     */
    public function delete(int $userId): int
    {
        return (int) $this->database->getQueryBuilder()
            ->update('users')
            ->set('deleted', ':deleted')
            ->where('user_id = :user_id')
            ->setParameter('deleted', 1)
            ->setParameter('user_id', $userId)
            ->executeStatement();
    }
}
