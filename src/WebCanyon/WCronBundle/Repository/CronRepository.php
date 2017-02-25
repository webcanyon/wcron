<?php

namespace WebCanyon\WCronBundle\Repository;


use WebCanyon\WCronBundle\Entity\CronEntity;
use WebCanyon\WCronBundle\Entity\EntityInterface;

/**
 * Class CronRepository
 *
 * @package WebCanyon\WCronBundle\Repository
 */
class CronRepository extends AbstractRepository
    implements RepositoryInterface, CronRepositoryInterface
{
    protected $tableName = 'crons';

    /**
     * @param EntityInterface $cron
     * @return array
     */
    protected function mapEntity(EntityInterface $cron): array
    {
        /** @var CronEntity $cron */
        return [
            'minute' => $cron->getMinute(),
            'hour' => $cron->getHour() ,
            'day_of_month' => $cron->getDayOfMonth() ,
            'month' => $cron->getMonth() ,
            'day_of_week' => $cron->getDayOfWeek() ,
            'start_time' => ($cron->getStartTime()) ? $cron->getStartTime()->format(self::TIMESTAMP_FORMAT) : null,
            'end_time' => ($cron->getStopTime()) ? $cron->getStopTime()->format(self::TIMESTAMP_FORMAT) : null,
            'command' => $cron->getCommand() ,
            'log_file' => $cron->getLogFile() ,
            'error_file' => $cron->getErrorFile() ,
            'comment' => $cron->getComment(),
            'mutex' => (int)$cron->isMutex(),
            'status' => (int)$cron->getStatus(),
        ];
    }

    /**
     * Method insert
     *
     * @param EntityInterface $cron
     *
     * @return int|null
     */
    protected function insert(EntityInterface $cron)
    {

        $sql = "
            INSERT INTO `crons`
              ( `minute`, `hour`, `day_of_month`, `month`, `day_of_week`, `start_time`, `end_time`, 
                `command`, `log_file`, `error_file`, `comment`, `mutex`, `status`)
            VALUES 
              ( :minute , :hour , :day_of_month , :month , :day_of_week , :start_time , :end_time , 
                :command , :log_file , :error_file , :comment , :mutex , :status);
        ";

        $params = $this->mapEntity($cron);

        return $this->getInsertionId($sql, $params);
    }

    /**
     * Method update
     *
     * @param EntityInterface $cron
     *
     * @return int
     */
    protected function update(EntityInterface $cron)
    {
        $sql = "
            UPDATE `crons` SET
              `minute` = :minute,
              `hour` = :hour,
              `day_of_month` = :day_of_month,
              `month` = :month,
              `day_of_week` = :day_of_week,
              `start_time` = :start_time,
              `end_time` = :end_time,
              `command` = :command,
              `log_file` = :log_file,
              `error_file` = :error_file,
              `comment` = :comment,
              `mutex` = :mutex,
              `status` = :status
            WHERE
              `id` = :id;
        ";

        $params = $this->mapEntity($cron);
        $params['id'] = $cron->getId();

        return $this->fetchAffected($sql, $params);
    }

    /**
     * Method getAll
     *
     * @return array|false
     */
    public function getAll()
    {
        $sql = "SELECT * FROM `crons`;";

        return $this->fetchAll($sql);
    }

    /**
     * Method getActive
     *
     * @return array|false
     */
    public function getActive()
    {
        $sql = "SELECT * FROM `crons` WHERE `status` >= 0";

        return $this->fetchAll($sql);
    }

    /**
     * Method getToRun
     *
     * @return array|false
     */
    public function getToRun()
    {
        $sql = "
            SELECT * 
            FROM `crons` 
            WHERE 
              `status` = 0 
              OR (`status` = 1 AND `mutex` = 0)
            ;
        ";

        return $this->fetchAll($sql);
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
        $sql = "SELECT * FROM `crons` WHERE `status` = :status";
        return $this->fetchAll($sql, ['status' => $status]);
    }
}