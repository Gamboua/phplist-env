<?php

namespace phplist\Caixa\Functionality\Domain\Service;

use phplist\Caixa\Functionality\Domain\Model\Caixa\InvestmentFund;
use phplist\Caixa\Functionality\Domain\Model\SubscriptionList;
use phplist\Caixa\Functionality\Domain\Model\SubscriptionListRepository;
use PHPUnit\Framework\TestCase;

/**
 * Class ImportSubscriptionListTest
 *
 * @package phplist\Caixa\Functionality\Domain\Service
 */
class ImportSubscriptionListTest extends TestCase
{
    /**
     * @var InvestmentFund
     */
    private $investmentFund;

    /**
     * @var SubscriptionList
     */
    private $newSubscriptionList;

    /**
     * @var SubscriptionList
     */
    private $savedSubscriptionList;

    public function setUp()
    {
        $this->investmentFund = InvestmentFund::fromArray([
            'referenceDate' => '2015-08-01',
            'agencyNumber' => 1941,
            'operationNumber' => 1,
            'accountNumber' => 747681,
            'agencyEmail' => 'agency@agency.com.br',
            'modalityNumber' => 4930,
        ]);

        $this->newSubscriptionList = SubscriptionList::fromArray([
            'id' => null,
            'name' => '4930',
            'active' => 0,
            'owner' => 1,
        ]);

        $this->savedSubscriptionList = SubscriptionList::fromArray([
            'id' => 13,
            'name' => '4930',
            'active' => 0,
            'owner' => 1,
        ]);
    }

    public function testFindOrSaveWhenNotFoundMustCreate()
    {
        $subscriptionListRepository = $this->getMockBuilder(SubscriptionListRepository::class)->getMock();

        // expects
        $subscriptionListRepository->expects($this->once())
            ->method('findOneByName')
            ->with($this->equalTo('4930'))
            ->willReturn(null);

        $subscriptionListRepository->expects($this->once())
            ->method('add')
            ->with($this->equalTo($this->newSubscriptionList));

        $importSubscriptionList = new ImportSubscriptionList($subscriptionListRepository);
        $subscriptionList = $importSubscriptionList->findOrSave($this->investmentFund);

        $this->assertNotNull($subscriptionList);
        $this->assertEquals(SubscriptionList::class, get_class($subscriptionList));
        $this->assertEquals('4930', $subscriptionList->getName());
    }

    public function testFindOrSaveWhenFoundMustReturnIt()
    {
        $subscriptionListRepository = $this->getMockBuilder(SubscriptionListRepository::class)->getMock();

        // expects
        $subscriptionListRepository->expects($this->once())
            ->method('findOneByName')
            ->with($this->equalTo('4930'))
            ->willReturn($this->savedSubscriptionList);

        $subscriptionListRepository->expects($this->never())
            ->method('add');

        $importSubscriptionList = new ImportSubscriptionList($subscriptionListRepository);
        $subscriptionList = $importSubscriptionList->findOrSave($this->investmentFund);

        $this->assertNotNull($subscriptionList);
        $this->assertEquals(SubscriptionList::class, get_class($subscriptionList));
        $this->assertSame($this->savedSubscriptionList, $subscriptionList);
    }
}
