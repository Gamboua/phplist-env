<?php

namespace phplist\Caixa\Functionality\Domain\Model;

/**
 * Interface UserRepository
 *
 * @package phplist\Caixa\Functionality\Domain\Model
 */
interface UserRepository
{
    /**
     * @param integer $id
     * @return User
     */
    public function findOne($id);

    /**
     * @param $attributeId
     * @param $value
     * @return User
     */
    public function findOneByUserAttribute($attributeId, $value);

    /**
     * @param User $user
     * @return void
     */
    public function add(User $user);

    /**
     * @param User $user
     * @return void
     */
    public function merge(User $user);
}
