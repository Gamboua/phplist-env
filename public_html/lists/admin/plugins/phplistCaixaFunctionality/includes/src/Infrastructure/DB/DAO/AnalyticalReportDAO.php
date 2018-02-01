<?php

namespace phplist\Caixa\Functionality\Infrastructure\DB\DAO;

use phplist\Caixa\Functionality\Domain\Model\AnalyticalReport;
use phplist\Caixa\Functionality\Domain\Model\AnalyticalReportRepository;
use phplist\Caixa\Functionality\Domain\Model\CampaignCall;
use phplist\Caixa\Functionality\Domain\Model\CampaignCallList;
use phplist\Caixa\Functionality\Domain\Model\SubscriptionList;
use phplist\Caixa\Functionality\Infrastructure\DB\PageRequest;
use phplist\Caixa\Functionality\Infrastructure\Shared\AbstractDAO;
use phplist\Caixa\Functionality\Interfaces\Models\AnalyticalReportModel;

/**
 * Class AnalyticalReportDAO
 *
 * @package phplist\Caixa\Functionality\Infrastructure\DB\DAO
 */
class AnalyticalReportDAO extends AbstractDAO implements AnalyticalReportRepository
{
    public function findAll(AnalyticalReportModel $model, $exportData)
    {
        $sql = <<<SQL
SELECT 
	l.category as category,
	l.name as fund,
	clif.account_number as account,
	uua1.value as registration,
	uu.email as email,
	um.entered as dtSent,
	uu.bouncecount as attempts,
	um.status as status,
	um.userid as userId,
	um.messageid as messageId
FROM 
	phplist_usermessage um
	INNER JOIN phplist_user_user AS uu ON uu.id = um.userid
	INNER JOIN phplist_user_user_attribute AS uua1 ON uua1.attributeid = 1 AND uua1.userid = um.userid
	INNER JOIN phplist_listmessage AS lm ON lm.messageid = um.messageid
	INNER JOIN phplist_list AS l ON l.id = lm.listid
	INNER JOIN phplist_caixa_list_investment_fund AS clif ON clif.user_id = um.userid AND clif.list_id = l.id
WHERE 1=1	

SQL;
        $connectionPDO = $this->connection->getPDO();

        $sql = $this->setQueryConditions($sql, $model);

        if($model->getOrderBy()) {
            $sql .= "ORDER BY {$model->getOrderBy()} {$model->getSort()} ";
        }

        if(!$exportData) {
            $sql .= "LIMIT {$model->getPageRequest()->getNumberPerPage()} ";
            $sql .= "OFFSET {$model->getPageRequest()->getStart()} ";
        }

        $connectionPDOStmt = $connectionPDO->prepare($sql);
        $connectionPDOStmt->execute();

        $analyticalReport = [];
        foreach ($connectionPDOStmt->fetchAll(\PDO::FETCH_ASSOC) as $fetched) {
            $analyticalReport[] = AnalyticalReport::fromArray([
                'group' => $fetched['category'],
                'fund' => $fetched['fund'],
                'account' => $fetched['account'],
                'registration' => $fetched['registration'],
                'email' => $fetched['email'],
                'dtSent' => $fetched['dtSent'],
                'attempts' => $fetched['attempts'],
                'status' => $fetched['status'],
                'message' => isset($fetched['message']) ? $fetched['message'] : null,
                'userId' => $fetched['userId'],
                'messageId' => $fetched['messageId']
            ]);
        }

        return $analyticalReport;

    }

    /**
     * @return integer
     */
    public function countAll(AnalyticalReportModel $model)
    {
        $sql = <<<SQL
SELECT 
  count(*)
  FROM 
	phplist_usermessage um
	INNER JOIN phplist_user_user AS uu ON uu.id = um.userid
	INNER JOIN phplist_user_user_attribute AS uua1 ON uua1.attributeid = 1 AND uua1.userid = um.userid
	INNER JOIN phplist_listmessage AS lm ON lm.messageid = um.messageid
	INNER JOIN phplist_list AS l ON l.id = lm.listid
	INNER JOIN phplist_caixa_list_investment_fund AS clif ON clif.user_id = um.userid AND clif.list_id = l.id
WHERE 1=1
SQL;

        $connectionPDO = $this->connection->getPDO();

        $sql = $this->setQueryConditions($sql, $model);

        $connectionPDOStmt = $connectionPDO->prepare($sql);
        $connectionPDOStmt->execute();

        $fetched = $connectionPDOStmt->fetch();
        return intval($fetched[0]);
    }

    private function setQueryConditions($sql, AnalyticalReportModel $model) {
        if($model->getCategory()) {
            $sql .= " AND l.category LIKE '%{$model->getCategory()}%' ";
        }

        if($model->getFund()) {
            $sql .= " AND l.name LIKE '%{$model->getFund()}%' ";
        }

        if($model->getAccount()) {
            $sql .= " AND clif.account_number LIKE '%{$model->getAccount()}%' ";
        }

        if($model->getRegistration()) {
            $sql .= " AND uua1.value LIKE '%{$model->getRegistration()}%' ";
        }

        if($model->getEmail()) {
            $sql .= " AND uu.email LIKE '%{$model->getEmail()}%' ";
        }

        if($model->getAttempts()) {
            $sql .= " AND uu.bouncecount = '{$model->getAttempts()}' ";
        }

        if($model->getStatus()) {
            $sql .= " AND um.status = '{$model->getStatus()}' ";
        }

        if($model->getMessageDateStarted() && $model->getMessageDateFinished()) {
            $sql .= " AND um.entered BETWEEN '{$model->getMessageDateStarted()}' AND '{$model->getMessageDateFinished()}' ";
        }

        return $sql;

    }

