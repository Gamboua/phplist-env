<?php

namespace phplist\Caixa\Functionality\Infrastructure\DB\DAO;

use phplist\Caixa\Functionality\Domain\Model\Caixa\Client;
use phplist\Caixa\Functionality\Domain\Model\Caixa\ClientInvestment;
use phplist\Caixa\Functionality\Domain\Model\Caixa\ClientInvestmentRepository;
use phplist\Caixa\Functionality\Domain\Model\Caixa\InvestmentFund;
use phplist\Caixa\Functionality\Infrastructure\Shared\AbstractDAO;
use phplist\Caixa\Functionality\Infrastructure\Shared\PHPListCaixaLogger;

/**
 * Class ClientInvestmentDAO
 *
 * @package phplist\Caixa\Functionality\Infrastructure\DB\DAO
 */
class ClientInvestmentDAO extends AbstractDAO implements ClientInvestmentRepository
{
    public function getTables()
    {
        return [
            'fundo_investimento' => 'lissm001.listb001_fundo_investimento',
        ];
    }

    public function getTablePrefix()
    {
        return '';
    }

    /**
     * @param $modalityNumber
     *
     * @return ClientInvestment[]
     */
    public function findAllByModalityNumber($modalityNumber)
    {
        $sql = <<<SQL
SELECT 
  *
FROM 
  lissm001.lisvw001_lista_email
WHERE 
  nu_modalidade = ?;
SQL;

        $connectionPDO = $this->connection->getPDO();
        $connectionPDOStmt = $connectionPDO->prepare($sql);
        $connectionPDOStmt->execute([
            $modalityNumber,
        ]);

        $clientInvestments = [];
        foreach ($connectionPDOStmt->fetchAll(\PDO::FETCH_ASSOC) as $fetched) {

            $client = Client::fromArray([
                'identifier' => $fetched['co_identificador_cliente'],
                'email' => $fetched['de_email_cliente'],
                'name' => $fetched['no_cliente'],
            ]);

            $investmentFund = InvestmentFund::fromArray([
                'referenceDate' => $fetched['dt_referencia'],
                'agencyNumber' => $fetched['nu_agencia'],
                'operationNumber' => $fetched['nu_operacao'],
                'accountNumber' => $fetched['nu_conta'],
                'agencyEmail' => $fetched['de_email_agencia'],
                'modalityNumber' => $fetched['nu_modalidade'],
            ]);

            $clientInvestments[] = new ClientInvestment($client, $investmentFund);
        }
        
        $readingTime = microtime(true) - $_SERVER['REQUEST_TIME'];
               
        $this->getLogger()->generateLogReadingTimeView($readingTime);

        return $clientInvestments;
    }

    /**
     * @param $modalityNumber
     * @param callable $callback
     * @return void
     */
    public function fromWithinFindAllByModalityNumber($modalityNumber, callable $callback)
    {
        $sql = <<<SQL
SELECT
  *
FROM
  lissm001.lisvw001_lista_email
WHERE
  nu_modalidade = ?;
SQL;

        $connectionPDO = $this->connection->getPDO();
        $connectionPDOStmt = $connectionPDO->prepare($sql);
        $connectionPDOStmt->execute([
            $modalityNumber,
        ]);

        while ($fetched = $connectionPDOStmt->fetch(\PDO::FETCH_ASSOC)) {

            $client = Client::fromArray([
                'identifier' => $fetched['co_identificador_cliente'],
                'email' => $fetched['de_email_cliente'],
                'name' => $fetched['no_cliente'],
            ]);

            $investmentFund = InvestmentFund::fromArray([
                'referenceDate' => $fetched['dt_referencia'],
                'agencyNumber' => $fetched['nu_agencia'],
                'operationNumber' => $fetched['nu_operacao'],
                'accountNumber' => $fetched['nu_conta'],
                'agencyEmail' => $fetched['de_email_agencia'],
                'modalityNumber' => $fetched['nu_modalidade'],
            ]);

            $callback(new ClientInvestment($client, $investmentFund));
        }
    }

    /**
     * @return integer[]
     */
    public function collectModalityNumbers()
    {
        $sql = <<<SQL
SELECT DISTINCT 
  nu_modalidade 
FROM 
  lissm001.lisvw001_lista_email;
SQL;

        $connectionPDO = $this->connection->getPDO();
        $connectionPDOStmt = $connectionPDO->prepare($sql);
        $connectionPDOStmt->execute();

        $investmentFunds = [];
        foreach ($connectionPDOStmt->fetchAll(\PDO::FETCH_ASSOC) as $fetched) {
            $investmentFunds[] = $fetched['nu_modalidade'];
        }

        return $investmentFunds;
    }
}
