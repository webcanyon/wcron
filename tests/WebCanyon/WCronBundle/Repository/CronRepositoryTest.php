<?php

namespace tests\WebCanyon\WCronBundle\Repository;


use tests\WebCanyon\WCronBundle\AbstractUnitTest;
use WebCanyon\WCronBundle\Entity\CronEntity;
use WebCanyon\WCronBundle\Entity\EntityInterface;
use WebCanyon\WCronBundle\Repository\AbstractRepositoryInterface;
use WebCanyon\WCronBundle\Repository\CronRepository;
use WebCanyon\WCronBundle\Repository\CronRepositoryInterface;

class CronRepositoryTest extends AbstractUnitTest
    implements AbstractRepositoryInterface, CronRepositoryInterface
{
    /** @var CronRepository */
    protected $repository;

    /**
     * Method setUp
     */
    public function setUp() {
        parent::setUp();
        $this->repository = new CronRepository($this->container);
    }

    /**
     * Method testSubmit
     */
    public function testSubmit()
    {
        $entity = self::stubCron();

        $submit = $this->submit($entity);

        $this->assertTrue(is_numeric($submit));
        $this->assertTrue($submit > 0);

        $entity->setId((int)$submit);
        $entity->setMutex(false);

        $submit = $this->submit($entity);

        $this->assertTrue(is_numeric($submit));
        $this->assertTrue($submit > 0);
    }

    /**
     * Method testGetAll
     */
    public function testGetAll()
    {
        //empty
        $crons = $this->getAll();

        $this->assertTrue(is_array($crons));
        $this->assertTrue(count($crons) == 0);

        //with one cron
        $entity = self::stubCron();
        $this->submit($entity);

        $crons = $this->getAll();

        $this->assertTrue(is_array($crons));
        $this->assertTrue(count($crons) == 1);
    }

    /**
     * Method submit
     *
     * @param EntityInterface $entity
     *
     * @return int|null
     */
    public function submit(EntityInterface $entity)
    {
        return $this->repository->submit($entity);
    }

    /**
     * Method getAll
     *
     * @return array|false
     */
    public function getAll()
    {
        return $this->repository->getAll();
    }

    /**
     * Method getActive
     *
     * @return array|false
     */
    public function getActive()
    {
        return $this->repository->getActive();
    }

    /**
     * Method getToRun
     *
     * @return array|false
     */
    public function getToRun()
    {
       return $this->repository->getToRun();
    }

    /**
     * Method getByStatus
     *
     * @param int $status
     *
     * @return array|false
     */
    public function getByStatus(int $status = 0)
    {
        return $this->repository->getByStatus($status);
    }

    /**
     * Method stubCron
     *
     * @return CronEntity
     */
    public function stubCron()
    {
        return new CronEntity(
            [
                'minute' => '*',
                'hour' => '*',
                'day_of_month' => '*',
                'month' => '*',
                'day_of_week' => '*',
                'start_time' => null,
                'end_time' => null,
                'command' => 'wcron:list',
                'log_file' => null,
                'error_file' => '&1',
                'comment' => 'This is a comment',
                'mutex' => true,
                'status' => 0,
            ]
        );
    }
}