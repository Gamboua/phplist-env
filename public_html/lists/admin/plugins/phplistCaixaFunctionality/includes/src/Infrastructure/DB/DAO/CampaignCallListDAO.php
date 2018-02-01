<?php

namespace phplist\Caixa\Functionality\Infrastructure\DB\DAO;

use phplist\Caixa\Functionality\Domain\Model\CampaignCall;
use phplist\Caixa\Functionality\Domain\Model\CampaignCallList;
use phplist\Caixa\Functionality\Domain\Model\CampaignCallListRepository;
use phplist\Caixa\Functionality\Domain\Model\SubscriptionList;
use phplist\Caixa\Functionality\Infrastructure\Shared\AbstractDAO;

/**
 * Class CampaignCallListDAO
 *
 * @package phplist\Caixa\Functionality\Infrastructure\DB\DAO
 */
class CampaignCallListDAO extends AbstractDAO implements CampaignCallListRepository
{
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
