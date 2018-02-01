<?php

namespace phplist\Caixa\Functionality\Domain;

use phplist\Caixa\Functionality\Domain\Model\InvestmentFundSubscription;
use phplist\Caixa\Functionality\Domain\Model\SubscriptionList;
use phplist\Caixa\Functionality\Domain\Model\User;
use PHPUnit\Framework\TestCase;

/**
 * Class InvestmentFundSubscriptionTest
 *
 * @package phplist\Caixa\Functionality\Domain
 */
class InvestmentFundSubscriptionTest extends TestCase
{
    public function testFromArray()
    {
        $user = new User();
        $subscriptionList = new SubscriptionList();

        $investmentFundSubscription = InvestmentFundSubscription::fromArray([
            'user' => $user,
            'subscriptionList' => $subscriptionList,
            'referenceDate' => '2015-08-01',
            'agencyNumber' => 1941,
            'operationNumber' => 1,
            'accountNumber' => 747681,
            'agencyEmail' => 'agency@agency.com.br',
            'modalityNumber' => 5930,
        ]);

        $this->assertSame($user, $investmentFundSubscription->getUser());
        $this->assertSame($subscriptionList, $investmentFundSubscription->getSubscriptionList());
        $this->assertEquals('2015-08-01', $investmentFundSubscription->getReferenceDate());
        $this->assertEquals(1941, $investmentFundSubscription->getAgencyNumber());
        $this->assertEquals(1, $investmentFundSubscription->getOperationNumber());
        $this->assertEquals(747681, $investmentFundSubscription->getAccountNumber());
        $this->assertEquals('agency@agency.com.br', $investmentFundSubscription->getAgencyEmail());
        $this->assertEquals(5930, $investmentFundSubscription->getModalityNumber());
    }
}
