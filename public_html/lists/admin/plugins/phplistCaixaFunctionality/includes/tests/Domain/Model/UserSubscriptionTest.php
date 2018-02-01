<?php

namespace phplist\Caixa\Functionality\Domain;

use phplist\Caixa\Functionality\Domain\Model\SubscriptionList;
use phplist\Caixa\Functionality\Domain\Model\User;
use phplist\Caixa\Functionality\Domain\Model\UserSubscription;
use PHPUnit\Framework\TestCase;

/**
 * Class UserSubscriptionTest
 *
 * @package phplist\Caixa\Functionality\Domain
 */
class UserSubscriptionTest extends TestCase
{
    public function testFromArray()
    {
        $user = new User();
        $subscriptionList = new SubscriptionList();

        $userSubscription = UserSubscription::fromArray([
            'user' => $user,
            'subscriptionList' => $subscriptionList,
        ]);

        $this->assertSame($user, $userSubscription->getUser());
        $this->assertSame($subscriptionList, $userSubscription->getSubscriptionList());
    }
}
