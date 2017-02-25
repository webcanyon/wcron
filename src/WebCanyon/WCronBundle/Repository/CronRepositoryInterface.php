<?php

namespace WebCanyon\WCronBundle\Repository;


interface CronRepositoryInterface
{

    /**
     * Method getAll
     *
     * @return array|false
     */
    public function getAll();

    /**
     * Method getActive
     *
     * @return array|false
     */
    public function getActive();

    /**
     * Method getToRun
     *
     * @return array|false
     */
    public function getToRun();

    /**
     * Method getByStatus
     *
     * @param int $status
     *
     * @return array|false
     */
    public function getByStatus(int $status = 0);
}