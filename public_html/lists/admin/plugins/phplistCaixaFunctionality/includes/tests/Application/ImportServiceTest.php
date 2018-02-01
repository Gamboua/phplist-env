<?php

namespace phplist\Caixa\Functionality\Application;

use phplist\Caixa\Functionality\Domain\Model\Caixa\Client;
use phplist\Caixa\Functionality\Domain\Model\Caixa\ClientInvestment;
use phplist\Caixa\Functionality\Domain\Model\Caixa\ClientInvestmentRepository;
use phplist\Caixa\Functionality\Domain\Model\Caixa\InvestmentFund;
use phplist\Caixa\Functionality\Domain\Model\SubscriptionList;
use phplist\Caixa\Functionality\Domain\Model\User;
use phplist\Caixa\Functionality\Domain\Service\ImportClientInvestmentLog;
use phplist\Caixa\Functionality\Domain\Service\ImportInvestmentFundSubscription;
use phplist\Caixa\Functionality\Domain\Service\ImportSubscriptionList;
use phplist\Caixa\Functionality\Domain\Service\ImportUser;
use phplist\Caixa\Functionality\Domain\Service\ImportUserSubscription;
use phplist\Caixa\Functionality\Infrastructure\DB\Connection;
use phplist\Caixa\Functionality\Infrastructure\Shared\PHPListCaixaLogger;
use PHPUnit\Framework\TestCase;

/**
 * Class ImportServiceTest
 *
 * @package phplist\Caixa\Functionality\Domain
 */
class ImportServiceTest extends TestCase
{
    /**
     * @var ClientInvestment
     */
    private $clientInvestment1;

    /**
     * @var ClientInvestment
     */
    private $clientInvestment2;

    /**
     * @var User
     */
    private $user1;

    /**
     * @var User
     */
    private $user2;

    /**
     * @var SubscriptionList
     */
    private $subscriptionList;

    public function setUp()
    {
        $this->clientInvestment1 = new ClientInvestment(
            Client::fromArray([
                'identifier' => '123',
                'email' => 'client123@client.com.br',
                'name' => 'The client 123',
            ]),
            InvestmentFund::fromArray([
                'referenceDate' => '2015-08-01',
                'agencyNumber' => 1941,
                'operationNumber' => 1,
                'accountNumber' => 747681,
                'agencyEmail' => 'agency123@agency.com.br',
                'modalityNumber' => 5930,
            ])
        );

        $this->clientInvestment2 = new ClientInvestment(
            Client::fromArray([
                'identifier' => '456',
                'email' => 'client456@client.com.br',
                'name' => 'The client 456',
            ]),
            InvestmentFund::fromArray([
                'referenceDate' => '2015-08-04',
                'agencyNumber' => 1944,
                'operationNumber' => 4,
                'accountNumber' => 747684,
                'agencyEmail' => 'agency456@agency.com.br',
                'modalityNumber' => 5930,
            ])
        );

        $this->user1 = User::fromArray([
            'id' => 25,
            'email' => 'client123@client.com.br',
        ]);

        $this->user2 = User::fromArray([
            'id' => 26,
            'email' => 'client456@client.com.br',
        ]);

        $this->subscriptionList = SubscriptionList::fromArray([
            'id' => 13,
            'name' => '5930',
            'active' => 0,
            'owner' => 1,
        ]);
    }

    public function testGetAllModalityNumbers()
    {
        $phpListConnection = $this->getMockBuilder(Connection::class)
            ->disableOriginalConstructor()
            ->getMock();

        $clientInvestmentRepository = $this->getMockBuilder(ClientInvestmentRepository::class)
            ->setMethods([
                'findAllByModalityNumber',
                'fromWithinFindAllByModalityNumber',
                'collectModalityNumbers',
            ])
            ->getMock();

        $importUser = $this->getMockBuilder(ImportUser::class)
            ->disableOriginalConstructor()
            ->getMock();

        $importList = $this->getMockBuilder(ImportSubscriptionList::class)
            ->disableOriginalConstructor()
            ->getMock();

        $importUserSubscription = $this->getMockBuilder(ImportUserSubscription::class)
            ->disableOriginalConstructor()
            ->getMock();

        $importInvestmentFundSubscription = $this->getMockBuilder(ImportInvestmentFundSubscription::class)
            ->disableOriginalConstructor()
            ->getMock();

        $importClientInvestmentLog = $this->getMockBuilder(ImportClientInvestmentLog::class)
            ->disableOriginalConstructor()
            ->getMock();

        $logger = $this->getMockBuilder(PHPListCaixaLogger::class)
            ->disableOriginalConstructor()
            ->getMock();

        $importService = new ImportService(
            $importUser,
            $importList,
            $importUserSubscription,
            $importInvestmentFundSubscription,
            $importClientInvestmentLog,
            $clientInvestmentRepository,
            $phpListConnection,
            $logger
        );

        // expects
        $clientInvestmentRepository->expects($this->once())
            ->method('collectModalityNumbers')
            ->willReturn([4930, 4931, 4932]);

        $modalityNumbers = $importService->getAllModalityNumbers();
        $this->assertTrue(is_array($modalityNumbers));
        $this->assertEquals(3, sizeof($modalityNumbers));
        $this->assertTrue(in_array(4930, $modalityNumbers));
        $this->assertTrue(in_array(4931, $modalityNumbers));
        $this->assertTrue(in_array(4932, $modalityNumbers));
    }

