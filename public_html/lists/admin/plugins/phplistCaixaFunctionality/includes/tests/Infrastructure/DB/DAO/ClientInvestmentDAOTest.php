<?php

namespace phplist\Caixa\Functionality\Infrastructure\DB\DAO;

use phplist\Caixa\Functionality\Domain\Model\Caixa\ClientInvestment;
use phplist\Caixa\Functionality\Infrastructure\DB\Connection;
use phplist\Caixa\Functionality\Infrastructure\Shared\PHPListCaixaLogger;
use PHPUnit\Framework\TestCase;

/**
 * Class ClientInvestmentDAOTest
 *
 * @package phplist\Caixa\Functionality\Infrastructure\DB\DAO
 */
class ClientInvestmentDAOTest extends TestCase
{
    public function testGetTables()
    {
        $caixaDAOClazz = new \ReflectionClass(ClientInvestmentDAO::class);
        $getTablesMethod = $caixaDAOClazz->getMethod('getTables');
        $getTablesMethod->setAccessible(true);

        $connection = $this->getMockBuilder(Connection::class)
            ->disableOriginalConstructor()
            ->getMock();

        $clientInvestmentDAO = new ClientInvestmentDAO($connection);
        $getTablesResult = $getTablesMethod->invoke($clientInvestmentDAO);

        $this->assertEquals(1, sizeof($getTablesResult));
        $this->assertTrue(array_key_exists('fundo_investimento', $getTablesResult));
        $this->assertEquals('lissm001.listb001_fundo_investimento', $getTablesResult['fundo_investimento']);
    }

    public function testGetTablePrefix()
    {
        $caixaDAOClazz = new \ReflectionClass(ClientInvestmentDAO::class);
        $getTablePrefixMethod = $caixaDAOClazz->getMethod('getTablePrefix');
        $getTablePrefixMethod->setAccessible(true);

        $connection = $this->getMockBuilder(Connection::class)
            ->disableOriginalConstructor()
            ->getMock();

        $clientInvestmentDAO = new ClientInvestmentDAO($connection);
        $this->assertSame('', $getTablePrefixMethod->invoke($clientInvestmentDAO));
    }

    public function testFindAllByModalityNumber()
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
            ->setMethods(['execute', 'fetchAll'])
            ->getMock();

        $logger = $this->getMockBuilder(PHPListCaixaLogger::class)
            ->disableOriginalConstructor()
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
            ->with($this->equalTo([5930]));

