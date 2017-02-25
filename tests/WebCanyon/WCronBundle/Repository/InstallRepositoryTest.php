<?php

namespace tests\WebCanyon\WCronBundle\Repository;


use tests\WebCanyon\WCronBundle\AbstractUnitTest;
use WebCanyon\WCronBundle\Repository\InstallRepository;
use WebCanyon\WCronBundle\Repository\InstallRepositoryInterface;

/**
 * Class InstallRepositoryTest
 *
 * @package tests\WebCanyon\WCronBundle\Repository
 */
class InstallRepositoryTest extends AbstractUnitTest
    implements InstallRepositoryInterface
{
    /** @var InstallRepository $repository */
    protected $repository;

    /**
     * Method setUp
     */
    public function setUp()
    {
        parent::setUp();
        $this->repository = new InstallRepository($this->container);
    }

    /**
     * Method testInstall
     */
    public function testInstall()
    {
        $this->assertTrue($this->install());
    }

    /**
     * Method testIsInstalled
     */
    public function testIsInstalled()
    {
        $this->install();
        $this->assertTrue($this->isInstalled());
    }

    /**
     * Method testUninstall
     *
     * @depends testInstall
     */
    public function testUninstall()
    {
        $this->install();
        $this->assertTrue($this->uninstall());
    }

    /**
     * Method testIsNotInstalled
     */
    public function testIsNotInstalled()
    {
        $this->uninstall();
        $this->assertFalse($this->isInstalled());
    }

    /**
     * Method install
     *
     * @return bool
     */
    public function install(): bool
    {
        return $this->repository->install();
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
        return $this->repository->uninstall($dropDatabase);
    }

    /**
     * Method isInstalled
     *
     * @return bool
     */
    public function isInstalled(): bool
    {
        return $this->repository->isInstalled();
    }

    /**
     * Method getDatabaseName
     *
     * @return string
     */
    public function getDatabaseName(): string
    {
        // TODO: Implement getDatabaseName() method.
        return '';
    }

    /**
     * Method setDatabaseName
     *
     * @param string $databaseName
     */
    public function setDatabaseName(string $databaseName)
    {
        // TODO: Implement setDatabaseName() method.
    }

    /**
     * Method setDown
     */
    public function setDown()
    {
        $this->install();
    }
}