<?php

namespace phplist\Caixa\Functionality\Infrastructure\DB\DAO;

use phplist\Caixa\Functionality\Domain\Model\InvestmentFundSubscription;
use phplist\Caixa\Functionality\Domain\Model\InvestmentFundSubscriptionRepository;
use phplist\Caixa\Functionality\Domain\Model\SubscriptionList;
use phplist\Caixa\Functionality\Domain\Model\User;
use phplist\Caixa\Functionality\Infrastructure\Shared\AbstractDAO;

/**
 * Class InvestmentFundSubscriptionDAO
 *
 * @package phplist\Caixa\Functionality\Infrastructure\DB\DAO
 */
class InvestmentFundSubscriptionDAO extends AbstractDAO implements InvestmentFundSubscriptionRepository
{
    /**
     * @param User $user
     * @param SubscriptionList $subscriptionList
     *
     * @return InvestmentFundSubscription
     */
    public function findOne(User $user, SubscriptionList $subscriptionList)
    {
        $sql = <<<SQL
SELECT 
  user_id,
  list_id,
  reference_date,
  agency_number,
  operation_number,
  account_number,
  agency_email,
  modality_number
FROM 
  phplist.phplist_caixa_list_investment_fund
WHERE 
  user_id = ?
  AND list_id = ?;
SQL;

        $connectionPDO = $this->connection->getPDO();
        $connectionPDOStmt = $connectionPDO->prepare($sql);
        $connectionPDOStmt->execute([
            $user->getId(),
            $subscriptionList->getId(),
        ]);

        $investmentFundSubscription = null;
        $fetched = $connectionPDOStmt->fetch(\PDO::FETCH_ASSOC);

        if ($fetched) {
            $investmentFundSubscription = InvestmentFundSubscription::fromArray([
                'user' => $user,
                'subscriptionList' => $subscriptionList,
                'referenceDate' => $fetched['reference_date'],
                'agencyNumber' => $fetched['agency_number'],
                'operationNumber' => $fetched['operation_number'],
                'accountNumber' => $fetched['account_number'],
                'agencyEmail' => $fetched['agency_email'],
                'modalityNumber' => $fetched['modality_number'],
            ]);
        }

        return $investmentFundSubscription;
    }

    /**
     * @param InvestmentFundSubscription $investmentFundSubscription
     *
     * @return void
     */
    public function add(InvestmentFundSubscription $investmentFundSubscription)
    {
        $sql = <<<SQL
INSERT INTO 
  phplist.phplist_caixa_list_investment_fund
  (
    user_id,
    list_id,
    reference_date,
    agency_number,
    operation_number,
    account_number,
    agency_email,
    modality_number
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
    ?
  );
SQL;

        $connectionPDO = $this->connection->getPDO();
        $connectionPDOStmt = $connectionPDO->prepare($sql);
        $connectionPDOStmt->execute([
            $investmentFundSubscription->getUser()->getId(),
            $investmentFundSubscription->getSubscriptionList()->getId(),
            $investmentFundSubscription->getReferenceDate(),
            $investmentFundSubscription->getAgencyNumber(),
            $investmentFundSubscription->getOperationNumber(),
            $investmentFundSubscription->getAccountNumber(),
            $investmentFundSubscription->getAgencyEmail(),
            $investmentFundSubscription->getModalityNumber(),
        ]);
    }

    /**
     * @param InvestmentFundSubscription $investmentFundSubscription
     *
     * @return void
     */
    public function merge(InvestmentFundSubscription $investmentFundSubscription)
    {
        $sql = <<<SQL
UPDATE 
  phplist.phplist_caixa_list_investment_fund
SET 
  reference_date = ?,
  agency_number = ?,
  operation_number = ?,
  account_number = ?,
  agency_email = ?,
  modality_number = ?
WHERE 
  user_id = ?
  AND list_id = ?;
SQL;

        $connectionPDO = $this->connection->getPDO();
        $connectionPDOStmt = $connectionPDO->prepare($sql);
        $connectionPDOStmt->execute([
            $investmentFundSubscription->getReferenceDate(),
            $investmentFundSubscription->getAgencyNumber(),
            $investmentFundSubscription->getOperationNumber(),
            $investmentFundSubscription->getAccountNumber(),
            $investmentFundSubscription->getAgencyEmail(),
            $investmentFundSubscription->getModalityNumber(),
            $investmentFundSubscription->getUser()->getId(),
            $investmentFundSubscription->getSubscriptionList()->getId(),
        ]);
    }
}