    public function testImportByModalityNumberSlowly()
    {
        $phpListConnection = $this->getMockBuilder(Connection::class)
            ->disableOriginalConstructor()
            ->setMethods(['getPDO'])
            ->getMock();

        $connectionPDO = $this->getMockBuilder(\PDO::class)
            ->disableOriginalConstructor()
            ->setMethods(['beginTransaction', 'commit'])
            ->getMock();

        $clientInvestmentRepository = $this->getMockBuilder(ClientInvestmentRepository::class)
            ->setMethods([
                'findAllByModalityNumber',
                'fromWithinFindAllByModalityNumber',
                'collectModalityNumbers',
            ])
            ->getMock();

        $importUser = $this->getMockBuilder(ImportUser::class)
            ->disableOriginalConstructor()
            ->getMock();

        $importList = $this->getMockBuilder(ImportSubscriptionList::class)
            ->disableOriginalConstructor()
            ->getMock();

        $importUserSubscription = $this->getMockBuilder(ImportUserSubscription::class)
            ->disableOriginalConstructor()
            ->getMock();

        $importInvestmentFundSubscription = $this->getMockBuilder(ImportInvestmentFundSubscription::class)
            ->disableOriginalConstructor()
            ->getMock();

        $importClientInvestmentLog = $this->getMockBuilder(ImportClientInvestmentLog::class)
            ->disableOriginalConstructor()
            ->getMock();

        $logger = $this->getMockBuilder(PHPListCaixaLogger::class)
            ->disableOriginalConstructor()
            ->getMock();

        // expects
        $phpListConnection->expects($this->once())
            ->method('getPDO')
            ->willReturn($connectionPDO);

        $connectionPDO->expects($this->once())
            ->method('beginTransaction')
            ->willReturn($connectionPDO);

        $connectionPDO->expects($this->once())
            ->method('commit')
            ->willReturn($connectionPDO);

        $clientInvestmentRepository->expects($this->once())
            ->method('findAllByModalityNumber')
            ->with($this->equalTo(5930))
            ->willReturn([
                $this->clientInvestment1,
                $this->clientInvestment2,
            ]);

        $importUser->expects($this->at(0))
            ->method('findOrSave')
            ->with($this->equalTo($this->clientInvestment1->getClient()))
            ->willReturn($this->user1);

        $importUser->expects($this->at(1))
            ->method('findOrSave')
            ->with($this->equalTo($this->clientInvestment2->getClient()))
            ->willReturn($this->user2);

        $importList->expects($this->at(0))
            ->method('findOrSave')
            ->with($this->equalTo($this->clientInvestment1->getInvestmentFund()))
            ->willReturn($this->subscriptionList);

        $importList->expects($this->at(1))
            ->method('findOrSave')
            ->with($this->equalTo($this->clientInvestment2->getInvestmentFund()))
            ->willReturn($this->subscriptionList);

        $importUserSubscription->expects($this->at(0))
            ->method('findOrSave')
            ->with(
                $this->equalTo($this->user1),
                $this->equalTo($this->subscriptionList)
            );

        $importUserSubscription->expects($this->at(1))
            ->method('findOrSave')
            ->with(
                $this->equalTo($this->user2),
                $this->equalTo($this->subscriptionList)
            );

        $importInvestmentFundSubscription->expects($this->at(0))
            ->method('findOrSave')
            ->with(
                $this->equalTo($this->user1),
                $this->equalTo($this->subscriptionList),
                $this->equalTo($this->clientInvestment1->getInvestmentFund())
            );

        $importInvestmentFundSubscription->expects($this->at(1))
            ->method('findOrSave')
            ->with(
                $this->equalTo($this->user2),
                $this->equalTo($this->subscriptionList),
                $this->equalTo($this->clientInvestment2->getInvestmentFund())
            );

        $importClientInvestmentLog->expects($this->at(0))
            ->method('findOrSave')
            ->with($this->equalTo($this->clientInvestment1));

        $importClientInvestmentLog->expects($this->at(1))
            ->method('findOrSave')
            ->with($this->equalTo($this->clientInvestment2));

        $importService = new ImportService(
            $importUser,
            $importList,
            $importUserSubscription,
            $importInvestmentFundSubscription,
            $importClientInvestmentLog,
            $clientInvestmentRepository,
            $phpListConnection,
            $logger
        );

        $importService->importByModalityNumberSlowly(5930);
    }

