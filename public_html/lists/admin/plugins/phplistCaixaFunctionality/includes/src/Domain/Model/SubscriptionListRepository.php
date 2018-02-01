<?php

namespace phplist\Caixa\Functionality\Domain\Model;

/**
 * Interface SubscriptionListRepository
 *
 * @package phplist\Caixa\Functionality\Domain\Model
 */
interface SubscriptionListRepository
{
    /**
     * @param integer $id
     *
     * @return SubscriptionList
     */
    public function findOne($id);

    /**
     * @param string $name
     *
     * @return SubscriptionList
     */
    public function findOneByName($name);

    /**
     * @return SubscriptionList[]
     */
    public function findAll();

    /**
     * @param SubscriptionList $subscriberList
     *
     * @return void
     */
    public function add(SubscriptionList $subscriberList);
}
