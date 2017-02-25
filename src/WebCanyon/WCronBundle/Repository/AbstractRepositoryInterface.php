<?php

namespace WebCanyon\WCronBundle\Repository;


use WebCanyon\WCronBundle\Entity\EntityInterface;

interface AbstractRepositoryInterface
{
    /**
     * Method submit
     *
     * @param EntityInterface $entity
     *
     * @return int|null
     */
    public function submit(EntityInterface $entity);
}