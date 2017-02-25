<?php

namespace WebCanyon\WCronBundle\Repository;


use WebCanyon\WCronBundle\Entity\EntityInterface;

/**
 * Interface RepositoryInterface
 *
 * @package WebCanyon\WCronBundle\Repository
 */
interface RepositoryInterface
{
    public function submit(EntityInterface $entity);
}