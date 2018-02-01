<?php

namespace phplist\Caixa\Functionality\Domain\Model;

/**
 * Class User
 *
 * @package phplist\Caixa\Functionality\Domain\Model
 */
class User
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $email;

    /**
     * User constructor.
     */
    public function __construct()
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
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @param array $properties
     *
     * @return User
     */
    public static function fromArray(array $properties)
    {
        $user = new User();
        $user->id = $properties['id'];
        $user->email = $properties['email'];

        return $user;
    }
}
