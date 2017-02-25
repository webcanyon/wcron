<?php

namespace WebCanyon\WCronBundle\Entity;
use DateTime;

/**
 * Class CronRunEntity
 *
 * @package WebCanyon\WCronBundle\Entity
 */
class CronRunEntity extends AbstractEntity
    implements EntityInterface
{
    /** @var int|null $id */
    protected $id;

    /** @var int $cronId */
    protected $cronId;

    /** @var string $output */
    protected $output;

    /** @var DateTime $runTime */
    protected $runTime;

    /** @var string $comment */
    protected $comment;

    /** @var int $status */
    protected $status;

    const STATUS_FAIL = 0;
    const STATUS_SUCCESS = 1;

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getCronId(): int
    {
        return $this->cronId;
    }

    /**
     * @param int $cronId
     */
    public function setCronId(int $cronId)
    {
        $this->cronId = $cronId;
    }

    /**
     * @return string
     */
    public function getOutput(): string
    {
        return $this->output;
    }

    /**
     * @param string $output
     */
    public function setOutput(string $output)
    {
        $this->output = $output;
    }

    /**
     * @return DateTime
     */
    public function getRunTime(): DateTime
    {
        return $this->runTime;
    }

    /**
     * @param DateTime $runTime
     */
    public function setRunTime(DateTime $runTime)
    {
        $this->runTime = $runTime;
    }

    /**
     * @return string
     */
    public function getComment(): string
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     */
    public function setComment(string $comment)
    {
        $this->comment = $comment;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status)
    {
        $this->status = $status;
    }
}