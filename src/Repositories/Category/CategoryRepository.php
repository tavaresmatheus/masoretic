<?php

declare(strict_types=1);

namespace Masoretic\Repositories\Category;

use Masoretic\DBAL\Database;

class CategoryRepository implements CategoryRepositoryInterface
{
    protected Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    /**
     * @param array<string, string> $category
     * @return int
     */
    public function create(array $category): int
    {
        try {
            return (int) $this->database->getQueryBuilder()
                ->insert('categories')
                ->values(
                    [
                        'name' => ':name',
                        'deleted' => ':deleted'
                    ]
                )
                ->setParameter('name', $category['name'])
                ->setParameter('deleted', 0)
                ->executeStatement();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @param int $categoryId
     * @return array<string, mixed>
     */
    public function load(int $categoryId): array
    {
        $result = $this->database->getQueryBuilder()
            ->select('category_id, name')
            ->from('categories')
            ->where('deleted = :deleted')
            ->andWhere('category_id = :category_id')
            ->setParameter('deleted', 0)
            ->setParameter('category_id', $categoryId)
            ->fetchAssociative();

        if ($result === false) {
            return [];
        }

        return $result;
    }

    /**
     * @param string $categoryName
     * @return array<string, mixed>
     */
    public function loadCategoryByName(string $categoryName): array
    {
        $result = $this->database->getQueryBuilder()
            ->select('category_id, name')
            ->from('categories')
            ->where('deleted = :deleted')
            ->andWhere('name = :categoryName')
            ->setParameter('deleted', 0)
            ->setParameter('categoryName', $categoryName)
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
            ->select('category_id, name')
            ->from('categories')
            ->where('deleted = :deleted')
            ->setParameter('deleted', 0)
            ->fetchAllAssociative();
    }

    /**
     * @param array<string, string|int> $category
     * @return array<string, mixed>
     */
    public function update(array $category): array
    {
        $this->database->getQueryBuilder()
            ->update('categories')
            ->set('name', ':name')
            ->where('deleted = :deleted')
            ->andWhere('category_id = :categoryId')
            ->setParameter('name', $category['name'])
            ->setParameter('deleted', 0)
            ->setParameter('categoryId', $category['category_id'])
            ->executeStatement();

        return $this->load((int) $category['category_id']);
    }
}
