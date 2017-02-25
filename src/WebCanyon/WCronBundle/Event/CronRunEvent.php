<?php

namespace WebCanyon\WCronBundle\Event;


use Symfony\Component\EventDispatcher\Event;
use WebCanyon\WCronBundle\Entity\CronEntity;
use WebCanyon\WCronBundle\Repository\CronRepository;

/**
 * Class CronRunEvent
 *
 * @package WebCanyon\WCronBundle\Event
 */
class CronRunEvent extends Event
{
    /** @var CronEntity $cron */
    protected $cron;

    /** @var CronRepository $cronRepository */
    protected $cronRepository;

    /** @var bool $console */
    protected $console;

    /**
     * CronRunEvent constructor.
     *
     * @param CronEntity $cron
     * @param CronRepository $cronRepository
     * @param bool $console
     */
    public function __construct(CronEntity $cron, CronRepository $cronRepository, bool $console = false)
    {
        $this->cron = $cron;
        $this->cronRepository = $cronRepository;
        $this->console = $console;
    }

    /**
     * @return CronEntity
     */
    public function getCron(): CronEntity
    {
        return $this->cron;
    }

    /**
     * @param CronEntity $cron
     */
    public function setCron(CronEntity $cron)
    {
        $this->cron = $cron;
    }

    /**
     * @return CronRepository
     */
    public function getCronRepository(): CronRepository
    {
        return $this->cronRepository;
    }

    /**
     * @param CronRepository $cronRepository
     */
    public function setCronRepository(CronRepository $cronRepository)
    {
        $this->cronRepository = $cronRepository;
    }

    /**
     * @return bool
     */
    public function isConsole(): bool
    {
        return $this->console;
    }

    /**
     * @param bool $console
     */
    public function setConsole(bool $console)
    {
        $this->console = $console;
    }
}