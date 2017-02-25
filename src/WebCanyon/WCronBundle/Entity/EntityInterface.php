<?php

namespace WebCanyon\WCronBundle\Entity;


interface EntityInterface
{
    /**
     * Method getId
     *
     * @return mixed
     */
    public function getId();

    /**
     * Method getCreatedAt
     *
     * @return mixed
     */
    public function getCreatedAt();

    /**
     * Method getUpdatedAt
     *
     * @return mixed
     */
    public function getUpdatedAt();
}