    public function findAllSorted(AnalyticalReport $analyticalReport)
    {
        // TODO: Implement findAllSorted() method.
    }


    /**
     * @param CampaignCall $campaignCall
     * @param SubscriptionList $list
     *
     * @return CampaignCallList
     */
    public function findOne(CampaignCall $campaignCall, SubscriptionList $list)
    {
        $sql = <<<SQL
SELECT 
  campaign_call_id,
  list_id,
  message_id
FROM 
  phplist.phplist_caixa_campaign_call_list
WHERE 
  campaign_call_id = ?
  AND list_id = ?;
SQL;

        $connectionPDO = $this->connection->getPDO();
        $connectionPDOStmt = $connectionPDO->prepare($sql);
        $connectionPDOStmt->execute([
            $campaignCall->getId(),
            $list->getId(),
        ]);

        $campaignCallList = null;
        $fetched = $connectionPDOStmt->fetch(\PDO::FETCH_ASSOC);

        if ($fetched) {
            $campaignCallList = CampaignCallList::fromArray([
                'campaignCall' => $campaignCall,
                'subscriptionList' => $list,
                'messageId' => $fetched['message_id'],
            ]);
        }

        return $campaignCallList;
    }

    /**
     * @param integer $messageId
     *
     * @return CampaignCallList
     */
    public function findOneByMessageId($messageId)
    {
        $sql = <<<SQL
SELECT
  campaigncalllist.campaign_call_id as campaign_call_id,
  campaigncalllist.list_id as list_id,
  campaigncalllist.message_id as message_id
FROM
  phplist.phplist_caixa_campaign_call_list AS campaigncalllist
  INNER JOIN phplist.phplist_caixa_campaign_call AS campaigncall ON campaigncall.id = campaigncalllist.campaign_call_id
  INNER JOIN phplist.phplist_list AS list ON list.id = campaigncalllist.list_id
WHERE
  campaigncalllist.message_id = ?;
SQL;

        $connectionPDO = $this->connection->getPDO();
        $connectionPDOStmt = $connectionPDO->prepare($sql);
        $connectionPDOStmt->execute([
            $messageId,
        ]);

        $campaignCallList = null;
        $fetched = $connectionPDOStmt->fetch(\PDO::FETCH_ASSOC);

        if ($fetched) {

            $campaignCall = new CampaignCall();
            $campaignCall->setId($fetched['campaign_call_id']);

            $subscriptionList = new SubscriptionList();
            $subscriptionList->setId($fetched['list_id']);

            $campaignCallList = CampaignCallList::fromArray([
                'campaignCall' => $campaignCall,
                'subscriptionList' => $subscriptionList,
                'messageId' => $fetched['message_id'],
            ]);
        }

        return $campaignCallList;
    }

    /**
     * @param CampaignCall $campaignCall
     *
     * @return CampaignCallList[]
     */
    public function findAllByCampaignCall(CampaignCall $campaignCall)
    {
        $sql = <<<SQL
SELECT
  campaigncalllist.campaign_call_id AS campaign_call_id,
  campaigncalllist.list_id AS list_id,
  campaigncalllist.message_id AS message_id,
  list.name as list_name
FROM 
  phplist.phplist_caixa_campaign_call_list AS campaigncalllist
  INNER JOIN phplist.phplist_list AS list ON list.id = campaigncalllist.list_id
WHERE 
  campaigncalllist.campaign_call_id = ?
ORDER BY
  campaigncalllist.list_id;
SQL;

        $connectionPDO = $this->connection->getPDO();
        $connectionPDOStmt = $connectionPDO->prepare($sql);
        $connectionPDOStmt->execute([
            $campaignCall->getId(),
        ]);

        $campaignCallLists = [];
        foreach ($connectionPDOStmt->fetchAll(\PDO::FETCH_ASSOC) as $fetched) {

            $subscriptionList = new SubscriptionList();
            $subscriptionList->setId($fetched['list_id']);
            $subscriptionList->setName($fetched['list_name']);

            $campaignCallLists[] = CampaignCallList::fromArray([
                'campaignCall' => $campaignCall,
                'subscriptionList' => $subscriptionList,
                'messageId' => $fetched['message_id'],
            ]);

        }

        return $campaignCallLists;
    }

    /**
     * @param CampaignCallList $campaignCallList
     *
     * @return void
     */
    public function add(CampaignCallList $campaignCallList)
    {
        $sql = <<<SQL
INSERT INTO 
  phplist.phplist_caixa_campaign_call_list
  (
    campaign_call_id,
    list_id,
    message_id
  )
VALUES
  (
    ?,
    ?,
    ?
  );
SQL;

        $connectionPDO = $this->connection->getPDO();
        $connectionPDOStmt = $connectionPDO->prepare($sql);
        $connectionPDOStmt->execute([
            $campaignCallList->getCampaignCall()->getId(),
            $campaignCallList->getSubscriptionList()->getId(),
            $campaignCallList->getMessageId(),
        ]);
    }

    /**
     * @param CampaignCall $campaignCall
     *
     * @return void
     */
    public function removeAllByCampaignCall(CampaignCall $campaignCall)
    {
        $sql = <<<SQL
DELETE FROM 
  phplist.phplist_caixa_campaign_call_list
WHERE 
  campaign_call_id = ?;
SQL;

        $connectionPDO = $this->connection->getPDO();
        $connectionPDOStmt = $connectionPDO->prepare($sql);
        $connectionPDOStmt->execute([
            $campaignCall->getId(),
        ]);
    }
}
