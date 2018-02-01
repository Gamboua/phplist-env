<?php

namespace phplist\Caixa\Functionality\Infrastructure\DB\DAO;

use phplist\Caixa\Functionality\Domain\Model\Caixa\ListTb\ImportClientsFundRepository;
use phplist\Caixa\Functionality\Domain\Model\Caixa\ListTb\ListTbClient;
use phplist\Caixa\Functionality\Domain\Model\Caixa\ListTb\ListTbClientEmail;
use phplist\Caixa\Functionality\Domain\Model\Caixa\ListTb\ListTbClientInvestment;
use phplist\Caixa\Functionality\Infrastructure\Shared\AbstractDAO;

/**
 * Class ImportFundsDAO
 *
 * @package phplist\Caixa\Functionality\Infrastructure\DB\DAO
 */
class ImportFundsDAO extends AbstractDAO implements ImportClientsFundRepository
{

    /**
     * @param $clientInvestment
     *
     */
    public function insertClientInvestment(ListTbClientInvestment $clientInvestment)
    {
        $sql = <<<SQL
INSERT INTO lissm001.listb001_fundo_investimento(
            nu_cliente, nu_agencia, nu_operacao, nu_conta, 
            nu_modalidade, no_modalidade, dt_aplicacao, dt_vencimento, vr_base, 
            vr_atualizado, nu_certificado, dt_referencia,  
            ts_atualizacao, de_email_agencia)
    VALUES (
     
    
    
     ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);
            
SQL;
        $investmentFund = $clientInvestment->getInvestmentFund();
        $client = $clientInvestment->getClient();

        $connectionPDO = $this->connection->getPDO();
        $connectionPDOStmt = $connectionPDO->prepare($sql);
        $connectionPDOStmt->execute([
            $client->getId(),
            $investmentFund->getAgencyNumber(),
            $investmentFund->getOperationNumber(),
            $investmentFund->getAccountNumber(),
            $investmentFund->getModalityNumber(),
            $investmentFund->getModalityName(),
            $investmentFund->getDtApplication(),
            $investmentFund->getDtExpiration(),
            $investmentFund->getVrBase(),
            $investmentFund->getVrUpdated(),
            $investmentFund->getCertifiedNumber(),
            $investmentFund->getReferenceDate(),
            $investmentFund->getTsUpdated(),
            $investmentFund->getAgencyEmail()

            ]);
    }

    public function insertClient(ListTbClient $client)
    {
        $sql = <<<SQL
INSERT INTO lissm001.listb003_cliente(
            co_identificador_cliente, no_cliente)
    VALUES (?, ?);
SQL;


        $connectionPDO = $this->connection->getPDO();
        $connectionPDOStmt = $connectionPDO->prepare($sql);
        $connectionPDOStmt->execute([
            $client->getIdentifier(),
            $client->getName()
        ]);
        return $connectionPDO->lastInsertId();

    }

    public function getLastClientId() {
        $sql = <<<SQL
SELECT last_value FROM lissm001.lissq003_cliente;
SQL;
        $connectionPDO = $this->connection->getPDO();
        $connectionPDOStmt = $connectionPDO->prepare($sql);
        $connectionPDOStmt->execute();
        return $connectionPDOStmt->fetch()['last_value'];
    }

    public function insertClientEmail(ListTbClientEmail $clientEmail)
    {
        $sql = <<<SQL
INSERT INTO lissm001.listb002_email_cliente(
            nu_cliente, de_email_cliente, ts_atualizacao)
    VALUES (?, ?, ?);
SQL;


        $connectionPDO = $this->connection->getPDO();
        $connectionPDOStmt = $connectionPDO->prepare($sql);
        $connectionPDOStmt->execute([
            $clientEmail->getClientId(),
            $clientEmail->getEmail(),
            $clientEmail->getUpdatedAt()
        ]);
        return $connectionPDOStmt->fetchAll();

    }

    public function refreshMaterializedView()
    {
        $refreshView = <<<SQL
REFRESH MATERIALIZED VIEW lissm001.lisvw001_lista_email
SQL;
        $connectionPDO = $this->connection->getPDO();
        $connectionPDOStmt = $connectionPDO->prepare($refreshView);
        $connectionPDOStmt->execute();
    }
}
