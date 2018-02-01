<?php

namespace phplist\Caixa\Functionality\Infrastructure\DB\DAO;

use phplist\Caixa\Functionality\Infrastructure\DB\Connection;

use PHPUnit\Framework\TestCase;

/**
 * Class PHPListDAOTest
 *
 * @package phplist\Caixa\Functionality\Infrastructure\DB\DAO
 */
class PHPListDAOTest extends TestCase
{
    public function testDeleteUserMessageByNotViewed()
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
            ->with($this->equalTo([25]));

        $phpListDAO = new PHPListDAO($connection);
        $phpListDAO->deleteUserMessageByNotViewed(25);
    }
}
