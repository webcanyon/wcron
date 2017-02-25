<?php

namespace WebCanyon\WCronBundle\Repository;


use Exception;
use PDO;
use PDOException;
use PDOStatement;
use Symfony\Component\DependencyInjection\Container;
use WebCanyon\WCronBundle\Entity\EntityInterface;
use WebCanyon\WCronBundle\Util\Collection;

/**
 * Class AbstractRepository
 *
 * @package WebCanyon\WCronBundle\Repository
 */
abstract class AbstractRepository implements AbstractRepositoryInterface
{
    const TIMESTAMP_FORMAT = 'Y-m-d H:i:s';

    /** @var  Container $container */
    protected $container;

    /** @var PDO $db */
    public $db;

    /** @var string $databaseName */
    protected $databaseName = 'wcrons';

    /** @var string $environment */
    protected $environment = 'dev';

    /** @var array $databaseParameters */
    protected $databaseParameters = [
        'database_host' => 'database_host',
        'database_port' => 'database_port',
        'database_name' => 'database_name',
        'database_user' => 'database_user',
        'database_password' => 'database_password',
    ];

    /**
     * AbstractRepository constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;

        $this->environment = getenv($this->container->getParameter('environment_variable_name'));

        $this->prepareEnvironmentParameter();

        $this->databaseName = $this->container->getParameter($this->databaseParameters['database_name']);

        $this->makeConnection();
    }

    /**
     * Method makeConnection
     */
    protected function makeConnection()
    {
        try {
            $this->db = $this->connect(true);
        } catch (Exception $exception) {
            if ($exception->getCode() == 1049) {
                $crateDatabase = $this->createDatabase();
                if ($crateDatabase === true)
                    $this->makeConnection();
                elseif ($crateDatabase instanceof Exception)
                    throw $crateDatabase;
            } else
                throw $exception;
        }
    }

    /**
     * Method connect
     *
     * @param bool $databaseExist
     *
     * @return PDO
     */
    protected function connect(bool $databaseExist = false)
    {
        if (!$databaseExist)
            $connection = sprintf(
                "mysql:host=%s;",
                $this->container->getParameter($this->databaseParameters['database_host'])
            );
        else
            $connection = sprintf(
                "mysql:host=%s;dbname=%s",
                $this->container->getParameter($this->databaseParameters['database_host']),
                $this->container->getParameter($this->databaseParameters['database_name'])
            );

        return new PDO(
            $connection,
            $this->container->getParameter($this->databaseParameters['database_user']),
            $this->container->getParameter($this->databaseParameters['database_password'])
        );
    }

    /**
     * Method prepareEnvironmentParameter
     *
     * @return bool
     */
    protected function prepareEnvironmentParameter()
    {
        if (!$this->environment)
            return false;

        foreach ($this->databaseParameters as $key => $value) {
            $this->databaseParameters[$key] = "{$this->environment}_$value";
        }
    }

    /**
     * Method createDatabase
     *
     * @return bool|Exception|PDOException
     */
    protected function createDatabase()
    {
        try {
            $db = $this->connect();

            $sql = "CREATE DATABASE `{$this->databaseName}`;
                CREATE USER '{$this->container->getParameter('database_user')}'@'{$this->container->getParameter('database_host')}' IDENTIFIED BY '{$this->container->getParameter('database_password')}';
                GRANT ALL ON `$this->databaseName`.* TO '{$this->container->getParameter('database_user')}'@'{$this->container->getParameter('database_host')}';
                FLUSH PRIVILEGES;";

            if ($db->exec($sql) === false)
                return false;

        } catch (PDOException $e) {
            return $e;
        }

        return true;
    }

    /**
     * Method dropDatabase
     */
    protected function dropDatabase()
    {
        $sql = "DROP DATABASE IF EXISTS `{$this->databaseName}`";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute();
    }

    /**
     * Method submit
     *
     * @param EntityInterface $entity
     *
     * @return int|null
     */
    public function submit(EntityInterface $entity){
        if ($entity->getId())
            return $this->update($entity);
        else
            return $this->insert($entity);
    }

    /**
     * @param EntityInterface $entity
     *
     * @return int|null
     */
    abstract protected function update(EntityInterface $entity);

    /**
     * Method insert
     *
     * @param EntityInterface $entity
     *
     * @return int|null
     */
    abstract protected function insert(EntityInterface $entity);

    /**
     * Method mapEntity
     *
     * @param EntityInterface $entity
     *
     * @return array
     */
    abstract protected function mapEntity(EntityInterface $entity): array;


    /**
     * Method prepareAndExecute
     *
     * @param string $sql
     * @param array|null $params
     *
     * @return PDOStatement
     */
    private function prepareAndExecute(string $sql, ?array $params = null)
    {
        $stmt = $this->db->prepare($sql);

        if (is_array($params))
            $executed = $stmt->execute($params);
        else
            $executed = $stmt->execute();

        if (!$executed)
            throw new PDOException('SQL statement not executed.');

        return $stmt;
    }

    /**
     * Method fetchAffected
     *
     * @param string $sql
     * @param array|null $params
     *
     * @return int
     */
    protected function fetchAffected(string $sql, ?array $params = null)
    {
        $stmt = $this->prepareAndExecute($sql, $params);

        return $stmt->rowCount();
    }

    /**
     * Method getInsertionId
     *
     * @param string $sql
     * @param array|null $params
     *
     * @return string
     */
    protected function getInsertionId(string $sql, ?array $params = null)
    {
        $this->prepareAndExecute($sql, $params);

        return $this->db->lastInsertId();
    }

    /**
     * Method fetchAll
     *
     * @param string $sql
     * @param array|null $params
     *
     * @return array|false
     */
    protected function fetchAll(string $sql, ?array $params = null)
    {
        $stmt = $this->prepareAndExecute($sql, $params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Method debugSql
     *
     * @param string $sql
     * @param array|null $params
     *
     * @return mixed|string
     */
    protected function debugSql(string $sql, ?array $params = null)
    {
        $params = Collection::sortDescByKeyLength($params);
        foreach ($params as $key => $value)
        {
            $sql = str_replace(":$key", $this->db->quote($value), $sql);
        }

        return $sql;
    }
}