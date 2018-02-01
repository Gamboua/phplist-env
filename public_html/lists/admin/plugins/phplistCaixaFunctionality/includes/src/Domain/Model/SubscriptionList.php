<?php

namespace phplist\Caixa\Functionality\Domain\Model;


/**
 * Class SubscriptionList
 *
 * @package phplist\Caixa\Functionality\Domain\Model
 */
class SubscriptionList
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;
    
    /**
     * 
     * @var integer
     */
    private $active;
    
    /**
     * 
     * @var integer
     */
    private $owner;

    /**
     * SubscriberList constructor.
     */
    public function __construct()
    {
        // ...
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return the $active
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @return the $owner
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param number $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * @param number $owner
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;
    }

    /**
     * @param array $properties
     *
     * @return SubscriptionList
     */
    public static function fromArray(array $properties)
    {
        $subscriptionList = new SubscriptionList();
        $subscriptionList->id = $properties['id'];
        $subscriptionList->name = $properties['name'];
        $subscriptionList->active = $properties['active'];
        $subscriptionList->owner = $properties['owner'];

        return $subscriptionList;
    }
}