    public function testImportByModalityNumberSlowlyWhenNoUserFoundMustAvoidSubscriptions()
    {
        $phpListConnection = $this->getMockBuilder(Connection::class)
            ->disableOriginalConstructor()
            ->getMock();

        $connectionPDO = $this->getMockBuilder(\PDO::class)
            ->disableOriginalConstructor()
            ->getMock();

        $clientInvestmentRepository = $this->getMockBuilder(ClientInvestmentRepository::class)
            ->getMock();

        $importUser = $this->getMockBuilder(ImportUser::class)
            ->disableOriginalConstructor()
            ->getMock();

        $importList = $this->getMockBuilder(ImportSubscriptionList::class)
            ->disableOriginalConstructor()
            ->getMock();

        $importUserSubscription = $this->getMockBuilder(ImportUserSubscription::class)
            ->disableOriginalConstructor()
            ->getMock();

        $importInvestmentFundSubscription = $this->getMockBuilder(ImportInvestmentFundSubscription::class)
            ->disableOriginalConstructor()
            ->getMock();

        $importClientInvestmentLog = $this->getMockBuilder(ImportClientInvestmentLog::class)
            ->disableOriginalConstructor()
            ->getMock();

        $logger = $this->getMockBuilder(PHPListCaixaLogger::class)
            ->disableOriginalConstructor()
            ->getMock();

        // expects
        $phpListConnection->expects($this->once())
            ->method('getPDO')
            ->willReturn($connectionPDO);

        $connectionPDO->expects($this->once())
            ->method('beginTransaction');

        $connectionPDO->expects($this->once())
            ->method('commit');

        $clientInvestmentRepository->expects($this->once())
            ->method('findAllByModalityNumber')
            ->with($this->equalTo(5930))
            ->willReturn([
                $this->clientInvestment1,
            ]);

        $importUser->expects($this->once())
            ->method('findOrSave')
            ->with($this->equalTo($this->clientInvestment1->getClient()))
            ->willReturn(null);

        $importList->expects($this->once())
            ->method('findOrSave')
            ->with($this->equalTo($this->clientInvestment1->getInvestmentFund()))
            ->willReturn(new SubscriptionList());

        $importUserSubscription->expects($this->never())->method('findOrSave');
        $importInvestmentFundSubscription->expects($this->never())->method('findOrSave');
        $importClientInvestmentLog->expects($this->once())->method('findOrSave');

        $importService = new ImportService(
            $importUser,
            $importList,
            $importUserSubscription,
            $importInvestmentFundSubscription,
            $importClientInvestmentLog,
            $clientInvestmentRepository,
            $phpListConnection,
            $logger
        );

        $importService->importByModalityNumberSlowly(5930);
    }

    public function testImportByModalityNumberSlowlyWhenNoListFoundMustAvoidSubscriptions()
    {
        $phpListConnection = $this->getMockBuilder(Connection::class)
            ->disableOriginalConstructor()
            ->getMock();

        $connectionPDO = $this->getMockBuilder(\PDO::class)
            ->disableOriginalConstructor()
            ->getMock();

        $clientInvestmentRepository = $this->getMockBuilder(ClientInvestmentRepository::class)
            ->getMock();

        $importUser = $this->getMockBuilder(ImportUser::class)
            ->disableOriginalConstructor()
            ->getMock();

        $importList = $this->getMockBuilder(ImportSubscriptionList::class)
            ->disableOriginalConstructor()
            ->getMock();

        $importUserSubscription = $this->getMockBuilder(ImportUserSubscription::class)
            ->disableOriginalConstructor()
            ->getMock();

        $importInvestmentFundSubscription = $this->getMockBuilder(ImportInvestmentFundSubscription::class)
            ->disableOriginalConstructor()
            ->getMock();

        $importClientInvestmentLog = $this->getMockBuilder(ImportClientInvestmentLog::class)
            ->disableOriginalConstructor()
            ->getMock();

        $logger = $this->getMockBuilder(PHPListCaixaLogger::class)
            ->disableOriginalConstructor()
            ->getMock();

        // expects
        $phpListConnection->expects($this->once())
            ->method('getPDO')
            ->willReturn($connectionPDO);

        $connectionPDO->expects($this->once())
            ->method('beginTransaction');

        $connectionPDO->expects($this->once())
            ->method('commit');

        $clientInvestmentRepository->expects($this->once())
            ->method('findAllByModalityNumber')
            ->with($this->equalTo(5930))
            ->willReturn([
                $this->clientInvestment1,
            ]);

        $importUser->expects($this->once())
            ->method('findOrSave')
            ->with($this->equalTo($this->clientInvestment1->getClient()))
            ->willReturn(new User());

        $importList->expects($this->once())
            ->method('findOrSave')
            ->with($this->equalTo($this->clientInvestment1->getInvestmentFund()))
            ->willReturn(null);

        $importUserSubscription->expects($this->never())->method('findOrSave');
        $importInvestmentFundSubscription->expects($this->never())->method('findOrSave');
        $importClientInvestmentLog->expects($this->once())->method('findOrSave');

        $importService = new ImportService(
            $importUser,
            $importList,
            $importUserSubscription,
            $importInvestmentFundSubscription,
            $importClientInvestmentLog,
            $clientInvestmentRepository,
            $phpListConnection,
            $logger
        );

        $importService->importByModalityNumberSlowly(5930);
    }
}
