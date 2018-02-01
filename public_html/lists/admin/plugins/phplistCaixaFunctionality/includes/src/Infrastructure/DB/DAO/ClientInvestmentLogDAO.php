<?php
namespace phplist\Caixa\Functionality\Infrastructure\DB\DAO;

use phplist\Caixa\Functionality\Domain\Model\ClientInvestmentLog;
use phplist\Caixa\Functionality\Domain\Model\ClientInvestmentLogRepository;
use phplist\Caixa\Functionality\Infrastructure\Shared\AbstractDAO;
use phplist\Caixa\Functionality\Infrastructure\DB\PageRequest;

/**
 * Class ClientInvestmentLogDAO
 *
 * @package phplist\Caixa\Functionality\Infrastructure\DB\DAO
 */
class ClientInvestmentLogDAO extends AbstractDAO implements ClientInvestmentLogRepository
{

    /**
     *
     * @param string $identifier
     * @param integer $modalityNumber
     *
     * @return ClientInvestmentLog
     */
    public function findOne($identifier, $modalityNumber)
    {
        $sql = <<<SQL
SELECT 
  co_identificador_cliente,
  no_cliente,
  dt_referencia,
  nu_agencia,
  nu_operacao,
  nu_conta,
  de_email_agencia,
  nu_modalidade,
  de_email_cliente
FROM 
  phplist.phplist_caixa_cliente_sem_email
WHERE 
  co_identificador_cliente = ?
  AND nu_modalidade = ?;
SQL;

        $connectionPDO = $this->connection->getPDO();
        $connectionPDOStmt = $connectionPDO->prepare($sql);
        $connectionPDOStmt->execute([
            $identifier,
            $modalityNumber
        ]);

        $clientInvestmentLog = null;
        $fetched = $connectionPDOStmt->fetch(\PDO::FETCH_ASSOC);

        if ($fetched) {
            $clientInvestmentLog = ClientInvestmentLog::fromArray([
                'identifier' => $fetched['co_identificador_cliente'],
                'name' => $fetched['no_cliente'],
                'referenceDate' => $fetched['dt_referencia'],
                'agencyNumber' => $fetched['nu_agencia'],
                'operationNumber' => $fetched['nu_operacao'],
                'accountNumber' => $fetched['nu_conta'],
                'agencyEmail' => $fetched['de_email_agencia'],
                'modalityNumber' => $fetched['nu_modalidade'],
                'clientEmail' => $fetched['de_email_cliente'],
            ]);
        }

        return $clientInvestmentLog;
    }

    public function findByFilter($field, $filter = [], $order = 'co_identificador_cliente', PageRequest $pageRequest = null)
    {

        $sql = <<<SQL
SELECT
  co_identificador_cliente,
  no_cliente,
  dt_referencia,
  nu_agencia,
  nu_operacao,
  nu_conta,
  de_email_agencia,
  nu_modalidade
FROM
  phplist.phplist_caixa_cliente_sem_email
SQL;

        if ($filter && $field) {
            $sql .= " WHERE
                        $field = ?";
        }

        if($pageRequest) {
            $sql .= " limit {$pageRequest->getNumberPerPage()} offset {$pageRequest->getStart()};";
        }


        $connectionPDO = $this->connection->getPDO();
        $connectionPDOStmt = $connectionPDO->prepare($sql);
        $connectionPDOStmt->execute([
            $filter
        ]);

        $clientInvestmentLog = null;
        $fetcheds = $connectionPDOStmt->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($fetcheds as $fetched) {
            $clientInvestmentLog = ClientInvestmentLog::fromArray([
                'identifier' => $fetched['co_identificador_cliente'],
                'name' => $fetched['no_cliente'],
                'referenceDate' => $fetched['dt_referencia'],
                'agencyNumber' => $fetched['nu_agencia'],
                'operationNumber' => $fetched['nu_operacao'],
                'accountNumber' => $fetched['nu_conta'],
                'agencyEmail' => $fetched['de_email_agencia'],
                'modalityNumber' => $fetched['nu_modalidade']
            ]);

            $clientsInvestmentLog[] = $clientInvestmentLog;
        }

        return $clientsInvestmentLog;
    }

