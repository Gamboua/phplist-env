<?php
namespace phplist\Caixa\Functionality\Application;

use phplist\Caixa\Functionality\Domain\Model\Caixa\ClientInvestment;
use phplist\Caixa\Functionality\Domain\Model\Caixa\ClientInvestmentRepository;
use phplist\Caixa\Functionality\Domain\Service\ImportClientInvestmentLog;
use phplist\Caixa\Functionality\Domain\Service\ImportInvestmentFundSubscription;
use phplist\Caixa\Functionality\Domain\Service\ImportSubscriptionList;
use phplist\Caixa\Functionality\Domain\Service\ImportUser;
use phplist\Caixa\Functionality\Domain\Service\ImportUserSubscription;
use phplist\Caixa\Functionality\Infrastructure\DB\Connection;
use phplist\Caixa\Functionality\Infrastructure\DB\Transaction;
use phplist\Caixa\Functionality\Infrastructure\Shared\PHPListCaixaLogger;

/**
 * Class ImportService
 *
 * @package phplist\Caixa\Functionality\Application
 */
class ImportService
{

    /**
     *
     * @var ImportUser
     */
    private $importUser;

    /**
     *
     * @var ImportSubscriptionList
     */
    private $importList;

    /**
     *
     * @var ImportUserSubscription
     */
    private $importUserSubscription;

    /**
     *
     * @var ImportInvestmentFundSubscription
     */
    private $importInvestmentFundSubscription;

    /**
     *
     * @var ImportClientInvestmentLog
     */
    private $importClientInvestmentLog;

    /**
     *
     * @var ClientInvestmentRepository
     */
    private $clientInvestmentRepository;

    /**
     *
     * @var Connection
     */
    private $phpListConnection;

    /**
     *
     * @var PHPListCaixaLogger
     */
    private $logger;

    /**
     * ImportService constructor.
     * @param ImportUser $importUser
     * @param ImportSubscriptionList $importList
     * @param ImportUserSubscription $importUserSubscription
     * @param ImportInvestmentFundSubscription $importInvestmentFundSubscription
     * @param ImportClientInvestmentLog $importClientInvestmentLog
     * @param ClientInvestmentRepository $clientInvestmentRepository
     * @param Connection $phpListConnection
     * @param PHPListCaixaLogger $logger
     */
    public function __construct
    (
        ImportUser $importUser,
        ImportSubscriptionList $importList,
        ImportUserSubscription $importUserSubscription,
        ImportInvestmentFundSubscription $importInvestmentFundSubscription,
        ImportClientInvestmentLog $importClientInvestmentLog,
        ClientInvestmentRepository $clientInvestmentRepository,
        Connection $phpListConnection,
        PHPListCaixaLogger $logger
    )
    {
        $this->importUser = $importUser;
        $this->importList = $importList;
        $this->importUserSubscription = $importUserSubscription;
        $this->importInvestmentFundSubscription = $importInvestmentFundSubscription;
        $this->importClientInvestmentLog = $importClientInvestmentLog;
        $this->clientInvestmentRepository = $clientInvestmentRepository;
        $this->phpListConnection = $phpListConnection;
        $this->logger = $logger;
    }

    /**
     *
     * @return \integer[]
     */
    public function getAllModalityNumbers()
    {
        return $this->clientInvestmentRepository->collectModalityNumbers();
    }

    /**
     * @param $modalityNumber
     */
    public function importByModalityNumber($modalityNumber)
    {
        $transaction = new Transaction($this->phpListConnection);
        $transaction->execute(function () use ($modalityNumber) {

            $this->clientInvestmentRepository->fromWithinFindAllByModalityNumber($modalityNumber, function (ClientInvestment $clientInvestment) {

                $client = $clientInvestment->getClient();
                $investmentFund = $clientInvestment->getInvestmentFund();
                $user = $this->importUser->findOrSave($client);
                $list = $this->importList->findOrSave($investmentFund);

                if ($user && $list) {
                    $this->importUserSubscription->findOrSave($user, $list);
                    $this->importInvestmentFundSubscription->findOrSave($user, $list, $investmentFund);
                }

                $this->importClientInvestmentLog->findOrSave($clientInvestment);

            });

        });

        $timeused = microtime(true) - $_SERVER['REQUEST_TIME'];

        $this->logger->generateLogImport
        (
            $this->importUser->getTotalNewUsers(),
            $this->importUser->getTotalUpdatedUsers(),
            $this->importClientInvestmentLog->getTotalNewClientesNoEmail(),
            $this->importClientInvestmentLog->getTotalupdatedClienteNoEmail(),
            $timeused
        );
    }

    /**
     * @param $modalityNumber
     */
    public function importByModalityNumberSlowly($modalityNumber)
    {
        $transaction = new Transaction($this->phpListConnection);
        $transaction->execute(function () use ($modalityNumber) {

            $clientInvestments = $this->clientInvestmentRepository->findAllByModalityNumber($modalityNumber);
            foreach ($clientInvestments as $clientInvestment) {

                $client = $clientInvestment->getClient();

                $investmentFund = $clientInvestment->getInvestmentFund();

                $user = $this->importUser->findOrSave($client);

                $list = $this->importList->findOrSave($investmentFund);

                if ($user && $list) {
                    $this->importUserSubscription->findOrSave($user, $list);
                    $this->importInvestmentFundSubscription->findOrSave($user, $list, $investmentFund);
                }

                $this->importClientInvestmentLog->findOrSave($clientInvestment);

            }

        });

        $timeused = microtime(true) - $_SERVER['REQUEST_TIME'];

        $this->logger->generateLogImport
        (
            $this->importUser->getTotalNewUsers(),
            $this->importUser->getTotalUpdatedUsers(),
            $this->importClientInvestmentLog->getTotalNewClientesNoEmail(),
            $this->importClientInvestmentLog->getTotalupdatedClienteNoEmail(),
            $timeused
        );
    }
}
