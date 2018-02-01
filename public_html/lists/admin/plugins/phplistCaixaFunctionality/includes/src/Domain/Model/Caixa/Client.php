<?php

namespace phplist\Caixa\Functionality\Domain\Model\Caixa;

/**
 * Class Client
 *
 * @package phplist\Caixa\Functionality\Domain\Model\Caixa
 */
class Client
{
    /**
     * @var integer
     */
    private $identifier;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $email;

    /**
     * Client constructor.
     */
    private function __construct()
    {
        // ...
    }

    /**
     * @return int
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param array $properties
     *
     * @return Client
     */
    public static function fromArray(array $properties)
    {
        $client = new Client();
        $client->identifier = $properties['identifier'];
        $client->email = $properties['email'];
        $client->name = $properties['name'];

        return $client;
    }
}
