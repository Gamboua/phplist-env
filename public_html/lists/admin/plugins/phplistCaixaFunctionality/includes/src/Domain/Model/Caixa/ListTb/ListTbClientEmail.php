<?php

namespace phplist\Caixa\Functionality\Domain\Model\Caixa\ListTb;

/**
 * Class ClientEmail
 *
 * @package phplist\Caixa\Functionality\Domain\Model\Caixa
 */
class ListTbClientEmail
{
    /**
     * @var integer
     */
    private $clientId;

    /**
     * @var date
     */
    private $updatedAt;

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
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @return date
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
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
        $clientEmail = new ListTbClientEmail();
        $clientEmail->clientId = $properties['clientId'];
        $clientEmail->email = $properties['email'];
        $clientEmail->updatedAt = $properties['updatedAt'];

        return $clientEmail;
    }
}
