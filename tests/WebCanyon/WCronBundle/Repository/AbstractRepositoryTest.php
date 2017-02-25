<?php

namespace tests\WebCanyon\WCronBundle\Repository;


use Exception;
use tests\WebCanyon\WCronBundle\AbstractUnitTest;
use WebCanyon\WCronBundle\Repository\InstallRepository;

class AbstractRepositoryTest extends AbstractUnitTest
{
    /**
     * Method testMakeConnection
     */
    public function testMakeConnection()
    {
        try {
            new InstallRepository($this->container);
        } catch (Exception $exception) {
            $this->fail(
                sprintf(
                    "Repositories abstract test make connection fail with code %s and message %s",
                    $exception->getCode(),
                    $exception->getMessage()
                )
            );
        }
    }
}