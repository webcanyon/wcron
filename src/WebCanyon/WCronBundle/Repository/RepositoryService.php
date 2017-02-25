<?php

namespace WebCanyon\WCronBundle\Repository;


use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class RepositoryService
 *
 * @package WebCanyon\WCronBundle\Repository
 */
class RepositoryService
{
    /** @var Container */
    protected $container;

    /**
     * RepositoryService constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Method create
     *
     * @param string $repository
     *
     * @return mixed
     */
    public function create($repository)
    {
        if (!class_exists($repository) && !isset(class_implements($repository)['RepositoryInterface']))
            throw new Exception(sprintf('There was an exception when tries to create repository %s', $repository));

        static $instances = [];

        if (!isset($instances[$repository]))
            $instances[$repository] = new $repository($this->container);

        return $instances[$repository];
    }
}