<?php

namespace phplist\Caixa\Functionality\Infrastructure\DB\DAO;

use phplist\Caixa\Functionality\Domain\Model\ClientInvestmentLog;
use phplist\Caixa\Functionality\Infrastructure\DB\Connection;
use phplist\Caixa\Functionality\Infrastructure\Shared\PHPList;
use PHPUnit\Framework\TestCase;

/**
 * Class ClientInvestmentLogDAOTest
 *
 * @package phplist\Caixa\Functionality\Infrastructure\DB\DAO
 */
class ClientInvestmentLogDAOTest extends TestCase
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
            ->with($this->equalTo(['00028430892801', 5930]));

        $connectionPDOStmt->expects($this->once())
            ->method('fetch')
            ->willReturn([
                'co_identificador_cliente' => '00028430892801',
                'no_cliente' => 'Nome do Cliente 1',
                'dt_referencia' => '2015-08-01',
                'nu_agencia' => 1941,
                'nu_operacao' => 1,
                'nu_conta' => 747681,
                'de_email_agencia' => 'agencia1@agencia.com.br',
                'nu_modalidade' => 5930,
                'de_email_cliente' => null,
            ]);

        $clientInvestmentLogDAO = new ClientInvestmentLogDAO($connection);
        $clientInvestmentLogDAO->setPhpList($phpList);

        $clientInvestmentLog = $clientInvestmentLogDAO->findOne('00028430892801', 5930);

        $this->assertNotNull($clientInvestmentLog);
        $this->assertEquals(ClientInvestmentLog::class, get_class($clientInvestmentLog));

        $this->assertEquals('00028430892801', $clientInvestmentLog->getIdentifier());
        $this->assertEquals('Nome do Cliente 1', $clientInvestmentLog->getName());
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
            ->with($this->equalTo([
                '00028430892801',           // co_identificador_cliente
                'Nome do Cliente 1',        // no_cliente
                '2015-08-01',               // dt_referencia
                1941,                       // nu_agencia
                1,                          // nu_operacao
                747681,                     // nu_conta
                'agencia1@agencia.com.br',  // de_email_agencia
                5930,                       // nu_modalidade
                null,                       // de_email_cliente
            ]));

        $clientInvestmentLog = new ClientInvestmentLog();
        $clientInvestmentLog->setIdentifier('00028430892801');
        $clientInvestmentLog->setName('Nome do Cliente 1');
        $clientInvestmentLog->setReferenceDate('2015-08-01');
        $clientInvestmentLog->setAgencyNumber(1941);
        $clientInvestmentLog->setOperationNumber(1);
        $clientInvestmentLog->setAccountNumber(747681);
        $clientInvestmentLog->setAgencyEmail('agencia1@agencia.com.br');
        $clientInvestmentLog->setModalityNumber(5930);

        $clientInvestmentLogDAO = new ClientInvestmentLogDAO($connection);
        $clientInvestmentLogDAO->setPhpList($phpList);

        $clientInvestmentLogDAO->add($clientInvestmentLog);

        $this->assertEquals('00028430892801', $clientInvestmentLog->getIdentifier());
        $this->assertEquals('Nome do Cliente 1', $clientInvestmentLog->getName());
        $this->assertEquals('2015-08-01', $clientInvestmentLog->getReferenceDate());
        $this->assertEquals(1941, $clientInvestmentLog->getAgencyNumber());
        $this->assertEquals(1, $clientInvestmentLog->getOperationNumber());
        $this->assertEquals(747681, $clientInvestmentLog->getAccountNumber());
        $this->assertEquals('agencia1@agencia.com.br', $clientInvestmentLog->getAgencyEmail());
        $this->assertEquals(5930, $clientInvestmentLog->getModalityNumber());
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
            ->with($this->equalTo([
                'Nome do Cliente 1',        // no_cliente
                '2015-08-01',               // dt_referencia
                1941,                       // nu_agencia
                1,                          // nu_operacao
                747681,                     // nu_conta
                'agencia1@agencia.com.br',  // de_email_agencia
                null,                       // de_email_cliente
                '00028430892801',           // co_identificador_cliente
                5930,                       // nu_modalidade
            ]));

        $clientInvestmentLog = new ClientInvestmentLog();
        $clientInvestmentLog->setIdentifier('00028430892801');
        $clientInvestmentLog->setName('Nome do Cliente 1');
        $clientInvestmentLog->setReferenceDate('2015-08-01');
        $clientInvestmentLog->setAgencyNumber(1941);
        $clientInvestmentLog->setOperationNumber(1);
        $clientInvestmentLog->setAccountNumber(747681);
        $clientInvestmentLog->setAgencyEmail('agencia1@agencia.com.br');
        $clientInvestmentLog->setModalityNumber(5930);

        $clientInvestmentLogDAO = new ClientInvestmentLogDAO($connection);
        $clientInvestmentLogDAO->setPhpList($phpList);

        $clientInvestmentLogDAO->merge($clientInvestmentLog);
    }

    public function testRemove()
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
            ->with($this->equalTo(['00028430892801', 5930]));

        $clientInvestmentLog = new ClientInvestmentLog();
        $clientInvestmentLog->setIdentifier('00028430892801');
        $clientInvestmentLog->setModalityNumber(5930);

        $clientInvestmentLogDAO = new ClientInvestmentLogDAO($connection);
        $clientInvestmentLogDAO->setPhpList($phpList);

        $clientInvestmentLogDAO->remove($clientInvestmentLog);
    }
}
