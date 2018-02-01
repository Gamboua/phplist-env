<?php

namespace phplist\Caixa\Functionality\Domain\Service;

use phplist\Caixa\Functionality\Domain\Model\Caixa\InvestmentFund;
use phplist\Caixa\Functionality\Domain\Model\InvestmentFundSubscription;
use phplist\Caixa\Functionality\Domain\Model\InvestmentFundSubscriptionRepository;
use phplist\Caixa\Functionality\Domain\Model\SubscriptionList;
use phplist\Caixa\Functionality\Domain\Model\User;

/**
 * Class ImportInvestmentFundSubscription
 *
 * @package phplist\Caixa\Functionality\Domain\Service
 */
class ImportInvestmentFundSubscription
{
    /**
     * @var InvestmentFundSubscriptionRepository
     */
    private $investmentFundSubscriptionRepository;

    /**
     * ImportInvestmentFundSubscription constructor.
     *
     * @param InvestmentFundSubscriptionRepository $investmentFundSubscriptionRepository
     */
    public function __construct(InvestmentFundSubscriptionRepository $investmentFundSubscriptionRepository)
    {
        $this->investmentFundSubscriptionRepository = $investmentFundSubscriptionRepository;
    }

    /**
     * @param User $user
     * @param SubscriptionList $subscriptionList
     * @param InvestmentFund $investmentFund
     *
     * @return InvestmentFundSubscription
     */
    public function findOrSave(User $user, SubscriptionList $subscriptionList, InvestmentFund $investmentFund)
    {
        $investmentFundSubscription = $this->investmentFundSubscriptionRepository->findOne($user, $subscriptionList);
        if (!$investmentFundSubscription) {

            $investmentFundSubscription = new InvestmentFundSubscription();
            $investmentFundSubscription->setUser($user);
            $investmentFundSubscription->setSubscriptionList($subscriptionList);

            $investmentFundSubscription->setReferenceDate($investmentFund->getReferenceDate());
            $investmentFundSubscription->setAgencyNumber($investmentFund->getAgencyNumber());
            $investmentFundSubscription->setOperationNumber($investmentFund->getOperationNumber());
            $investmentFundSubscription->setAccountNumber($investmentFund->getAccountNumber());
            $investmentFundSubscription->setAgencyEmail($investmentFund->getAgencyEmail());
            $investmentFundSubscription->setModalityNumber($investmentFund->getModalityNumber());

            $this->investmentFundSubscriptionRepository->add($investmentFundSubscription);

        } else {

            $investmentFundSubscription->setReferenceDate($investmentFund->getReferenceDate());
            $investmentFundSubscription->setAgencyNumber($investmentFund->getAgencyNumber());
            $investmentFundSubscription->setOperationNumber($investmentFund->getOperationNumber());
            $investmentFundSubscription->setAccountNumber($investmentFund->getAccountNumber());
            $investmentFundSubscription->setAgencyEmail($investmentFund->getAgencyEmail());
            $investmentFundSubscription->setModalityNumber($investmentFund->getModalityNumber());

            $this->investmentFundSubscriptionRepository->merge($investmentFundSubscription);

        }

        return $investmentFundSubscription;
    }
}