        $connectionPDOStmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                [
                    'co_identificador_cliente' => '00028430892801',
                    'de_email_cliente' => 'cliente1@email.com.br',
                    'no_cliente' => 'Nome do Cliente 1',
                    'dt_referencia' => '2015-08-01',
                    'nu_agencia' => 1941,
                    'nu_operacao' => 1,
                    'nu_conta' => 747681,
                    'de_email_agencia' => 'agencia1@agencia.com.br',
                    'nu_modalidade' => 5930,
                ],
                [
                    'co_identificador_cliente' => '00028430892802',
                    'de_email_cliente' => 'cliente2@email.com.br',
                    'no_cliente' => 'Nome do Cliente 2',
                    'dt_referencia' => '2015-08-02',
                    'nu_agencia' => 1942,
                    'nu_operacao' => 2,
                    'nu_conta' => 747682,
                    'de_email_agencia' => 'agencia2@agencia.com.br',
                    'nu_modalidade' => 5930,
                ],
            ]);


        $clientInvestmentDAO = new ClientInvestmentDAO($connection);
        $clientInvestmentDAO->setPhpListCaixaLogger($logger);

        $clientInvestments = $clientInvestmentDAO->findAllByModalityNumber(5930);

        $this->assertTrue(is_array($clientInvestments));
        $this->assertEquals(2, sizeof($clientInvestments));

        $this->assertEquals('00028430892801', $clientInvestments[0]->getClient()->getIdentifier());
        $this->assertEquals('Nome do Cliente 1', $clientInvestments[0]->getClient()->getName());
        $this->assertEquals('cliente1@email.com.br', $clientInvestments[0]->getClient()->getEmail());
        $this->assertEquals('2015-08-01', $clientInvestments[0]->getInvestmentFund()->getReferenceDate());
        $this->assertEquals(1941, $clientInvestments[0]->getInvestmentFund()->getAgencyNumber());
        $this->assertEquals(1, $clientInvestments[0]->getInvestmentFund()->getOperationNumber());
        $this->assertEquals(747681, $clientInvestments[0]->getInvestmentFund()->getAccountNumber());
        $this->assertEquals('agencia1@agencia.com.br', $clientInvestments[0]->getInvestmentFund()->getAgencyEmail());
        $this->assertEquals(5930, $clientInvestments[0]->getInvestmentFund()->getModalityNumber());

        $this->assertEquals('00028430892802', $clientInvestments[1]->getClient()->getIdentifier());
        $this->assertEquals('Nome do Cliente 2', $clientInvestments[1]->getClient()->getName());
        $this->assertEquals('cliente2@email.com.br', $clientInvestments[1]->getClient()->getEmail());
        $this->assertEquals('2015-08-02', $clientInvestments[1]->getInvestmentFund()->getReferenceDate());
        $this->assertEquals(1942, $clientInvestments[1]->getInvestmentFund()->getAgencyNumber());
        $this->assertEquals(2, $clientInvestments[1]->getInvestmentFund()->getOperationNumber());
        $this->assertEquals(747682, $clientInvestments[1]->getInvestmentFund()->getAccountNumber());
        $this->assertEquals('agencia2@agencia.com.br', $clientInvestments[1]->getInvestmentFund()->getAgencyEmail());
        $this->assertEquals(5930, $clientInvestments[1]->getInvestmentFund()->getModalityNumber());
    }

    public function testFromWithinFindAllByModalityNumber()
    {
        $connection = $this->getMockBuilder(Connection::class)
            ->disableOriginalConstructor()
            ->getMock();

        $connectionPDO = $this->getMockBuilder(\PDO::class)
            ->disableOriginalConstructor()
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
            ->with($this->equalTo([5930]));

        $connectionPDOStmt->expects($this->at(1))
            ->method('fetch')
            ->with($this->equalTo(\PDO::FETCH_ASSOC))
            ->willReturn([
                'co_identificador_cliente' => '00028430892801',
                'de_email_cliente' => 'cliente1@email.com.br',
                'no_cliente' => 'Nome do Cliente 1',
                'dt_referencia' => '2015-08-01',
                'nu_agencia' => 1941,
                'nu_operacao' => 1,
                'nu_conta' => 747681,
                'de_email_agencia' => 'agencia1@agencia.com.br',
                'nu_modalidade' => 5930,
            ]);

        $connectionPDOStmt->expects($this->at(2))
            ->method('fetch')
            ->with($this->equalTo(\PDO::FETCH_ASSOC))
            ->willReturn(false);

        $clientInvestments = [];
        $callback = function (ClientInvestment $clientInvestment) use (&$clientInvestments) {
            $clientInvestments[] = $clientInvestment;
        };

        $clientInvestmentDAO = new ClientInvestmentDAO($connection);
        $clientInvestmentDAO->fromWithinFindAllByModalityNumber(5930, $callback);

        $this->assertTrue(is_array($clientInvestments));
        $this->assertEquals(1, sizeof($clientInvestments));

        $this->assertEquals('00028430892801', $clientInvestments[0]->getClient()->getIdentifier());
        $this->assertEquals('Nome do Cliente 1', $clientInvestments[0]->getClient()->getName());
        $this->assertEquals('cliente1@email.com.br', $clientInvestments[0]->getClient()->getEmail());
        $this->assertEquals('2015-08-01', $clientInvestments[0]->getInvestmentFund()->getReferenceDate());
        $this->assertEquals(1941, $clientInvestments[0]->getInvestmentFund()->getAgencyNumber());
        $this->assertEquals(1, $clientInvestments[0]->getInvestmentFund()->getOperationNumber());
        $this->assertEquals(747681, $clientInvestments[0]->getInvestmentFund()->getAccountNumber());
        $this->assertEquals('agencia1@agencia.com.br', $clientInvestments[0]->getInvestmentFund()->getAgencyEmail());
        $this->assertEquals(5930, $clientInvestments[0]->getInvestmentFund()->getModalityNumber());
    }

    public function testCollectModalityNumbers()
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
            ->setMethods(['execute', 'fetchAll'])
            ->getMock();

        // expects
        $connection->expects($this->once())
            ->method('getPDO')
            ->willReturn($connectionPDO);

        $connectionPDO->expects($this->once())
            ->method('prepare')
            ->willReturn($connectionPDOStmt);

        $connectionPDOStmt->expects($this->once())
            ->method('execute');

        $connectionPDOStmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['nu_modalidade' => 5930],
                ['nu_modalidade' => 5931],
                ['nu_modalidade' => 5932],
            ]);

        $clientInvestmentDAO = new ClientInvestmentDAO($connection);
        $modalityNumbers = $clientInvestmentDAO->collectModalityNumbers();

        $this->assertTrue(is_array($modalityNumbers));
        $this->assertEquals(3, sizeof($modalityNumbers));

        $this->assertTrue(in_array(5930, $modalityNumbers));
        $this->assertTrue(in_array(5931, $modalityNumbers));
        $this->assertTrue(in_array(5932, $modalityNumbers));
    }
}
