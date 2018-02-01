<?php

namespace phplist\Caixa\Functionality\Domain\Service;

use phplist\Caixa\Functionality\Domain\Model\Caixa\Client;
use phplist\Caixa\Functionality\Domain\Model\User;
use phplist\Caixa\Functionality\Domain\Model\UserAttribute;
use phplist\Caixa\Functionality\Domain\Model\UserAttributeRepository;
use phplist\Caixa\Functionality\Domain\Model\UserRepository;
use PHPUnit\Framework\TestCase;

/**
 * Class ImportUserTest
 *
 * @package phplist\Caixa\Functionality\Domain\Service
 */
class ImportUserTest extends TestCase
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var User
     */
    private $newUser;

    /**
     * @var UserAttribute
     */
    private $newClientIdentifier;

    /**
     * @var UserAttribute
     */
    private $newClientName;

    /**
     * @var User
     */
    private $savedUser;

    /**
     * @var UserAttribute
     */
    private $savedClientName;

    public function setUp()
    {
        $this->client = Client::fromArray([
            'identifier' => '123',
            'email' => 'client@client.com.br',
            'name' => 'the client name',
        ]);

        $this->newUser = User::fromArray([
            'id' => null,
            'email' => 'client@client.com.br',
        ]);

        $this->newClientIdentifier = UserAttribute::fromArray([
            'user' => $this->newUser,
            'attributeId' => UserAttribute::CLIENT_IDENTIFIER,
            'value' => '123'
        ]);

        $this->newClientName = UserAttribute::fromArray([
            'user' => $this->newUser,
            'attributeId' => UserAttribute::CLIENT_NAME,
            'value' => 'the client name'
        ]);

        $this->savedUser = User::fromArray([
            'id' => 25,
            'email' => 'client2@client.com.br',
        ]);

        $this->savedClientName = UserAttribute::fromArray([
            'user' => $this->savedUser,
            'attributeId' => UserAttribute::CLIENT_NAME,
            'value' => 'the client name'
        ]);
    }

    public function testFindOrSaveWhenNotFoundMustCreate()
    {
        $userRepository = $this->getMockBuilder(UserRepository::class)->getMock();
        $userAttributeRepository = $this->getMockBuilder(UserAttributeRepository::class)->getMock();

        // expects
        $userRepository->expects($this->once())
            ->method('findOneByUserAttribute')
            ->with($this->equalTo(UserAttribute::CLIENT_IDENTIFIER), $this->equalTo('123'))
            ->willReturn(null);

        $userRepository->expects($this->once())
            ->method('add')
            ->with($this->equalTo($this->newUser));

        $userAttributeRepository->expects($this->at(0))
            ->method('add')
            ->with($this->equalTo($this->newClientIdentifier));

        $userAttributeRepository->expects($this->at(1))
            ->method('add')
            ->with($this->equalTo($this->newClientName));

        $importUser = new ImportUser(
            $userRepository,
            $userAttributeRepository
        );

        $user = $importUser->findOrSave($this->client);
        $this->assertNotNull($user);
        $this->assertEquals(User::class, get_class($user));
        $this->assertEquals('client@client.com.br', $user->getEmail());
    }

    public function testFindOrSaveWhenFoundMustUpdate()
    {
        $userRepository = $this->getMockBuilder(UserRepository::class)->getMock();
        $userAttributeRepository = $this->getMockBuilder(UserAttributeRepository::class)->getMock();

        // expects
        $userRepository->expects($this->once())
            ->method('findOneByUserAttribute')
            ->with($this->equalTo(UserAttribute::CLIENT_IDENTIFIER), $this->equalTo('123'))
            ->willReturn($this->savedUser);

        $userRepository->expects($this->once())
            ->method('merge')
            ->with($this->equalTo($this->savedUser));

        $userAttributeRepository->expects($this->at(0))
            ->method('merge')
            ->with($this->equalTo($this->savedClientName));

        $importUser = new ImportUser(
            $userRepository,
            $userAttributeRepository
        );

        $user = $importUser->findOrSave($this->client);
        $this->assertNotNull($user);
        $this->assertEquals(User::class, get_class($user));
        $this->assertEquals('client@client.com.br', $user->getEmail());
    }

    public function testFindOrSaveWhenClientHasNullEmailMustImportNothingAndReturnNull()
    {
        $userRepository = $this->getMockBuilder(UserRepository::class)->getMock();
        $userAttributeRepository = $this->getMockBuilder(UserAttributeRepository::class)->getMock();

        // expects
        $userRepository->expects($this->never())->method('findOneByUserAttribute');
        $userRepository->expects($this->never())->method('add');
        $userRepository->expects($this->never())->method('merge');
        $userAttributeRepository->expects($this->never())->method('add');
        $userAttributeRepository->expects($this->never())->method('merge');

        $client = Client::fromArray([
            'identifier' => '123456',
            'name' => 'the client name',
            'email' => null,
        ]);

        $importUser = new ImportUser(
            $userRepository,
            $userAttributeRepository
        );

        $this->assertNull($importUser->findOrSave($client));
    }

    public function testFindOrSaveWhenClientHasBlankEmailMustImportNothingAndReturnNull()
    {
        $userRepository = $this->getMockBuilder(UserRepository::class)->getMock();
        $userAttributeRepository = $this->getMockBuilder(UserAttributeRepository::class)->getMock();

        // expects
        $userRepository->expects($this->never())->method('findOneByUserAttribute');
        $userRepository->expects($this->never())->method('add');
        $userRepository->expects($this->never())->method('merge');
        $userAttributeRepository->expects($this->never())->method('add');
        $userAttributeRepository->expects($this->never())->method('merge');

        $client = Client::fromArray([
            'identifier' => '123456',
            'name' => 'the client name',
            'email' => ' ',
        ]);

        $importUser = new ImportUser(
            $userRepository,
            $userAttributeRepository
        );

        $this->assertNull($importUser->findOrSave($client));
    }
}
