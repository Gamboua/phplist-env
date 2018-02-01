<?php

namespace phplist\Caixa\Functionality\Domain\Model;

/**
 * Interface UserSubscriptionRepository
 *
 * @package phplist\Caixa\Functionality\Domain\Model
 */
interface UserSubscriptionRepository
{
    /**
     * @param User $user
     * @param SubscriptionList $subscriptionList
     *
     * @return UserSubscription
     */
    public function findOne(User $user, SubscriptionList $subscriptionList);

    /**
     * @param UserSubscription $userSubscription
     *
     * @return void
     */
    public function add(UserSubscription $userSubscription);
}
