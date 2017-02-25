<?php

namespace WebCanyon\WCronBundle\Entity;


use DateTime;
use WebCanyon\WCronBundle\Traits\CronFrequencies;

/**
 * Class Cron
 *
 * @package WebCanyon\WCronBundle\Entity
 */
class CronEntity extends AbstractEntity
    implements EntityInterface
{
    use CronFrequencies;

    /** @var null|int $id */
    protected $id;

    /** @var string $minute */
    protected $minute = '*';

    /** @var string $hour */
    protected $hour = '*';

    /** @var string $dayOfMonth */
    protected $dayOfMonth = '*';

    /** @var string $month */
    protected $month = '*';

    /** @var string $dayOfWeek */
    protected $dayOfWeek = '*';

    /** @var string $expression */
    protected $expression = '* * * * *';

    /** @var null|DateTime $startTime */
    protected $startTime;

    /** @var null|DateTime $stopTime */
    protected $stopTime;

    /** @var string $timezone */
    protected $timezone = 'Europe/Bucharest';

    /** @var string $command */
    protected $command;

    /** @var null|string $logFile */
    protected $logFile = null;

    /** @var null|string $errorFile */
    protected $errorFile = null;

    /** @var int $status
     *  -2  = suspended because an exception
     *  -1  = suspended
     *  0   = pending
     *  1   = running
     */
    protected $status;

    /** @var string $comment */
    protected $comment;

    /** @var bool $mutex */
    protected $mutex = true;

    /**
     * Status constants
     */
    const STATUS_SUSPENDED_EXCEPTION = -2;
    const STATUS_SUSPENDED = -1;
    const STATUS_PENDING = 0;
    const STATUS_RUNNING = 1;

    /**
     * Cron constructor.
     * @param array|null $attributes
     */
    public function __construct(?array $attributes = null)
    {
        $this->timezone = ini_get('date.timezone');
        parent::__construct($attributes);
        $this->setExpression($this->buildExpression());
    }

    /**
     * Method createFromString
     * Will create an instance from a cron string line
     *
     * @param $cron
     *
     * @return string|CronEntity
     */
    public static function createFromString($cron)
    {
        $status = self::STATUS_PENDING;

        if (substr($cron, 0, 12) == '#suspended: ') {
            $cron = substr($cron, 12);
            $status = self::STATUS_SUSPENDED;
        }

        $parts = \explode(' ', $cron);
        $command = \implode(' ',\array_slice($parts, 5));

        // extract comment
        if (\strpos($command, '#')) {
            list($command, $comment) = \explode('#', $command);
            $comment = \trim($comment);
        }

        // extract error file
        if (\strpos($command, '2>')) {
            list($command, $errorFile) = \explode('2>', $command);
            $errorFile = \trim($errorFile);
        }

        // extract log file
        if (\strpos($command, '>')) {
            list($command, $logFile) = \explode('>', $command);
            $logFile = \trim($logFile);
        }


        // create cron instance
        $cron = new self();
        $cron->setMinute($parts[0]);
        $cron->setHour($parts[1]);
        $cron->setDayOfMonth($parts[2]);
        $cron->setMonth($parts[3]);
        $cron->setDayOfWeek($parts[4]);
        $cron->setCommand(\trim($command));
        $cron->setStatus($status);

        if (isset($comment)) {
            $cron->setComment($comment);
        }
        if (isset($logFile)) {
            $cron->setLogFile($logFile);
        }
        if (isset($errorFile)) {
            $cron->setErrorFile($errorFile);
        }

        if (strpos($cron, '#mutexed ') !== false) {
            $cron->setMutex(true);
            $cron->setComment(str_replace('mutexed ', '', $cron->getComment()));
        }

        return $cron;
    }

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
     * @return string
     */
    public function getMinute(): string
    {
        return $this->minute;
    }

    /**
     * @param string $minute
     */
    public function setMinute(string $minute)
    {
        $this->minute = $minute;
    }

    /**
     * @return string
     */
    public function getHour(): string
    {
        return $this->hour;
    }

    /**
     * @param string $hour
     */
    public function setHour(string $hour)
    {
        $this->hour = $hour;
    }

    /**
     * @return string
     */
    public function getDayOfMonth(): string
    {
        return $this->dayOfMonth;
    }

    /**
     * @param string $dayOfMonth
     */
    public function setDayOfMonth(string $dayOfMonth)
    {
        $this->dayOfMonth = $dayOfMonth;
    }

    /**
     * @return string
     */
    public function getMonth(): string
    {
        return $this->month;
    }

    /**
     * @param string $month
     */
    public function setMonth(string $month)
    {
        $this->month = $month;
    }

    /**
     * @return string
     */
    public function getDayOfWeek(): string
    {
        return $this->dayOfWeek;
    }

    /**
     * @param string $dayOfWeek
     */
    public function setDayOfWeek(string $dayOfWeek)
    {
        $this->dayOfWeek = $dayOfWeek;
    }

    /**
     * @return DateTime|null
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * @param DateTime|null $startTime
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;
    }

    /**
     * @return DateTime|null
     */
    public function getStopTime()
    {
        return $this->stopTime;
    }

    /**
     * @param DateTime|null $stopTime
     */
    public function setStopTime($stopTime)
    {
        $this->stopTime = $stopTime;
    }

    /**
     * @return string
     */
    public function getTimezone(): string
    {
        return $this->timezone;
    }

    /**
     * @param string $timezone
     */
    public function setTimezone(string $timezone)
    {
        $this->timezone = $timezone;
    }

    /**
     * @return string
     */
    public function getCommand(): string
    {
        return $this->command;
    }

    /**
     * @param string $command
     */
    public function setCommand(string $command)
    {
        $this->command = $command;
    }

    /**
     * @return null|string
     */
    public function getLogFile()
    {
        return $this->logFile;
    }

    /**
     * @param null|string $logFile
     */
    public function setLogFile($logFile)
    {
        $this->logFile = $logFile;
    }

    /**
     * @return null|string
     */
    public function getErrorFile()
    {
        return $this->errorFile;
    }

    /**
     * @param null|string $errorFile
     */
    public function setErrorFile($errorFile)
    {
        $this->errorFile = $errorFile;
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
     * @return bool
     */
    public function isMutex(): bool
    {
        return $this->mutex;
    }

    /**
     * @param bool $mutex
     */
    public function setMutex(bool $mutex)
    {
        $this->mutex = $mutex;
    }

    /**
     * Method setExpression
     *
     * @param null|string $expression
     */
    public function setExpression(?string $expression)
    {
        if ($expression)
            $this->expression = $expression;
        else
            $this->expression = $this->buildExpression();
    }

    /**
     * Methid getExpression
     *
     * @return string
     */
    public function getExpression()
    {
        return $this->expression;
    }

    /**
     * Method buildExpression
     * Concats time data to get the time expression
     *
     * @return string
     */
    public function buildExpression()
    {
        return \sprintf('%s %s %s %s %s', $this->minute, $this->hour, $this->dayOfMonth, $this->month, $this->dayOfWeek);
    }

    /**
     * Method buildShowExpression
     * Concats time data to get the time expression for colored display purpose
     *
     * @return string
     */
    public function buildShowExpression()
    {
        return \sprintf('<red>%s</red> <magenta>%s</magenta> <green>%s</green> <red>%s</red> <magenta>%s</magenta>', $this->minute, $this->hour, $this->dayOfMonth, $this->month, $this->dayOfWeek);
    }

    /**
     * Method showCommand
     *
     * @return string
     */
    public function showCommand()
    {
        switch ($this->getStatus()) {
            case self::STATUS_SUSPENDED_EXCEPTION:
                $status = '<error>EXCEPTION</error>';
                break;
            case self::STATUS_SUSPENDED:
                $status = '<warning>SUSPENDED</warning>';
                break;
            case self::STATUS_RUNNING:
                $status = '<running>RUNNING</running>';
                break;
            default:
                $status = '<pending>PENDING</pending>';
        }

        $expression = '<expression>'.$this->buildShowExpression().'</expression>';


        $command = trim($this->command);

        $logFile = '   ';
        if ('' != $this->logFile) {
            $logFile = ' > '.$this->logFile;
        }

        $errorFile = '    ';
        if ('' != $this->errorFile) {
            $errorFile = ' 2> '.$this->errorFile;
        }

        $mutexed = '      ';
        if ($this->mutex)
            $mutexed = 'MUTEXED';

        $cronLine = "<default>@{$this->getId()}</default> $status $mutexed $expression $command $logFile $errorFile <comment>#{$this->getComment()}</comment>";

        return $cronLine;
    }

    /**
     * Transforms the cron instance into a cron line
     *
     * @return string
     */
    public function __toString()
    {
        $cronLine = '';
        if ($this->getStatus() == self::STATUS_SUSPENDED) {
            $cronLine .= '#suspended: ';
        } elseif ($this->getStatus() == self::STATUS_SUSPENDED_EXCEPTION) {
            $cronLine .= '#EXCEPTION: ';
        }

        $cronLine .= $this->buildExpression().' '.$this->command;
        if ('' != $this->logFile) {
            $cronLine .= ' > '.$this->logFile;
        }

        if ('' != $this->errorFile) {
            $cronLine .= ' 2> '.$this->errorFile;
        }

        if ($this->mutex)
            $cronLine .= ' #mutexed ';

        if ('' != $this->comment) {
            if (!$this->mutex)
                $cronLine .= ' #';
            $cronLine .= $this->comment;
        }

        return $cronLine;
    }
}