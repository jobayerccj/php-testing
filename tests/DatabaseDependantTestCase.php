<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use Doctrine\ORM\EntityManagerInterface;

class DatabaseDependantTestCase extends TestCase
{
    protected ?EntityManagerInterface $entityManager;
    
    protected function setUp(): void
    {
        require __DIR__ . '/connection.php';
        
        $this->entityManager = $entityManager;
        SchemaLoader::load($entityManager);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }

    public function assertDatabaseHasEntry(string $entityName, array $criteria)
    {
        $result = $this->entityManager->getRepository($entityName)->findOneBy($criteria);

        $this->assertTrue((bool) $result, sprintf('No entry found in the database for %s with criteria: %s', $entityName, json_encode($criteria)));
    }
}
