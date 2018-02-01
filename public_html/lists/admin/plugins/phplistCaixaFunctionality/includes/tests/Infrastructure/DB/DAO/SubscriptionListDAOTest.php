<?php

namespace phplist\Caixa\Functionality\Infrastructure\DB\DAO;

use phplist\Caixa\Functionality\Domain\Model\SubscriptionList;
use phplist\Caixa\Functionality\Infrastructure\DB\Connection;
use phplist\Caixa\Functionality\Infrastructure\Shared\PHPList;
use PHPUnit\Framework\TestCase;

/**
 * Class SubscriptionListDAOTest
 *
 * @package phplist\Caixa\Functionality\Infrastructure\DB\DAO
 */
class SubscriptionListDAOTest extends TestCase
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
            ->with($this->equalTo([25]));

        $connectionPDOStmt->expects($this->once())
            ->method('fetch')
            ->willReturn([
                'id' => 25,
                'name' => 'The list name',
                'active' => 0,
                'owner' => 1,
            ]);

        $subscriptionListDAO = new SubscriptionListDAO($connection);
        $subscriptionListDAO->setPhpList($phpList);

        $subscriptionList = $subscriptionListDAO->findOne(25);

        $this->assertNotNull($subscriptionList);
        $this->assertEquals(SubscriptionList::class, get_class($subscriptionList));

        $this->assertEquals(25, $subscriptionList->getId());
        $this->assertEquals('The list name', $subscriptionList->getName());
    }

    public function testFindOneByName()
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
            ->with($this->equalTo(['The list name']));

        $connectionPDOStmt->expects($this->once())
            ->method('fetch')
            ->willReturn([
                'id' => 25,
                'name' => 'The list name',
                'active' => 0,
                'owner' => 1,
            ]);

        $subscriptionListDAO = new SubscriptionListDAO($connection);
        $subscriptionListDAO->setPhpList($phpList);

        $subscriptionList = $subscriptionListDAO->findOneByName('The list name');

        $this->assertNotNull($subscriptionList);
        $this->assertEquals(SubscriptionList::class, get_class($subscriptionList));

        $this->assertEquals(25, $subscriptionList->getId());
        $this->assertEquals('The list name', $subscriptionList->getName());
    }

    public function testAdd()
    {
        $connection = $this->getMockBuilder(Connection::class)
            ->disableOriginalConstructor()
            ->setMethods(['getPDO'])
            ->getMock();

        $connectionPDO = $this->getMockBuilder(\PDO::class)
            ->disableOriginalConstructor()
            ->setMethods(['prepare', 'lastInsertId'])
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

        $connectionPDO->expects($this->once())
            ->method('lastInsertId')
            ->willReturn(25);

        $connectionPDOStmt->expects($this->once())
            ->method('execute')
            ->with($this->equalTo([
                'The list name',        // name
                '2017-11-07 18:28:40',  // entered
                0, // active
                1, // owner
            ]));

        $phpList->expects($this->once())
            ->method('getEnteredNow')
            ->willReturn('2017-11-07 18:28:40');

        $subscriptionList = new SubscriptionList();
        $subscriptionList->setName('The list name');
        $subscriptionList->setActive(0);
        $subscriptionList->setOwner(1);

        $subscriptionListDAO = new SubscriptionListDAO($connection);
        $subscriptionListDAO->setPhpList($phpList);

        $subscriptionListDAO->add($subscriptionList);

        $this->assertEquals(25, $subscriptionList->getId());
    }
}
