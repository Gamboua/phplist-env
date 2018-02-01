<?php

namespace phplist\Caixa\Functionality\Domain\Model\Caixa\ListTb;

/**
 * Class InvestmentFund
 *
 * @package phplist\Caixa\Functionality\Domain\Model\Caixa
 */
class ListTbInvestmentFund
{
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
     * @var string
     */
    private $modalityName;

    /**
     * @var date
     */
    private $dtApplication;

    /**
     * @var date
     */
    private $dtExpiration;


    /**
     * @var string
     */
    private $vrBase;

    /**
     * @var string
     */
    private $vrUpdated;

    /**
     * @var string
     */
    private $certifiedNumber;

    /**
     * @var date
     */
    private $tsUpdated;


    /**
     * InvestmentFund constructor.
     */
    private function __construct()
    {
        // ...
    }

    /**
     * @return string
     */
    public function getReferenceDate()
    {
        return $this->referenceDate;
    }

    /**
     * @return int
     */
    public function getAgencyNumber()
    {
        return $this->agencyNumber;
    }

    /**
     * @return int
     */
    public function getOperationNumber()
    {
        return $this->operationNumber;
    }

    /**
     * @return int
     */
    public function getAccountNumber()
    {
        return $this->accountNumber;
    }

    /**
     * @return string
     */
    public function getAgencyEmail()
    {
        return $this->agencyEmail;
    }

    /**
     * @return int
     */
    public function getModalityNumber()
    {
        return $this->modalityNumber;
    }

    /**
     * @return string
     */
    public function getModalityName()
    {
        return $this->modalityName;
    }

    /**
     * @return date
     */
    public function getDtApplication()
    {
        return $this->dtApplication;
    }

    /**
     * @return date
     */
    public function getDtExpiration()
    {
        return $this->dtExpiration;
    }

    /**
     * @return date
     */
    public function getVrBase()
    {
        return $this->vrBase;
    }

    /**
     * @return string
     */
    public function getVrUpdated()
    {
        return $this->vrUpdated;
    }

    /**
     * @return string
     */
    public function getCertifiedNumber()
    {
        return $this->certifiedNumber;
    }

    /**
     * @return date
     */
    public function getTsUpdated()
    {
        return $this->tsUpdated;
    }



    /**
     * @param array $properties
     *
     * @return InvestmentFund
     */
    public static function fromArray(array $properties)
    {
        $investmentFund = new ListTbInvestmentFund();
        $investmentFund->referenceDate = $properties['referenceDate'];
        $investmentFund->agencyNumber = $properties['agencyNumber'];
        $investmentFund->operationNumber = $properties['operationNumber'];
        $investmentFund->accountNumber = $properties['accountNumber'];
        $investmentFund->agencyEmail = $properties['agencyEmail'];
        $investmentFund->modalityNumber = $properties['modalityNumber'];
        $investmentFund->modalityName = $properties['modalityName'];
        $investmentFund->dtApplication = $properties['dtApplication'];
        $investmentFund->dtExpiration = $properties['dtExpiration'];
        $investmentFund->vrBase = $properties['vrBase'];
        $investmentFund->vrUpdated = $properties['vrUpdated'];
        $investmentFund->certifiedNumber = $properties['certifiedNumber'];
        $investmentFund->tsUpdated = $properties['tsUpdated'];

        return $investmentFund;
    }
}

