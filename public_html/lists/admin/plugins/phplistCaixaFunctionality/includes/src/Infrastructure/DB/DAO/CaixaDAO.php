<?php

namespace phplist\Caixa\Functionality\Infrastructure\DB\DAO;

use phplist\Caixa\Functionality\Domain\Model\Caixa\Client;
use phplist\Caixa\Functionality\Domain\Model\Caixa\ClientEmail;
use phplist\Caixa\Functionality\Domain\Model\Caixa\ClientInvestment;
use phplist\Caixa\Functionality\Infrastructure\Shared\AbstractDAO;

/**
 * Class CaixaDAO
 *
 * @package phplist\Caixa\Functionality\Infrastructure\DB\DAO
 */
class CaixaDAO extends AbstractDAO
{
    public function findAllInvestmentFunds()
    {
        $sql = <<<SQL
select distinct 
  nu_modalidade 
from 
  lissm001.lisvw001_lista_email;
SQL;

        $connectionPDO = $this->connection->getPDO();
        $connectionPDOStmt = $connectionPDO->prepare($sql);
        $connectionPDOStmt->execute();

        $fundNames = [];
        foreach ($connectionPDOStmt->fetchAll(\PDO::FETCH_ASSOC) as $fetched) {
            $fundNames[] = $fetched['nu_modalidade'];
        }

        return $fundNames;
    }

    public function findInvestInvestmentFundsByNuModalidade($nuModalidade)
    {
        $sql = <<<SQL
SELECT * 
FROM lissm001.listb001_fundo_investimento
WHERE nu_modalidade = :nuModalidade
SQL;
        $connectionPDO = $this->connection->getPDO();
        $connectionPDOStmt = $connectionPDO->prepare($sql);
        $connectionPDOStmt->execute([$nuModalidade]);
        return $connectionPDOStmt->fetchAll();

    }

    public function findListEmailByNuModalidade($nuModalidade)
    {
        $sql = <<<SQL
SELECT *
     FROM lissm001.lisvw001_lista_email
     WHERE nu_modalidade = :nuModalidade
SQL;
        $connectionPDO = $this->connection->getPDO();
        $connectionPDOStmt = $connectionPDO->prepare($sql);
        $connectionPDOStmt->execute([$nuModalidade]);
        return $connectionPDOStmt->fetchAll();

    }

    public function refreshListaEmailView()
    {
        $refreshView = <<<SQL
REFRESH MATERIALIZED VIEW lissm001.lisvw001_lista_email
SQL;
        $connectionPDO = $this->connection->getPDO();
        $connectionPDOStmt = $connectionPDO->prepare($refreshView);
        $connectionPDOStmt->execute();

    }

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
}
