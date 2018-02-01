<?php

namespace phplist\Caixa\Functionality\Domain\Model;

/**
 * Class UserSubscription
 *
 * @package phplist\Caixa\Functionality\Domain\Model
 */
class UserSubscription
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var SubscriptionList
     */
    private $subscriptionList;

    /**
     * UserSubscription constructor.
     */
    public function __construct()
    {
        // ...
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return SubscriptionList
     */
    public function getSubscriptionList()
    {
        return $this->subscriptionList;
    }

    /**
     * @param SubscriptionList $subscriptionList
     */
    public function setSubscriptionList($subscriptionList)
    {
        $this->subscriptionList = $subscriptionList;
    }

    /**
     * @param array $properties
     *
     * @return UserSubscription
     */
    public static function fromArray(array $properties)
    {
        $userSubscription = new UserSubscription();
        $userSubscription->user = $properties['user'];
        $userSubscription->subscriptionList = $properties['subscriptionList'];

        return $userSubscription;
    }
}
