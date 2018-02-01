<?php

namespace phplist\Caixa\Functionality\Infrastructure\DB\DAO;

use phplist\Caixa\Functionality\Infrastructure\DB\Connection;
use PHPUnit\Framework\TestCase;

/**
 * Class CaixaDAOTest
 *
 * @package phplist\Caixa\Functionality\Infrastructure\DB\DAO
 */
class CaixaDAOTest extends TestCase
{
    public function testGetTables()
    {
        $caixaDAOClazz = new \ReflectionClass(CaixaDAO::class);
        $getTablesMethod = $caixaDAOClazz->getMethod('getTables');
        $getTablesMethod->setAccessible(true);

        $connection = $this->getMockBuilder(Connection::class)
            ->disableOriginalConstructor()
            ->getMock();

        $caixaDAO = new CaixaDAO($connection);
        $getTablesResult = $getTablesMethod->invoke($caixaDAO);

        $this->assertEquals(1, sizeof($getTablesResult));
        $this->assertTrue(array_key_exists('fundo_investimento', $getTablesResult));
        $this->assertEquals('lissm001.listb001_fundo_investimento', $getTablesResult['fundo_investimento']);
    }

    public function testGetTablePrefix()
    {
        $caixaDAOClazz = new \ReflectionClass(CaixaDAO::class);
        $getTablePrefixMethod = $caixaDAOClazz->getMethod('getTablePrefix');
        $getTablePrefixMethod->setAccessible(true);

        $connection = $this->getMockBuilder(Connection::class)
            ->disableOriginalConstructor()
            ->getMock();

        $caixaDAO = new CaixaDAO($connection);
        $this->assertSame('', $getTablePrefixMethod->invoke($caixaDAO));
    }

    public function testFindAllInvestmentFunds()
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
                ['nu_modalidade' => 7],
                ['nu_modalidade' => 14],
                ['nu_modalidade' => 21],
            ]);

        $caixaDAO = new CaixaDAO($connection);
        $investmentFunds = $caixaDAO->findAllInvestmentFunds();

        $this->assertTrue(is_array($investmentFunds));
        $this->assertEquals(3, sizeof($investmentFunds));
        $this->assertTrue(in_array(7, $investmentFunds));
        $this->assertTrue(in_array(14, $investmentFunds));
        $this->assertTrue(in_array(21, $investmentFunds));
    }

    public function testFindAllFundNamesWhenNoneWasFoundMustReturnAnEmptyArray()
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
            ->willReturn([]);

        $caixaDAO = new CaixaDAO($connection);
        $fundNames = $caixaDAO->findAllInvestmentFunds();

        $this->assertTrue(is_array($fundNames));
        $this->assertEquals(0, sizeof($fundNames));
    }
}
