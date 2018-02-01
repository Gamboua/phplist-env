<?php

namespace phplist\Caixa\Functionality\Domain\Model;

/**
 * Class UserAttribute
 *
 * @package phplist\Caixa\Functionality\Domain\Model
 */
class UserAttribute
{
    const CLIENT_IDENTIFIER = 1;
    const CLIENT_NAME = 2;

    /**
     * @var User
     */
    private $user;

    /**
     * @var integer
     */
    private $attributeId;

    /**
     * @var string
     */
    private $value;

    /**
     * UserAttribute constructor.
     */
    public function __construct()
    {
        // ...
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return int
     */
    public function getAttributeId()
    {
        return $this->attributeId;
    }

    /**
     * @param int $attributeId
     */
    public function setAttributeId($attributeId)
    {
        $this->attributeId = $attributeId;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @param array $properties
     *
     * @return UserAttribute
     */
    public static function fromArray(array $properties)
    {
        $user = new UserAttribute();
        $user->user = $properties['user'];
        $user->attributeId = $properties['attributeId'];
        $user->value = $properties['value'];

        return $user;
    }
}
