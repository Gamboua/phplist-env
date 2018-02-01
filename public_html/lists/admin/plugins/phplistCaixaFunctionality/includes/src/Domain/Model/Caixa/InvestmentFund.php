<?php

namespace phplist\Caixa\Functionality\Domain\Model\Caixa;

/**
 * Class InvestmentFund
 *
 * @package phplist\Caixa\Functionality\Domain\Model\Caixa
 */
class InvestmentFund
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
     * @param array $properties
     *
     * @return InvestmentFund
     */
    public static function fromArray(array $properties)
    {
        $investmentFund = new InvestmentFund();
        $investmentFund->referenceDate = $properties['referenceDate'];
        $investmentFund->agencyNumber = $properties['agencyNumber'];
        $investmentFund->operationNumber = $properties['operationNumber'];
        $investmentFund->accountNumber = $properties['accountNumber'];
        $investmentFund->agencyEmail = $properties['agencyEmail'];
        $investmentFund->modalityNumber = $properties['modalityNumber'];

        return $investmentFund;
    }
}
