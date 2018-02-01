<?php

namespace phplist\Caixa\Functionality\Domain\Model;

/**
 * Interface UserAttributeRepository
 *
 * @package phplist\Caixa\Functionality\Domain\Model
 */
interface UserAttributeRepository
{
    /**
     * @param User $user
     * @param $attributeId
     *
     * @return UserAttribute
     */
    public function findOne(User $user, $attributeId);

    /**
     * @param UserAttribute $userAttribute
     *
     * @return void
     */
    public function add(UserAttribute $userAttribute);

    /**
     * @param UserAttribute $userAttribute
     *
     * @return void
     */
    public function merge(UserAttribute $userAttribute);
}
