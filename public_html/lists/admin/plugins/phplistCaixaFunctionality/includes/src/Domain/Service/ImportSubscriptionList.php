<?php

namespace phplist\Caixa\Functionality\Domain\Service;

use phplist\Caixa\Functionality\Domain\Model\Caixa\InvestmentFund;
use phplist\Caixa\Functionality\Domain\Model\SubscriptionList;
use phplist\Caixa\Functionality\Domain\Model\SubscriptionListRepository;

/**
 * Class ImportSubscriptionList
 *
 * @package phplist\Caixa\Functionality\Domain\Service
 */
class ImportSubscriptionList
{
    /**
     * @var SubscriptionListRepository
     */
    private $subscriptionListRepository;

    /**
     * ImportSubscriptionList constructor.
     *
     * @param SubscriptionListRepository $subscriptionListRepository
     */
    public function __construct(SubscriptionListRepository $subscriptionListRepository)
    {
        $this->subscriptionListRepository = $subscriptionListRepository;
    }

    /**
     * @param InvestmentFund $investmentFund
     *
     * @return SubscriptionList
     */
    public function findOrSave(InvestmentFund $investmentFund)
    {
        $listName = $investmentFund->getModalityNumber();

        $subscriptionList = $this->subscriptionListRepository->findOneByName($listName);

        if (!$subscriptionList) {

            $subscriptionList = new SubscriptionList();
            $subscriptionList->setName($listName);
            $subscriptionList->setActive(0); // it's not a public list
            $subscriptionList->setOwner(1); // it's the admin user

            $this->subscriptionListRepository->add($subscriptionList);

        }

        return $subscriptionList;
    }
}
