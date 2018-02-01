<?php

namespace phplist\Caixa\Functionality\Infrastructure\DB\DAO;

use phplist\Caixa\Functionality\Domain\Model\User;
use phplist\Caixa\Functionality\Domain\Model\UserAttribute;
use phplist\Caixa\Functionality\Infrastructure\DB\Connection;
use PHPUnit\Framework\TestCase;

/**
 * Class UserAttributeDAOTest
 *
 * @package phplist\Caixa\Functionality\Infrastructure\DB\DAO
 */
class UserAttributeDAOTest extends TestCase
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
            ->with($this->equalTo([25, UserAttribute::CLIENT_IDENTIFIER]));

        $connectionPDOStmt->expects($this->once())
            ->method('fetch')
            ->willReturn([
                'attributeid' => UserAttribute::CLIENT_IDENTIFIER,
                'userid' => 25,
                'value' => '03853896000735',
            ]);

        $user = new User();
        $user->setId(25);
        $user->setEmail('client@email.com.br');

        $userAttributeDAO = new UserAttributeDAO($connection);
        $userAttribute = $userAttributeDAO->findOne($user, UserAttribute::CLIENT_IDENTIFIER);

        $this->assertNotNull($userAttribute);
        $this->assertSame($user, $userAttribute->getUser());
        $this->assertEquals(UserAttribute::CLIENT_IDENTIFIER, $userAttribute->getAttributeId());
        $this->assertEquals('03853896000735', $userAttribute->getValue());
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
                25,
                UserAttribute::CLIENT_IDENTIFIER,
                '03853896000735',
            ]));

        $user = new User();
        $user->setId(25);
        $user->setEmail('client@email.com.br');

        $userAttribute = new UserAttribute();
        $userAttribute->setUser($user);
        $userAttribute->setAttributeId(UserAttribute::CLIENT_IDENTIFIER);
        $userAttribute->setValue('03853896000735');

        $userAttributeDAO = new UserAttributeDAO($connection);
        $userAttributeDAO->add($userAttribute);
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
                '03853896000735',
                25,
                UserAttribute::CLIENT_IDENTIFIER,
            ]));

        $user = new User();
        $user->setId(25);
        $user->setEmail('client@email.com.br');

        $userAttribute = new UserAttribute();
        $userAttribute->setUser($user);
        $userAttribute->setAttributeId(UserAttribute::CLIENT_IDENTIFIER);
        $userAttribute->setValue('03853896000735');

        $userAttributeDAO = new UserAttributeDAO($connection);
        $userAttributeDAO->merge($userAttribute);
    }
}
