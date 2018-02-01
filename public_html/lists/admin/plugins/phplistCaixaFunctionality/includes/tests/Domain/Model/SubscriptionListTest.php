<?php

namespace phplist\Caixa\Functionality\Domain;

use phplist\Caixa\Functionality\Domain\Model\SubscriptionList;
use PHPUnit\Framework\TestCase;

/**
 * Class SubscriptionListTest
 *
 * @package phplist\Caixa\Functionality\Domain
 */
class SubscriptionListTest extends TestCase
{
    public function testFromArray()
    {
        $subscriptionList = SubscriptionList::fromArray([
            'id' => 13,
            'name' => 'the list name',
            'active' => 0,
            'owner' => 1,
        ]);

        $this->assertEquals(13, $subscriptionList->getId());
        $this->assertEquals('the list name', $subscriptionList->getName());
        $this->assertEquals(0, $subscriptionList->getActive());
        $this->assertEquals(1, $subscriptionList->getOwner());
    }
}
