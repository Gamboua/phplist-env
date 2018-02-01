<?php

namespace phplist\Caixa\Functionality\Domain\Service;

use phplist\Caixa\Functionality\Domain\Model\Caixa\InvestmentFund;
use phplist\Caixa\Functionality\Domain\Model\InvestmentFundSubscription;
use phplist\Caixa\Functionality\Domain\Model\InvestmentFundSubscriptionRepository;
use phplist\Caixa\Functionality\Domain\Model\SubscriptionList;
use phplist\Caixa\Functionality\Domain\Model\User;
use PHPUnit\Framework\TestCase;

/**
 * Class ImportInvestmentFundSubscriptionTest
 *
 * @package phplist\Caixa\Functionality\Domain\Service
 */
class ImportInvestmentFundSubscriptionTest extends TestCase
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var SubscriptionList
     */
    private $list;

    /**
     * @var InvestmentFund
     */
    private $investmentFund;

    /**
     * @var InvestmentFundSubscription
     */
    private $newInvestmentFundSubscription;

    /**
     * @var InvestmentFundSubscription
     */
    private $savedInvestmentFundSubscription;


    public function setUp()
    {
        $this->user = User::fromArray([
            'id' => 25,
            'email' => 'client@client.com.br',
        ]);

        $this->list = SubscriptionList::fromArray([
            'id' => 13,
            'name' => '4930',
            'active' => 0,
            'owner' => 1,
        ]);

        $this->investmentFund = InvestmentFund::fromArray([
            'referenceDate' => '2015-08-01',
            'agencyNumber' => 1941,
            'operationNumber' => 1,
            'accountNumber' => 747681,
            'agencyEmail' => 'agency@agency.com.br',
            'modalityNumber' => 4930,
        ]);

        $this->newInvestmentFundSubscription = InvestmentFundSubscription::fromArray([
            'user' => $this->user,
            'subscriptionList' => $this->list,
            'referenceDate' => '2015-08-01',
            'agencyNumber' => 1941,
            'operationNumber' => 1,
            'accountNumber' => 747681,
            'agencyEmail' => 'agency@agency.com.br',
            'modalityNumber' => 4930,
        ]);

        $this->savedInvestmentFundSubscription = InvestmentFundSubscription::fromArray([
            'user' => $this->user,
            'subscriptionList' => $this->list,
            'referenceDate' => '2015-08-02',
            'agencyNumber' => 1942,
            'operationNumber' => 2,
            'accountNumber' => 747682,
            'agencyEmail' => 'agency2@agency.com.br',
            'modalityNumber' => 4932,
        ]);
    }

    public function testFindOrSaveWhenNotFoundMustCreate()
    {
        $investmentFundSubscriptionRepository = $this->getMockBuilder(InvestmentFundSubscriptionRepository::class)->getMock();

        // expects
        $investmentFundSubscriptionRepository->expects($this->once())
            ->method('findOne')
            ->with($this->equalTo($this->user), $this->equalTo($this->list))
            ->willReturn(null);

        $investmentFundSubscriptionRepository->expects($this->once())
            ->method('add')
            ->with($this->equalTo($this->newInvestmentFundSubscription));

        $importInvestmentFundSubscription = new ImportInvestmentFundSubscription($investmentFundSubscriptionRepository);
        $investmentFundSubscription = $importInvestmentFundSubscription->findOrSave($this->user, $this->list, $this->investmentFund);

        $this->assertNotNull($investmentFundSubscription);
        $this->assertEquals(InvestmentFundSubscription::class, get_class($investmentFundSubscription));
        $this->assertSame($this->user, $investmentFundSubscription->getUser());
        $this->assertSame($this->list, $investmentFundSubscription->getSubscriptionList());
        $this->assertEquals('2015-08-01', $investmentFundSubscription->getReferenceDate());
        $this->assertEquals(1941, $investmentFundSubscription->getAgencyNumber());
        $this->assertEquals(1, $investmentFundSubscription->getOperationNumber());
        $this->assertEquals(747681, $investmentFundSubscription->getAccountNumber());
        $this->assertEquals('agency@agency.com.br', $investmentFundSubscription->getAgencyEmail());
        $this->assertEquals(4930, $investmentFundSubscription->getModalityNumber());
    }

    public function testFindOrSaveWhenFoundMustUpdate()
    {
        $investmentFundSubscriptionRepository = $this->getMockBuilder(InvestmentFundSubscriptionRepository::class)->getMock();

        // expects
        $investmentFundSubscriptionRepository->expects($this->once())
            ->method('findOne')
            ->with($this->equalTo($this->user), $this->equalTo($this->list))
            ->willReturn($this->savedInvestmentFundSubscription);

        $investmentFundSubscriptionRepository->expects($this->once())
            ->method('merge')
            ->with($this->equalTo($this->savedInvestmentFundSubscription));

        $importInvestmentFundSubscription = new ImportInvestmentFundSubscription($investmentFundSubscriptionRepository);
        $investmentFundSubscription = $importInvestmentFundSubscription->findOrSave($this->user, $this->list, $this->investmentFund);

        $this->assertNotNull($investmentFundSubscription);
        $this->assertEquals(InvestmentFundSubscription::class, get_class($investmentFundSubscription));
        $this->assertSame($this->user, $investmentFundSubscription->getUser());
        $this->assertSame($this->list, $investmentFundSubscription->getSubscriptionList());
        $this->assertEquals('2015-08-01', $investmentFundSubscription->getReferenceDate());
        $this->assertEquals(1941, $investmentFundSubscription->getAgencyNumber());
        $this->assertEquals(1, $investmentFundSubscription->getOperationNumber());
        $this->assertEquals(747681, $investmentFundSubscription->getAccountNumber());
        $this->assertEquals('agency@agency.com.br', $investmentFundSubscription->getAgencyEmail());
        $this->assertEquals(4930, $investmentFundSubscription->getModalityNumber());
    }
}