    /**
     *
     * @return ClientInvestmentLog[]
     */
    public function findAll(PageRequest $pageRequest)
    {
        $sql = <<<SQL
SELECT
  co_identificador_cliente,
  no_cliente,
  dt_referencia,
  nu_agencia,
  nu_operacao,
  nu_conta,
  de_email_agencia,
  nu_modalidade,
  de_email_cliente
FROM
  phplist.phplist_caixa_cliente_sem_email
limit {$pageRequest->getNumberPerPage()}
offset {$pageRequest->getStart()};
SQL;

        $connectionPDO = $this->connection->getPDO();
        $fetcheds = $connectionPDO->query($sql)->fetchAll(\PDO::FETCH_ASSOC);

        $clientsInvestmentLog = [];

        foreach ($fetcheds as $fetched) {
            $clientInvestmentLog = ClientInvestmentLog::fromArray([
                'identifier' => $fetched['co_identificador_cliente'],
                'name' => $fetched['no_cliente'],
                'referenceDate' => $fetched['dt_referencia'],
                'agencyNumber' => $fetched['nu_agencia'],
                'operationNumber' => $fetched['nu_operacao'],
                'accountNumber' => $fetched['nu_conta'],
                'agencyEmail' => $fetched['de_email_agencia'],
                'modalityNumber' => $fetched['nu_modalidade'],
                'clientEmail' => $fetched['de_email_cliente'],
            ]);

            $clientsInvestmentLog[] = $clientInvestmentLog;
        }

        return $clientsInvestmentLog;
    }

    /**
     *
     * @return integer
     */
    public function countAll()
    {
        $sql = <<<SQL
select
  count(*)
from
  phplist.phplist_caixa_cliente_sem_email;
SQL;

        $connectionPDO = $this->connection->getPDO();
        $connectionPDOStmt = $connectionPDO->prepare($sql);
        $connectionPDOStmt->execute();

        $fetched = $connectionPDOStmt->fetch();
        return intval($fetched[0]);
    }

    /**
     *
     * @param ClientInvestmentLog $clientInvestmentLog
     *
     * @return void
     */
    public function add(ClientInvestmentLog $clientInvestmentLog)
    {
        $sql = <<<SQL
INSERT INTO 
  phplist.phplist_caixa_cliente_sem_email
  (
    co_identificador_cliente,
    no_cliente,
    dt_referencia,
    nu_agencia,
    nu_operacao,
    nu_conta,
    de_email_agencia,
    nu_modalidade,
    de_email_cliente
  )
VALUES
  (
    ?,
    ?,
    ?,
    ?,
    ?,
    ?,
    ?,
    ?,
    ?
  );
SQL;

        $connectionPDO = $this->connection->getPDO();
        $connectionPDOStmt = $connectionPDO->prepare($sql);
        $connectionPDOStmt->execute([
            $clientInvestmentLog->getIdentifier(),
            $clientInvestmentLog->getName(),
            $clientInvestmentLog->getReferenceDate(),
            $clientInvestmentLog->getAgencyNumber(),
            $clientInvestmentLog->getOperationNumber(),
            $clientInvestmentLog->getAccountNumber(),
            $clientInvestmentLog->getAgencyEmail(),
            $clientInvestmentLog->getModalityNumber(),
            $clientInvestmentLog->getClientEmail(),
        ]);
    }

    /**
     *
     * @param ClientInvestmentLog $clientInvestmentLog
     *
     * @return void
     */
    public function merge(ClientInvestmentLog $clientInvestmentLog)
    {
        $sql = <<<SQL
UPDATE 
  phplist.phplist_caixa_cliente_sem_email
SET 
  no_cliente = ?,
  dt_referencia = ?,
  nu_agencia = ?,
  nu_operacao = ?,
  nu_conta = ?,
  de_email_agencia = ?,
  de_email_cliente = ?
WHERE 
  co_identificador_cliente = ?
  AND nu_modalidade = ?;
SQL;

        $connectionPDO = $this->connection->getPDO();
        $connectionPDOStmt = $connectionPDO->prepare($sql);
        $connectionPDOStmt->execute([
            $clientInvestmentLog->getName(),
            $clientInvestmentLog->getReferenceDate(),
            $clientInvestmentLog->getAgencyNumber(),
            $clientInvestmentLog->getOperationNumber(),
            $clientInvestmentLog->getAccountNumber(),
            $clientInvestmentLog->getAgencyEmail(),
            $clientInvestmentLog->getClientEmail(),
            $clientInvestmentLog->getIdentifier(),
            $clientInvestmentLog->getModalityNumber()
        ]);
    }

    /**
     *
     * @param ClientInvestmentLog $clientInvestmentLog
     *
     * @return void
     */
    public function remove(ClientInvestmentLog $clientInvestmentLog)
    {
        $sql = <<<SQL
DELETE FROM 
  phplist.phplist_caixa_cliente_sem_email
WHERE 
  co_identificador_cliente = ?
  AND nu_modalidade = ?;
SQL;

        $connectionPDO = $this->connection->getPDO();
        $connectionPDOStmt = $connectionPDO->prepare($sql);
        $connectionPDOStmt->execute([
            $clientInvestmentLog->getIdentifier(),
            $clientInvestmentLog->getModalityNumber()
        ]);
    }
}
