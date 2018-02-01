<?php

namespace phplist\Caixa\Functionality\Domain\Service;

use phplist\Caixa\Functionality\Domain\Model\Caixa\Client;
use phplist\Caixa\Functionality\Domain\Model\Caixa\ClientInvestment;
use phplist\Caixa\Functionality\Domain\Model\Caixa\InvestmentFund;
use phplist\Caixa\Functionality\Domain\Model\ClientInvestmentLog;
use phplist\Caixa\Functionality\Domain\Model\ClientInvestmentLogRepository;
use PHPUnit\Framework\TestCase;

/**
 * Class ImportClientInvestmentLogTest
 *
 * @package phplist\Caixa\Functionality\Domain\Service
 */
class ImportClientInvestmentLogTest extends TestCase
{
    /**
     * @var ClientInvestmentLog
     */
    private $toBeCreated;

    /**
     * @var ClientInvestmentLog
     */
    private $toBeUpdated;

    /**
     * @var ClientInvestmentLog
     */
    private $toBeFound;

    public function setUp()
    {
        $this->toBeCreated = ClientInvestmentLog::fromArray([
            'identifier' => '123456',
            'name' => 'the client name',
            'referenceDate' => '2015-08-01',
            'agencyNumber' => 1941,
            'operationNumber' => 1,
            'accountNumber' => 747681,
            'agencyEmail' => 'agency@agency.com.br',
            'modalityNumber' => 4930,
            'clientEmail' => null,
        ]);

        $this->toBeUpdated = ClientInvestmentLog::fromArray([
            'identifier' => '123456',
            'name' => 'the updated client name',
            'referenceDate' => '2015-08-02',
            'agencyNumber' => 1942,
            'operationNumber' => 2,
            'accountNumber' => 747682,
            'agencyEmail' => 'agency2@agency.com.br',
            'modalityNumber' => 4930,
            'clientEmail' => null,
        ]);

        $this->toBeFound = ClientInvestmentLog::fromArray([
            'identifier' => '123456',
            'name' => 'the client name',
            'referenceDate' => '2015-08-01',
            'agencyNumber' => 1941,
            'operationNumber' => 1,
            'accountNumber' => 747681,
            'agencyEmail' => 'agency@agency.com.br',
            'modalityNumber' => 4930,
            'clientEmail' => null,
        ]);
    }

    public function testFindOrSaveWhenClientExistsAndHasEmailMustRemoveAndReturnNull()
    {
        $clientInvestmentLogRepository = $this->getMockBuilder(ClientInvestmentLogRepository::class)->getMock();
        $importClientInvestmentLog = new ImportClientInvestmentLog($clientInvestmentLogRepository);

        // expects
        $clientInvestmentLogRepository->expects($this->once())
            ->method('findOne')
            ->with($this->equalTo('123456', 4930))
            ->willReturn($this->toBeFound);

        $clientInvestmentLogRepository->expects($this->once())
            ->method('remove')
            ->with($this->toBeFound);

        $clientInvestmentLogRepository->expects($this->never())->method('add');
        $clientInvestmentLogRepository->expects($this->never())->method('merge');

        $client = Client::fromArray([
            'identifier' => '123456',
            'name' => 'the client name',
            'email' => 'client@client.com.br',
        ]);

        $investmentFund = InvestmentFund::fromArray([
            'referenceDate' => '2015-08-01',
            'agencyNumber' => 1941,
            'operationNumber' => 1,
            'accountNumber' => 747681,
            'agencyEmail' => 'agency@agency.com.br',
            'modalityNumber' => 4930,
        ]);

        $clientInvestment = new ClientInvestment($client, $investmentFund);
        $this->assertNull($importClientInvestmentLog->findOrSave($clientInvestment));
    }

    public function testFindOrSaveWhenClientExistsAndHasNoEmailMustUpdate()
    {
        $clientInvestmentLogRepository = $this->getMockBuilder(ClientInvestmentLogRepository::class)->getMock();
        $importClientInvestmentLog = new ImportClientInvestmentLog($clientInvestmentLogRepository);

        // expects
        $clientInvestmentLogRepository->expects($this->once())
            ->method('findOne')
            ->with($this->equalTo('123456', 4930))
            ->willReturn($this->toBeFound);

        $clientInvestmentLogRepository->expects($this->once())
            ->method('merge')
            ->with($this->toBeUpdated);

        $clientInvestmentLogRepository->expects($this->never())->method('add');
        $clientInvestmentLogRepository->expects($this->never())->method('remove');

        $client = Client::fromArray([
            'identifier' => '123456',
            'name' => 'the updated client name',
            'email' => null,
        ]);

        $investmentFund = InvestmentFund::fromArray([
            'referenceDate' => '2015-08-02',
            'agencyNumber' => 1942,
            'operationNumber' => 2,
            'accountNumber' => 747682,
            'agencyEmail' => 'agency2@agency.com.br',
            'modalityNumber' => 4930,
        ]);

        $clientInvestment = new ClientInvestment($client, $investmentFund);
        $clientInvestmentLog = $importClientInvestmentLog->findOrSave($clientInvestment);

        $this->assertNotNull($clientInvestmentLog);
        $this->assertEquals(ClientInvestmentLog::class, get_class($clientInvestmentLog));
        $this->assertEquals($this->toBeUpdated, $clientInvestmentLog);
    }

    public function testFindOrSaveWhenClientNotExistsAndHasNoEmailMustCreate()
    {
        $clientInvestmentLogRepository = $this->getMockBuilder(ClientInvestmentLogRepository::class)->getMock();
        $importClientInvestmentLog = new ImportClientInvestmentLog($clientInvestmentLogRepository);

        // expects
        $clientInvestmentLogRepository->expects($this->once())
            ->method('findOne')
            ->with($this->equalTo('123456', 4930))
            ->willReturn(null);

        $clientInvestmentLogRepository->expects($this->once())
            ->method('add')
            ->with($this->toBeCreated);

        $clientInvestmentLogRepository->expects($this->never())->method('merge');
        $clientInvestmentLogRepository->expects($this->never())->method('remove');

        $client = Client::fromArray([
            'identifier' => '123456',
            'name' => 'the client name',
            'email' => null,
        ]);

        $investmentFund = InvestmentFund::fromArray([
            'referenceDate' => '2015-08-01',
            'agencyNumber' => 1941,
            'operationNumber' => 1,
            'accountNumber' => 747681,
            'agencyEmail' => 'agency@agency.com.br',
            'modalityNumber' => 4930,
        ]);

        $clientInvestment = new ClientInvestment($client, $investmentFund);
        $clientInvestmentLog = $importClientInvestmentLog->findOrSave($clientInvestment);

        $this->assertNotNull($clientInvestmentLog);
        $this->assertEquals(ClientInvestmentLog::class, get_class($clientInvestmentLog));
        $this->assertEquals($this->toBeCreated, $clientInvestmentLog);
    }
}
