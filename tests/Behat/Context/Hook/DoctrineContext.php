<?php

/*
 * This file is part of the Sylius ProductBundle Plugin package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Tests\Sylius\ProductBundlePlugin\Behat\Context\Hook;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Hook\BeforeScenario;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\DBAL\Platforms\MySQLPlatform;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineContext implements Context
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    #[BeforeScenario]
    public function purgeDatabase(BeforeScenarioScope $scope): void
    {
        $connection = $this->entityManager->getConnection();
        $configuration = $connection->getConfiguration();
        if (method_exists($configuration, 'setSQLLogger')) {
            $configuration->setSQLLogger(null);
        }

        $isMysql = $connection->getDatabasePlatform() instanceof MySQLPlatform;
        if ($isMysql) {
            $connection->executeStatement('SET foreign_key_checks = 0');
        }

        try {
            $purger = new ORMPurger($this->entityManager);
            $purger->purge();
        } finally {
            if ($isMysql) {
                $connection->executeStatement('SET foreign_key_checks = 1');
            }
        }

        $this->entityManager->clear();
    }
}
