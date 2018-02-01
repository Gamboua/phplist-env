<?php

namespace phplist\Caixa\Functionality\Domain;

use phplist\Caixa\Functionality\Domain\Model\Caixa\Client;
use PHPUnit\Framework\TestCase;

/**
 * Class ClientTest
 *
 * @package phplist\Caixa\Functionality\Domain
 */
class ClientTest extends TestCase
{
    public function testFromArray()
    {
        $client = Client::fromArray([
            'identifier' => '123456',
            'email' => 'client@client.com.br',
            'name' => 'The client name',
        ]);

        $this->assertEquals('123456', $client->getIdentifier());
        $this->assertEquals('client@client.com.br', $client->getEmail());
        $this->assertEquals('The client name', $client->getName());
    }
}
