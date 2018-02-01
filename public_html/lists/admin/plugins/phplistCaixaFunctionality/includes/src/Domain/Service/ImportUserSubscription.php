<?php

namespace phplist\Caixa\Functionality\Domain\Service;

use phplist\Caixa\Functionality\Domain\Model\SubscriptionList;
use phplist\Caixa\Functionality\Domain\Model\User;
use phplist\Caixa\Functionality\Domain\Model\UserSubscription;
use phplist\Caixa\Functionality\Domain\Model\UserSubscriptionRepository;

/**
 * Class ImportUserSubscription
 *
 * @package phplist\Caixa\Functionality\Domain\Service
 */
class ImportUserSubscription
{
    /**
     * @var UserSubscriptionRepository
     */
    private $userSubscriptionRepository;

    /**
     * ImportUserSubscription constructor.
     *
     * @param UserSubscriptionRepository $userSubscriptionRepository
     */
    public function __construct(UserSubscriptionRepository $userSubscriptionRepository)
    {
        $this->userSubscriptionRepository = $userSubscriptionRepository;
    }

    /**
     * @param User $user
     * @param SubscriptionList $subscriptionList
     *
     * @return UserSubscription
     */
    public function findOrSave(User $user, SubscriptionList $subscriptionList)
    {
        $userSubscription = $this->userSubscriptionRepository->findOne($user, $subscriptionList);
        if (!$userSubscription) {

            $userSubscription = new UserSubscription();
            $userSubscription->setUser($user);
            $userSubscription->setSubscriptionList($subscriptionList);

            $this->userSubscriptionRepository->add($userSubscription);

        }

        return $userSubscription;
    }
}
