<?php

namespace phplist\Caixa\Functionality\Domain\Shared;

/**
 * Class EmailValidator
 *
 * @package phplist\Caixa\Functionality\Domain\Shared
 */
abstract class EmailValidator
{
    /**
     * @param string $email
     *
     * @return bool
     */
    public static function isValid($email)
    {
        $isValid = !is_null($email);
        $isValid = $isValid && strlen(trim($email)) > 0;
        $isValid = $isValid && filter_var($email, FILTER_VALIDATE_EMAIL);

        return $isValid;
    }
}
