<?php

namespace phplist\Caixa\Functionality\Domain;

use phplist\Caixa\Functionality\Domain\Model\User;
use phplist\Caixa\Functionality\Domain\Model\UserAttribute;
use PHPUnit\Framework\TestCase;

/**
 * Class UserAttributeTest
 *
 * @package phplist\Caixa\Functionality\Domain
 */
class UserAttributeTest extends TestCase
{
    public function testFromArray()
    {
        $user = new User();

        $userAttribute = UserAttribute::fromArray([
            'user' => $user,
            'attributeId' => UserAttribute::CLIENT_IDENTIFIER,
            'value' => 'the value',
        ]);

        $this->assertSame($user, $userAttribute->getUser());
        $this->assertEquals(UserAttribute::CLIENT_IDENTIFIER, $userAttribute->getAttributeId());
        $this->assertEquals('the value', $userAttribute->getValue());
    }
}
