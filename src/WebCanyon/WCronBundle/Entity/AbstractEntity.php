<?php

namespace WebCanyon\WCronBundle\Entity;

use DateTime;
use WebCanyon\WCronBundle\Util\Strings;

/**
 * Class AbstractEntity
 *
 * @package WebCanyon\WCronBundle\Entity
 */
abstract class AbstractEntity
{
    /** @var null|DateTime $createdAt */
    protected $createdAt;

    /** @var null|DateTime $updatedAt */
    protected $updatedAt;

    /**
     * Cron constructor.
     * @param array|null $attributes
     */
    public function __construct(?array $attributes = null)
    {
        $this->inflate($attributes);
    }

    /**
     * Method inflate
     *
     * @param array|null $attributes
     *
     * @return bool
     */
    protected function inflate(?array $attributes)
    {
        if (!is_array($attributes))
            return false;

        foreach ($attributes as $key => $value) {
            $method = "set".Strings::toCamel($key, true);
            if (method_exists($this, $method))
                $this->$method($value);
        }

        return true;
    }

    /**
     * @return DateTime|null
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime|null $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return DateTime|null
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime|null $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }
}