<?php

namespace phplist\Caixa\Functionality\Domain\Model;

/**
 * Interface InvestmentFundSubscriptionRepository
 *
 * @package phplist\Caixa\Functionality\Domain\Model
 */
interface InvestmentFundSubscriptionRepository
{
    /**
     * @param User $user
     * @param SubscriptionList $subscriptionList
     *
     * @return InvestmentFundSubscription
     */
    public function findOne(User $user, SubscriptionList $subscriptionList);

    /**
     * @param InvestmentFundSubscription $investmentFundSubscription
     *
     * @return void
     */
    public function add(InvestmentFundSubscription $investmentFundSubscription);

    /**
     * @param InvestmentFundSubscription $investmentFundSubscription
     *
     * @return void
     */
    public function merge(InvestmentFundSubscription $investmentFundSubscription);
}
