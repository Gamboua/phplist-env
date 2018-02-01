<?php

namespace phplist\Caixa\Functionality\Domain;

use phplist\Caixa\Functionality\Domain\Model\User;
use PHPUnit\Framework\TestCase;

/**
 * Class UserTest
 *
 * @package phplist\Caixa\Functionality\Domain
 */
class UserTest extends TestCase
{
    public function testFromArray()
    {
        $user = User::fromArray([
            'id' => 25,
            'email' => 'user@user.com.br',
        ]);

        $this->assertEquals(25, $user->getId());
        $this->assertEquals('user@user.com.br', $user->getEmail());
    }
}
