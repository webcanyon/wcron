<?php

namespace WebCanyon\WCronBundle\Repository;


interface InstallRepositoryInterface
{
    /**
     * Method install
     *
     * @return bool
     */
    public function install(): bool;

    /**
     * Method uninstall
     *
     * @param bool $dropDatabase
     *
     * @return bool
     */
    public function uninstall($dropDatabase = false): bool;

    /**
     * Method isInstalled
     *
     * @return bool
     */
    public function isInstalled(): bool;

    /**
     * Method getDatabaseName
     *
     * @return string
     */
    public function getDatabaseName(): string;

    /**
     * Method setDatabaseName
     *
     * @param string $databaseName
     */
    public function setDatabaseName(string $databaseName);

}