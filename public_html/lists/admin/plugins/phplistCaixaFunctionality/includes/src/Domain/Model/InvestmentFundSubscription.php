<?php

namespace phplist\Caixa\Functionality\Domain\Model;

/**
 * Class InvestmentFundSubscription
 *
 * @package phplist\Caixa\Functionality\Domain\Model
 */
class InvestmentFundSubscription
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var SubscriptionList
     */
    private $subscriptionList;

    /**
     * @var string
     */
    private $referenceDate;

    /**
     * @var integer
     */
    private $agencyNumber;

    /**
     * @var integer
     */
    private $operationNumber;

    /**
     * @var integer
     */
    private $accountNumber;

    /**
     * @var string
     */
    private $agencyEmail;

    /**
     * @var integer
     */
    private $modalityNumber;

    /**
     * Subscription constructor.
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
     * @return SubscriptionList
     */
    public function getSubscriptionList()
    {
        return $this->subscriptionList;
    }

    /**
     * @param SubscriptionList $subscriptionList
     */
    public function setSubscriptionList($subscriptionList)
    {
        $this->subscriptionList = $subscriptionList;
    }

    /**
     * @return string
     */
    public function getReferenceDate()
    {
        return $this->referenceDate;
    }

    /**
     * @param string $referenceDate
     */
    public function setReferenceDate($referenceDate)
    {
        $this->referenceDate = $referenceDate;
    }

    /**
     * @return int
     */
    public function getAgencyNumber()
    {
        return $this->agencyNumber;
    }

    /**
     * @param int $agencyNumber
     */
    public function setAgencyNumber($agencyNumber)
    {
        $this->agencyNumber = $agencyNumber;
    }

    /**
     * @return int
     */
    public function getOperationNumber()
    {
        return $this->operationNumber;
    }

    /**
     * @param int $operationNumber
     */
    public function setOperationNumber($operationNumber)
    {
        $this->operationNumber = $operationNumber;
    }

    /**
     * @return int
     */
    public function getAccountNumber()
    {
        return $this->accountNumber;
    }

    /**
     * @param int $accountNumber
     */
    public function setAccountNumber($accountNumber)
    {
        $this->accountNumber = $accountNumber;
    }

    /**
     * @return string
     */
    public function getAgencyEmail()
    {
        return $this->agencyEmail;
    }

    /**
     * @param string $agencyEmail
     */
    public function setAgencyEmail($agencyEmail)
    {
        $this->agencyEmail = $agencyEmail;
    }

    /**
     * @return int
     */
    public function getModalityNumber()
    {
        return $this->modalityNumber;
    }

    /**
     * @param int $modalityNumber
     */
    public function setModalityNumber($modalityNumber)
    {
        $this->modalityNumber = $modalityNumber;
    }

    /**
     * @param array $properties
     *
     * @return InvestmentFundSubscription
     */
    public static function fromArray(array $properties)
    {
        $investmentFundSubscription = new InvestmentFundSubscription();
        $investmentFundSubscription->user = $properties['user'];
        $investmentFundSubscription->subscriptionList = $properties['subscriptionList'];
        $investmentFundSubscription->referenceDate = $properties['referenceDate'];
        $investmentFundSubscription->agencyNumber = $properties['agencyNumber'];
        $investmentFundSubscription->operationNumber = $properties['operationNumber'];
        $investmentFundSubscription->accountNumber = $properties['accountNumber'];
        $investmentFundSubscription->agencyEmail = $properties['agencyEmail'];
        $investmentFundSubscription->modalityNumber = $properties['modalityNumber'];

        return $investmentFundSubscription;
    }
}
