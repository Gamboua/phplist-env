<?php

namespace phplist\Caixa\Functionality\Domain\Service;

use phplist\Caixa\Functionality\Domain\Model\SubscriptionList;
use phplist\Caixa\Functionality\Domain\Model\User;
use phplist\Caixa\Functionality\Domain\Model\UserSubscription;
use phplist\Caixa\Functionality\Domain\Model\UserSubscriptionRepository;
use PHPUnit\Framework\TestCase;

/**
 * Class ImportUserSubscriptionTest
 *
 * @package phplist\Caixa\Functionality\Domain\Service
 */
class ImportUserSubscriptionTest extends TestCase
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
     * @var UserSubscription
     */
    private $newUserSubscription;

    /**
     * @var UserSubscription
     */
    private $savedUserSubscription;

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

        $this->newUserSubscription = UserSubscription::fromArray([
            'user' => $this->user,
            'subscriptionList' => $this->list,
        ]);

        $this->savedUserSubscription = UserSubscription::fromArray([
            'user' => $this->user,
            'subscriptionList' => $this->list,
        ]);
    }

    public function testFindOrSaveWhenNotFoundMustCreate()
    {
        $userSubscriptionRepository = $this->getMockBuilder(UserSubscriptionRepository::class)->getMock();

        // expects
        $userSubscriptionRepository->expects($this->once())
            ->method('findOne')
            ->with($this->equalTo($this->user), $this->equalTo($this->list))
            ->willReturn(null);

        $userSubscriptionRepository->expects($this->once())
            ->method('add')
            ->with($this->equalTo($this->newUserSubscription));

        $importUserSubscription = new ImportUserSubscription($userSubscriptionRepository);
        $userSubscription = $importUserSubscription->findOrSave($this->user, $this->list);

        $this->assertNotNull($userSubscription);
        $this->assertEquals(UserSubscription::class, get_class($userSubscription));
        $this->assertSame($this->user, $userSubscription->getUser());
        $this->assertSame($this->list, $userSubscription->getSubscriptionList());
    }

    public function testFindOrSaveWhenFoundMustReturnIt()
    {
        $userSubscriptionRepository = $this->getMockBuilder(UserSubscriptionRepository::class)->getMock();

        // expects
        $userSubscriptionRepository->expects($this->once())
            ->method('findOne')
            ->with($this->equalTo($this->user), $this->equalTo($this->list))
            ->willReturn($this->savedUserSubscription);

        $userSubscriptionRepository->expects($this->never())->method('add');

        $importUserSubscription = new ImportUserSubscription($userSubscriptionRepository);
        $userSubscription = $importUserSubscription->findOrSave($this->user, $this->list);

        $this->assertNotNull($userSubscription);
        $this->assertEquals(UserSubscription::class, get_class($userSubscription));
        $this->assertSame($this->user, $userSubscription->getUser());
        $this->assertSame($this->list, $userSubscription->getSubscriptionList());
    }
}
