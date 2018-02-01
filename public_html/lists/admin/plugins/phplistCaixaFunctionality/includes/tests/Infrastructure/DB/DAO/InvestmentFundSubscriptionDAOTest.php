<?php

namespace phplist\Caixa\Functionality\Infrastructure\DB\DAO;

use phplist\Caixa\Functionality\Domain\Model\InvestmentFundSubscription;
use phplist\Caixa\Functionality\Domain\Model\SubscriptionList;
use phplist\Caixa\Functionality\Domain\Model\User;
use phplist\Caixa\Functionality\Infrastructure\DB\Connection;
use PHPUnit\Framework\TestCase;

/**
 * Class InvestmentFundSubscriptionDAOTest
 *
 * @package phplist\Caixa\Functionality\Infrastructure\DB\DAO
 */
class InvestmentFundSubscriptionDAOTest extends TestCase
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
                'reference_date' => '2015-08-01',
                'agency_number' => 1941,
                'operation_number' => 1,
                'account_number' => 747681,
                'agency_email' => 'agency@agency.com.br',
                'modality_number' => 5930,
            ]);

        $user = new User();
        $user->setId(25);
        $user->setEmail('client@email.com.br');

        $subscriptionList = new SubscriptionList();
        $subscriptionList->setId(13);
        $subscriptionList->setName('The list name');

        $investmentFundSubscriptionDAO = new InvestmentFundSubscriptionDAO($connection);
        $investmentFundSubscription = $investmentFundSubscriptionDAO->findOne($user, $subscriptionList);

        $this->assertNotNull($investmentFundSubscription);
        $this->assertEquals(InvestmentFundSubscription::class, get_class($investmentFundSubscription));

        $this->assertSame($user, $investmentFundSubscription->getUser());
        $this->assertSame($subscriptionList, $investmentFundSubscription->getSubscriptionList());
        $this->assertEquals('2015-08-01', $investmentFundSubscription->getReferenceDate());
        $this->assertEquals(1941, $investmentFundSubscription->getAgencyNumber());
        $this->assertEquals(1, $investmentFundSubscription->getOperationNumber());
        $this->assertEquals(747681, $investmentFundSubscription->getAccountNumber());
        $this->assertEquals('agency@agency.com.br', $investmentFundSubscription->getAgencyEmail());
        $this->assertEquals(5930, $investmentFundSubscription->getModalityNumber());
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
                '2015-08-01',           // reference_date
                1941,                   // agency_number
                1,                      // operation_number
                747681,                 // account_number
                'agency@agency.com.br', // agency_email
                5930,                   // modality_number
            ]));

        $user = new User();
        $user->setId(25);
        $user->setEmail('client@email.com.br');

        $subscriptionList = new SubscriptionList();
        $subscriptionList->setId(13);
        $subscriptionList->setName('The list name');

        $investmentFundSubscription = new InvestmentFundSubscription();
        $investmentFundSubscription->setUser($user);
        $investmentFundSubscription->setSubscriptionList($subscriptionList);

        $investmentFundSubscription->setReferenceDate('2015-08-01');
        $investmentFundSubscription->setAgencyNumber(1941);
        $investmentFundSubscription->setOperationNumber(1);
        $investmentFundSubscription->setAccountNumber(747681);
        $investmentFundSubscription->setAgencyEmail('agency@agency.com.br');
        $investmentFundSubscription->setModalityNumber(5930);

        $subscriptionDAO = new InvestmentFundSubscriptionDAO($connection);
        $subscriptionDAO->add($investmentFundSubscription);
    }

    public function testMerge()
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
                '2015-08-01',           // reference_date
                1941,                   // agency_number
                1,                      // operation_number
                747681,                 // account_number
                'agency@agency.com.br', // agency_email
                5930,                   // modality_number
                25,                     // userid
                13,                     // listid
            ]));

        $user = new User();
        $user->setId(25);
        $user->setEmail('client@email.com.br');

        $subscriptionList = new SubscriptionList();
        $subscriptionList->setId(13);
        $subscriptionList->setName('The list name');

        $investmentFundSubscription = new InvestmentFundSubscription();
        $investmentFundSubscription->setUser($user);
        $investmentFundSubscription->setSubscriptionList($subscriptionList);

        $investmentFundSubscription->setReferenceDate('2015-08-01');
        $investmentFundSubscription->setAgencyNumber(1941);
        $investmentFundSubscription->setOperationNumber(1);
        $investmentFundSubscription->setAccountNumber(747681);
        $investmentFundSubscription->setAgencyEmail('agency@agency.com.br');
        $investmentFundSubscription->setModalityNumber(5930);

        $subscriptionDAO = new InvestmentFundSubscriptionDAO($connection);
        $subscriptionDAO->merge($investmentFundSubscription);
    }
}
