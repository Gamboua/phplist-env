<?php

namespace phplist\Caixa\Functionality\Infrastructure\DB\DAO;

use phplist\Caixa\Functionality\Domain\Model\SubscriptionList;
use phplist\Caixa\Functionality\Domain\Model\User;
use phplist\Caixa\Functionality\Domain\Model\UserSubscription;
use phplist\Caixa\Functionality\Infrastructure\DB\Connection;
use phplist\Caixa\Functionality\Infrastructure\Shared\PHPList;
use PHPUnit\Framework\TestCase;

/**
 * Class UserSubscriptionDAOTest
 *
 * @package phplist\Caixa\Functionality\Infrastructure\DB\DAO
 */
class UserSubscriptionDAOTest extends TestCase
{
    public function testFindOne()
    {
        $connection = $this->getMockBuilder(Connection::class)
            ->disableOriginalConstructor()
            ->setMethods(['getPDO'])
            ->getMock();

        $connectionPDO = $this->getMockBuilder(\PDO::class)
            ->disableOriginalConstructor()
            ->setMethods(['prepare'])
            ->getMock();

        $connectionPDOStmt = $this->getMockBuilder(\PDOStatement::class)
            ->setMethods(['execute', 'fetch'])
            ->getMock();

        $phpList = $this->getMockBuilder(PHPList::class)
            ->setMethods([])
            ->getMock();

        // expects
        $connection->expects($this->once())
            ->method('getPDO')
            ->willReturn($connectionPDO);

        $connectionPDO->expects($this->once())
            ->method('prepare')
            ->willReturn($connectionPDOStmt);

        $connectionPDOStmt->expects($this->once())
            ->method('execute')
            ->with($this->equalTo([25, 13]));

        $connectionPDOStmt->expects($this->once())
            ->method('fetch')
            ->willReturn([
                'userid' => 25,
                'listid' => 13,
            ]);

        $user = new User();
        $user->setId(25);
        $user->setEmail('client@email.com.br');

        $subscriptionList = new SubscriptionList();
        $subscriptionList->setId(13);
        $subscriptionList->setName('The list name');

        $userSubscriptionDAO = new UserSubscriptionDAO($connection);
        $userSubscriptionDAO->setPhpList($phpList);

        $userSubscription = $userSubscriptionDAO->findOne($user, $subscriptionList);

        $this->assertNotNull($userSubscription);
        $this->assertEquals(UserSubscription::class, get_class($userSubscription));

        $this->assertSame($user, $userSubscription->getUser());
        $this->assertSame($subscriptionList, $userSubscription->getSubscriptionList());
    }

    public function testAdd()
    {
        $connection = $this->getMockBuilder(Connection::class)
            ->disableOriginalConstructor()
            ->setMethods(['getPDO'])
            ->getMock();

        $connectionPDO = $this->getMockBuilder(\PDO::class)
            ->disableOriginalConstructor()
            ->setMethods(['prepare'])
            ->getMock();

        $connectionPDOStmt = $this->getMockBuilder(\PDOStatement::class)
            ->setMethods(['execute'])
            ->getMock();

        $phpList = $this->getMockBuilder(PHPList::class)
            ->setMethods(['getEnteredNow'])
            ->getMock();

        // expects
        $connection->expects($this->once())
            ->method('getPDO')
            ->willReturn($connectionPDO);

        $connectionPDO->expects($this->once())
            ->method('prepare')
            ->willReturn($connectionPDOStmt);

        $connectionPDOStmt->expects($this->once())
            ->method('execute')
            ->with($this->equalTo([
                25,                     // userid
                13,                     // listid
                '2017-11-07 18:28:40',  // entered
            ]));

        $phpList->expects($this->once())
            ->method('getEnteredNow')
            ->willReturn('2017-11-07 18:28:40');

        $user = new User();
        $user->setId(25);
        $user->setEmail('client@email.com.br');

        $subscriptionList = new SubscriptionList();
        $subscriptionList->setId(13);
        $subscriptionList->setName('The list name');

        $userSubscription = new UserSubscription();
        $userSubscription->setUser($user);
        $userSubscription->setSubscriptionList($subscriptionList);

        $userSubscriptionDAO = new UserSubscriptionDAO($connection);
        $userSubscriptionDAO->setPhpList($phpList);

        $userSubscriptionDAO->add($userSubscription);
    }
}
