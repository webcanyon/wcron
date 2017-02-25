<?php

namespace tests\WebCanyon\WCronBundle;


use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\Container;
use WebCanyon\WCronBundle\Repository\InstallRepository;


abstract class AbstractUnitTest extends KernelTestCase
{
    /** @var InstallRepository $installRepository */
    protected $installRepository;

    /** @var Container $container */
    protected $container;

    /** @var string $environment */
    protected $environment;

    /**
     * Method setup
     */
    public function setUp()
    {
        parent::setUp();

        self::bootKernel();

        $this->container = self::$kernel->getContainer();

        $this->environment = $this->container->getParameter('environment_name_test');

        //set environment variable for testing purpose
        putenv("{$this->container->getParameter('environment_variable_name')}={$this->environment}");

        $this->installRepository = new InstallRepository($this->container);

        if (!$this->installRepository->isInstalled())
            $this->installRepository->install();
    }

    /**
     * Method tearDown
     */
    public function tearDown()
    {
        if ($this->installRepository->isInstalled())
            $this->installRepository->uninstall(true);
    }
}