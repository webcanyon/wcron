<?php

namespace WebCanyon\WCronBundle\Repository;


use WebCanyon\WCronBundle\Entity\EntityInterface;

/**
 * Class InstallRepository
 *
 * @package WebCanyon\WCronBundle\Repository
 */
class InstallRepository extends AbstractRepository
    implements RepositoryInterface, InstallRepositoryInterface
{

    /**
     * Method install
     *
     * @return bool
     */
    public function install(): bool
    {
        if (!$this->createCronsTable())
            return false;
        if (!$this->createCronsRunsTable())
            return false;

        return true;
    }

    /**
     * Method uninstall
     *
     * @param bool $dropDatabase
     *
     * @return bool
     */
    public function uninstall($dropDatabase = false): bool
    {
        if (!$this->dropCronsRunsTable())
            return false;
        if (!$this->dropCronsTable())
            return false;

        if ($dropDatabase && ! $this->dropDatabase())
            return false;

        return true;
    }

    /**
     * Method createCronsTable
     *
     * @return bool
     */
    protected function createCronsTable()
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS `crons` (
              `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
              `minute` VARCHAR(10) NOT NULL DEFAULT '*',
              `hour` VARCHAR(10) NOT NULL DEFAULT '*',
              `day_of_month` VARCHAR(10) NOT NULL DEFAULT '*',
              `month` VARCHAR(10) NOT NULL DEFAULT '*',
              `day_of_week` VARCHAR(10) NOT NULL DEFAULT '*',
              `start_time` TIME,
              `end_time` TIME,
              `command` VARCHAR(255),
              `log_file` VARCHAR(255),
              `error_file` VARCHAR(255),
              `comment` VARCHAR(255),
              `mutex` INT(1) DEFAULT 1,
              `status` INT NOT NULL DEFAULT 0,
              `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
              `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) CHARACTER SET utf8 COLLATE utf8_general_ci;
        ";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute();
    }

    /**
     * Method createCronsRunsTable
     *
     * @return bool
     */
    protected function createCronsRunsTable()
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS `crons_runs` (
              `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
              `cron_id` INT NOT NULL,
              `output` TEXT,
              `run_time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
              `comment` VARCHAR(255),
              `status` INT NOT NULL DEFAULT 0,
              `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
              `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
              INDEX `cron_run_idx` (`cron_id`),
              FOREIGN KEY (`cron_id`) REFERENCES crons(`id`) ON UPDATE CASCADE ON DELETE CASCADE 
            ) CHARACTER SET utf8 COLLATE utf8_general_ci;
        ";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute();
    }

    /**
     * Method dropCronsTable
     *
     * @return string
     */
    protected function dropCronsTable()
    {
        $sql = "DROP TABLE IF EXISTS `crons`";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute();
    }

    /**
     * Method dropCronsRunsTable
     *
     * @return string
     */
    protected function dropCronsRunsTable()
    {
        $sql = "DROP TABLE IF EXISTS `crons_runs`";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute();
    }

    /**
     * Method isInstalled
     *
     * @return bool
     */
    public function isInstalled(): bool
    {
        $sql = "SHOW TABLES LIKE 'crons'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return ($stmt->rowCount() >= 1) ? true : false;
    }

    /**
     * Method update
     *
     * @param EntityInterface $entity
     *
     * @return int|null
     */
    protected function update(EntityInterface $entity)
    {
        return null;
    }

    /**
     * Method insert
     *
     * @param EntityInterface $entity
     *
     * @return int|null
     */
    protected function insert(EntityInterface $entity)
    {
        return null;
    }

    /**
     * Method mapEntity
     *
     * @param EntityInterface $entity
     *
     * @return array
     */
    protected function mapEntity(EntityInterface $entity): array
    {
        return [];
    }

    /**
     * Method getDatabaseName
     *
     * @return string
     */
    public function getDatabaseName(): string
    {
        return $this->databaseName;
    }

    /**
     * Method setDatabaseName
     *
     * @param string $databaseName
     */
    public function setDatabaseName(string $databaseName)
    {
        $this->databaseName = $databaseName;
    }
}