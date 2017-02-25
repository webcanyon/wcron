<?php

namespace WebCanyon\WCronBundle\Repository;


use WebCanyon\WCronBundle\Entity\CronRunEntity;
use WebCanyon\WCronBundle\Entity\EntityInterface;

/**
 * Class CronRunRepository
 *
 * @package WebCanyon\WCronBundle\Repository
 */
class CronRunRepository extends AbstractRepository
    implements RepositoryInterface
{
    /**
     * @param EntityInterface $cron
     * @return array
     */
    protected function mapEntity(EntityInterface $cron): array
    {
        /** @var CronRunEntity $cron */
        return [
            'cron_id' => $cron->getCronId(),
            'output' => $cron->getOutput(),
            'run_time' => $cron->getRunTime()->format(self::TIMESTAMP_FORMAT) ,
            'comment' => $cron->getComment() ,
            'status' => $cron->getStatus(),
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
            INSERT INTO `crons_runs`
              ( `cron_id`, `output`, `run_time`, `comment`, `status`)
            VALUES 
              ( :cron_id , :output , :run_time , :comment , :status);
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
            UPDATE `crons_runs` SET
              `output` = :output,
              `run_time` = :run_time,
              `comment` = :comment,
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
     * @return array
     */
    public function getAll()
    {
        $sql = "SELECT * FROM `crons_runs`;";

        return $this->fetchAll($sql);
    }
}