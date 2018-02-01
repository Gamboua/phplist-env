<?php

namespace phplist\Caixa\Functionality\Domain\Model\Caixa\ListTb;

/**
 * Class Xpto
 *
 * @package phplist\Caixa\Functionality\Domain\Model\Caixa
 */
class ListTbClient
{
    /**
     * @var integer
     */
    private $id;

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
    public function getId()
    {
        return $this->id;
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
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }


    /**
     * @param array $properties
     *
     * @return Client
     */
    public static function fromArray(array $properties)
    {
        $client = new ListTbClient();


        if(isset($properties['id'])){
            $client->id = $properties['id'];
        }

        $client->identifier = $properties['identifier'];
        $client->email = $properties['email'];
        $client->name = $properties['name'];

        return $client;
    }
}
