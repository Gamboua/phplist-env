<?php

namespace phplist\Caixa\Functionality\Domain;

use phplist\Caixa\Functionality\Domain\Model\Caixa\ListTb\ListTbClient;
use phplist\Caixa\Functionality\Domain\Model\Caixa\ListTb\ListTbClientEmail;
use phplist\Caixa\Functionality\Domain\Model\Caixa\ListTb\ListTbClientInvestment;
use phplist\Caixa\Functionality\Domain\Model\Caixa\ListTb\ListTbInvestmentFund;
use phplist\Caixa\Functionality\Infrastructure\DB\Connection;
use phplist\Caixa\Functionality\Infrastructure\DB\DAO\ImportFundsDAO;
use phplist\Caixa\Functionality\Infrastructure\Shared\PHPList;

/**
 * Class DummyImportGeneratorService
 *
 * @package phplist\Caixa\Functionality\Domain
 */
class DummyImportGeneratorService
{
    private $phpList;
    private $importFundsDAO;

    public function __construct(PHPList $phpList, ImportFundsDAO $importFundsDAO)
    {
        $this->phpList = $phpList;
        $this->importFundsDAO = $importFundsDAO;
    }

    public function insertClient(ListTbClient $client)
    {
        $clientId = $this->importFundsDAO->insertClient($client);
        $client->setId($clientId);
        $clientEmail = ListTbClientEmail::fromArray([
            'clientId' => $clientId,
            'email' => $client->getEmail(),
            'updatedAt' => $this->phpList->getEnteredNow()
        ]);

        $this->importFundsDAO->insertClientEmail($clientEmail);

    }

    public function getLastClientId() {
        return $this->importFundsDAO->getLastClientId();
    }

    public function insertClientAtFund(ListTbClientInvestment $clientInvestment)
    {
        $this->importFundsDAO->insertClientInvestment($clientInvestment);
    }

    public function insertClientsAtFund(array $clients, array $investmentFunds)
    {
        $connectionPDO = Connection::fromCaixa()->getPDO();
        $connectionPDO->beginTransaction();

        try {
            $this->importFundsDAO->refreshMaterializedView();
            foreach ($clients as $client) {
                $this->insertClient($client);
                $key = array_rand($investmentFunds);
                $clientInvestment = new ListTbClientInvestment($client, $investmentFunds[$key]);
                $this->importFundsDAO->insertClientInvestment($clientInvestment);
            }

            $connectionPDO->commit();

        }
        catch (\Exception $e) {
            echo $e->getMessage();
            echo '<br>';
            $connectionPDO->rollBack();
            exit();
        } finally {
            $connectionPDO = null;
        }

    }

    public function refreshMaterializedView()
    {
        $this->importFundsDAO->refreshMaterializedView();
    }




}
