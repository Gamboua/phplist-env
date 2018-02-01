<?php
namespace phplist\Caixa\Functionality\Domain\Service;

use phplist\Caixa\Functionality\Domain\Model\Caixa\ClientInvestment;
use phplist\Caixa\Functionality\Domain\Model\ClientInvestmentLog;
use phplist\Caixa\Functionality\Domain\Model\ClientInvestmentLogRepository;
use phplist\Caixa\Functionality\Domain\Shared\EmailValidator;

/**
 * Class ImportClientInvestmentLog
 *
 * @package phplist\Caixa\Functionality\Domain\Service
 */
class ImportClientInvestmentLog
{

    /**
     *
     * @var ClientInvestmentLogRepository
     */
    private $clientInvestmentLogRepository;

    /**
     * total new clients without email
     *
     * @var integer
     */
    private $totalNewClientesNoEmail = 0;

    /**
     * total clients without email updated
     *
     * @var integer
     */
    private $totalupdatedClienteNoEmail = 0;

    /**
     * ImportClientInvestmentLog constructor.
     *
     * @param ClientInvestmentLogRepository $clientInvestmentLogRepository
     */
    public function __construct(ClientInvestmentLogRepository $clientInvestmentLogRepository)
    {
        $this->clientInvestmentLogRepository = $clientInvestmentLogRepository;
    }

    /**
     *
     * @param ClientInvestment $clientInvestment
     *
     * @return ClientInvestmentLog
     */
    public function findOrSave(ClientInvestment $clientInvestment)
    {
        $client = $clientInvestment->getClient();
        $investmentFund = $clientInvestment->getInvestmentFund();

        $identifier = $clientInvestment->getClient()->getIdentifier();
        $modalityNumber = $clientInvestment->getInvestmentFund()->getModalityNumber();
        $clientInvestmentLog = $this->clientInvestmentLogRepository->findOne($identifier, $modalityNumber);

        $clientEmail = $client->getEmail();
        $hasValidClientEmail = EmailValidator::isValid($clientEmail);

        if ($hasValidClientEmail && $clientInvestmentLog) {

            // remove scenario
            $this->clientInvestmentLogRepository->remove($clientInvestmentLog);
            $clientInvestmentLog = null;

        } elseif (!$hasValidClientEmail && $clientInvestmentLog) {

            $clientInvestmentLog->setName($client->getName());
            $clientInvestmentLog->setReferenceDate($investmentFund->getReferenceDate());
            $clientInvestmentLog->setAgencyNumber($investmentFund->getAgencyNumber());
            $clientInvestmentLog->setOperationNumber($investmentFund->getOperationNumber());
            $clientInvestmentLog->setAccountNumber($investmentFund->getAccountNumber());
            $clientInvestmentLog->setAgencyEmail($investmentFund->getAgencyEmail());
            $clientInvestmentLog->setClientEmail($clientEmail);
            $this->totalupdatedClienteNoEmail++;

            // update scenario
            $this->clientInvestmentLogRepository->merge($clientInvestmentLog);

        } elseif (!$hasValidClientEmail && !$clientInvestmentLog) {

            $clientInvestmentLog = new ClientInvestmentLog();
            $clientInvestmentLog->setIdentifier($client->getIdentifier());
            $clientInvestmentLog->setName($client->getName());
            $clientInvestmentLog->setReferenceDate($investmentFund->getReferenceDate());
            $clientInvestmentLog->setAgencyNumber($investmentFund->getAgencyNumber());
            $clientInvestmentLog->setOperationNumber($investmentFund->getOperationNumber());
            $clientInvestmentLog->setAccountNumber($investmentFund->getAccountNumber());
            $clientInvestmentLog->setAgencyEmail($investmentFund->getAgencyEmail());
            $clientInvestmentLog->setModalityNumber($investmentFund->getModalityNumber());
            $clientInvestmentLog->setClientEmail($clientEmail);
            $this->totalNewClientesNoEmail++;

            // create scenario
            $this->clientInvestmentLogRepository->add($clientInvestmentLog);

        }

        return $clientInvestmentLog;
    }

    /**
     *
     * @return the $totalNewClientesNoEmail
     */
    public function getTotalNewClientesNoEmail()
    {
        return $this->totalNewClientesNoEmail;
    }

    /**
     *
     * @return the $totalupdatedClienteNoEmail
     */
    public function getTotalupdatedClienteNoEmail()
    {
        return $this->totalupdatedClienteNoEmail;
    }
}
