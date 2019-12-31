<?php

namespace OnlyTracker\Tests\Helpers;

use Doctrine\Bundle\DoctrineBundle\ConnectionFactory;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\EventManager;
use Doctrine\Common\Persistence\Mapping\Driver\SymfonyFileLocator;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\XmlDriver;
use Doctrine\ORM\Repository\DefaultRepositoryFactory;
use Doctrine\ORM\Tools\SchemaTool;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Doctrine\Logger\DbalLogger;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Yaml;

class DoctrineHelper
{
    public static function createEntityManager(LoggerInterface $logger = null)
    {
        $config = new Configuration();
        $config->setEntityNamespaces(['DomainEntity' => 'OnlyTracker\Domain\Entity']);
        $config->setAutoGenerateProxyClasses(true);
        $config->setProxyDir(sys_get_temp_dir());
        $config->setProxyNamespace('DoctrineProxies');
        $config->setMetadataDriverImpl(
            new XmlDriver(new SymfonyFileLocator([
                __DIR__ . '/../../src/Infrastructure/Doctrine/Mapping/' => 'OnlyTracker\Domain\Entity'
            ], '.orm.xml'))
            // new XmlDriver(__DIR__ . '/../../src/Infrastructure/Doctrine/Mapping', '.orm.xml')
        );
        $config->setQueryCacheImpl(new ArrayCache());
        $config->setMetadataCacheImpl(new ArrayCache());
        $config->setSQLLogger(new DbalLogger($logger));
        $config->setRepositoryFactory(new DefaultRepositoryFactory());

        $params = [
            'driver' => 'pdo_sqlite',
            'memory' => true,
        ];

        $yamlConfig = (new Parser)->parseFile(__DIR__ . '/../../applications/onlytracker_backend/config/packages/doctrine.yaml', Yaml::PARSE_CONSTANT | Yaml::PARSE_CUSTOM_TAGS);

        foreach ($yamlConfig['doctrine']['dbal']['types'] ?? [] as $name => $class) {
            Type::addType($name, $class);
        }

        $entityManager = EntityManager::create($params, $config, new EventManager);
        $metadata = $entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->updateSchema($metadata);

        return $entityManager;
    }
}
