<?php

namespace phplist\Caixa\Functionality\Domain\Model;

/**
 * Class ClientInvestmentLog
 *
 * @package phplist\Caixa\Functionality\Domain\Model
 */
class ClientInvestmentLog
{
    /**
     * @var integer
     */
    private $identifier;

    /**
     * @var string
     */
    private $name;

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
    private $clientEmail;

    /**
     * UserNoEmailLog constructor.
     */
    public function __construct()
    {
        // ...
    }

    /**
     * @return int
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @param int $identifier
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
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
     * @return string
     */
    public function getClientEmail()
    {
        return $this->clientEmail;
    }

    /**
     * @param string $clientEmail
     */
    public function setClientEmail($clientEmail)
    {
        $this->clientEmail = $clientEmail;
    }

    /**
     * @param array $properties
     *
     * @return ClientInvestmentLog
     */
    public static function fromArray(array $properties)
    {
        $clientInvestmentLog = new ClientInvestmentLog();
        $clientInvestmentLog->identifier = $properties['identifier'];
        $clientInvestmentLog->name = $properties['name'];
        $clientInvestmentLog->referenceDate = $properties['referenceDate'];
        $clientInvestmentLog->agencyNumber = $properties['agencyNumber'];
        $clientInvestmentLog->operationNumber = $properties['operationNumber'];
        $clientInvestmentLog->accountNumber = $properties['accountNumber'];
        $clientInvestmentLog->agencyEmail = $properties['agencyEmail'];
        $clientInvestmentLog->modalityNumber = $properties['modalityNumber'];
        $clientInvestmentLog->clientEmail = $properties['clientEmail'];

        return $clientInvestmentLog;
    }
}
