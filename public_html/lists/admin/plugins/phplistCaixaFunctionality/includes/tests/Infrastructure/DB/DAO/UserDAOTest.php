<?php

namespace phplist\Caixa\Functionality\Infrastructure\DB\DAO;

use phplist\Caixa\Functionality\Domain\Model\User;
use phplist\Caixa\Functionality\Domain\Model\UserAttribute;
use phplist\Caixa\Functionality\Infrastructure\DB\Connection;
use phplist\Caixa\Functionality\Infrastructure\Shared\PHPList;
use PHPUnit\Framework\TestCase;

/**
 * Class UserDAOTest
 *
 * @package phplist\Caixa\Functionality\Infrastructure\DB\DAO
 */
class UserDAOTest extends TestCase
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
                'email' => 'client@email.com.br',
            ]);

        $userDAO = new UserDAO($connection);
        $userDAO->setPhpList($phpList);

        $user = $userDAO->findOne(25);

        $this->assertNotNull($user);
        $this->assertEquals(User::class, get_class($user));

        $this->assertEquals(25, $user->getId());
        $this->assertEquals('client@email.com.br', $user->getEmail());
    }

    public function testFindOneByUserAttribute()
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
            ->with($this->equalTo([
                UserAttribute::CLIENT_IDENTIFIER,
                '03853896000735',
            ]));

        $connectionPDOStmt->expects($this->once())
            ->method('fetch')
            ->willReturn([
                'id' => 25,
                'email' => 'client@email.com.br',
            ]);

        $userDAO = new UserDAO($connection);
        $userDAO->setPhpList($phpList);

        $user = $userDAO->findOneByUserAttribute(UserAttribute::CLIENT_IDENTIFIER, '03853896000735');

        $this->assertNotNull($user);
        $this->assertEquals(User::class, get_class($user));

        $this->assertEquals(25, $user->getId());
        $this->assertEquals('client@email.com.br', $user->getEmail());
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
            ->setMethods(['getEnteredNow', 'getUniqid'])
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
                'client@email.com.br',              // email
                1,                                  // confirmed
                '2017-11-07 18:28:40',              // entered
                'd5e00949353e16add2f5ccda469d7ec6', // uniqid
                1                                   // htmlemail
            ]));

        $phpList->expects($this->once())
            ->method('getEnteredNow')
            ->willReturn('2017-11-07 18:28:40');

        $phpList->expects($this->once())
            ->method('getUniqid')
            ->willReturn('d5e00949353e16add2f5ccda469d7ec6');

        $user = new User();
        $user->setEmail('client@email.com.br');

        $userDAO = new UserDAO($connection);
        $userDAO->setPhpList($phpList);

        $userDAO->add($user);

        $this->assertEquals(25, $user->getId());
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
                'client@email.com.br', //  email
                25                      // id
            ]));

        $user = new User();
        $user->setId(25);
        $user->setEmail('client@email.com.br');

        $userDAO = new UserDAO($connection);
        $userDAO->setPhpList($phpList);

        $userDAO->merge($user);
    }
}
