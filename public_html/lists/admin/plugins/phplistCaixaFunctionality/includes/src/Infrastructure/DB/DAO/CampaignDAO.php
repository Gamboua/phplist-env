<?php

namespace phplist\Caixa\Functionality\Infrastructure\DB\DAO;

use phplist\Caixa\Functionality\Domain\Model\Campaign;
use phplist\Caixa\Functionality\Domain\Model\CampaignCall;
use phplist\Caixa\Functionality\Domain\Model\CampaignRepository;
use phplist\Caixa\Functionality\Domain\Model\SubscriptionList;
use phplist\Caixa\Functionality\Infrastructure\Shared\AbstractDAO;

/**
 * Class CampaignDAO
 *
 * @package phplist\Caixa\Functionality\Infrastructure\DB\DAO
 */
class CampaignDAO extends AbstractDAO implements CampaignRepository
{
    /**
     * @param integer $id
     *
     * @return Campaign
     */
    public function findOne($id)
    {
        $sql = <<<SQL
SELECT
  id,
  subject,
  fromfield,
  message,
  status,
  template,
  embargo
FROM
  phplist.phplist_message
WHERE
  id = ?;
SQL;

        $connectionPDO = $this->connection->getPDO();
        $connectionPDOStmt = $connectionPDO->prepare($sql);
        $connectionPDOStmt->execute([
            $id,
        ]);

        $campaign = null;
        $fetched = $connectionPDOStmt->fetch(\PDO::FETCH_ASSOC);

        if ($fetched) {
            $campaign = Campaign::fromArray([
                'id' => $fetched['id'],
                'subject' => $fetched['subject'],
                'fromField' => $fetched['fromfield'],
                'message' => $fetched['message'],
                'status' => $fetched['status'],
                'template' => $fetched['template'],
                'embargo' => $fetched['embargo'],
            ]);
        }

        return $campaign;
    }

    /**
     * @param CampaignCall $campaignCall
     *
     * @return integer
     */
    public function countQueuedToBeProcessed(CampaignCall $campaignCall)
    {
        $sql = <<<SQL
SELECT
  count(id)
FROM 
  phplist.phplist_message
WHERE 
  id IN (SELECT message_id FROM phplist.phplist_caixa_campaign_call_list WHERE campaign_call_id = ?)
  AND status in (?, ?)
  AND embargo < now();
SQL;

        $connectionPDO = $this->connection->getPDO();
        $connectionPDOStmt = $connectionPDO->prepare($sql);
        $connectionPDOStmt->execute([
            $campaignCall->getId(),
            Campaign::STATUS_SUBMITTED,
            Campaign::STATUS_INPROCESS,
        ]);

        $fetched = $connectionPDOStmt->fetch();
        return intval($fetched[0]);
    }

    /**
     * @param Campaign $campaign
     *
     * @return void
     */
    public function add(Campaign $campaign)
    {
        $sql = <<<SQL
INSERT INTO
  phplist.phplist_message 
  (
    subject,
    fromfield,
    message,
    status,
    entered,
    sendformat,
    embargo,
    repeatuntil,
    owner,
    template,
    tofield,
    replyto,
    footer,
    uuid
  )
VALUES
  (
    ?,
    ?,
    ?,
    ?,
    now(), 
    'HTML', 
    ?,
    now(), 
    ?, 
    ?, 
    '', 
    '', 
    ?, 
    ?
  );
SQL;
        $defaultFooter = getConfig('messagefooter');
        $loginDetailsId = $_SESSION['logindetails']['id'];

        $connectionPDO = $this->connection->getPDO();
        $connectionPDOStmt = $connectionPDO->prepare($sql);
        $connectionPDOStmt->execute([
            $campaign->getSubject(),
            $campaign->getFromField(),
            $campaign->getMessage(),
            $campaign->getStatus(),
            $campaign->getEmbargo(),
            $loginDetailsId,
            $campaign->getTemplate(),
            $defaultFooter,
            (string)\Uuid::generate(4),
        ]);

        $campaign->setId($connectionPDO->lastInsertId());

        if (!is_null($campaign->getEmbargo())) {

            // set messagedata / embargo
            $this->phpList->setMessageData($campaign->getId(), 'embargo', [
                'year' => date('Y', strtotime($campaign->getEmbargo())),
                'month' => date('m', strtotime($campaign->getEmbargo())),
                'day' => date('d', strtotime($campaign->getEmbargo())),
                'hour' => date('H', strtotime($campaign->getEmbargo())),
                'minute' => date('i', strtotime($campaign->getEmbargo())),
            ]);
        }

        if (!is_null($campaign->getFinishSending())) {

            // set messagedata / finishsending
            $this->phpList->setMessageData($campaign->getId(), 'finishsending', [
                'year' => date('Y', strtotime($campaign->getFinishSending())),
                'month' => date('m', strtotime($campaign->getFinishSending())),
                'day' => date('d', strtotime($campaign->getFinishSending())),
                'hour' => date('H', strtotime($campaign->getFinishSending())),
                'minute' => date('i', strtotime($campaign->getFinishSending())),
            ]);
        }
    }

    /**
     * @param Campaign $campaign
     *
     * @return void
     */
    public function merge(Campaign $campaign)
    {
        $sql = <<<SQL
UPDATE 
  phplist.phplist_message
SET 
  subject = ?,
  fromfield = ?,
  message = ?,
  status = ?,
  template = ?
WHERE 
  id = ?;
SQL;

        $connectionPDO = $this->connection->getPDO();
        $connectionPDOStmt = $connectionPDO->prepare($sql);
        $connectionPDOStmt->execute([
            $campaign->getSubject(),
            $campaign->getFromField(),
            $campaign->getMessage(),
            $campaign->getStatus(),
            $campaign->getTemplate(),
            $campaign->getId(),
        ]);
    }

    /**
     * @param Campaign $campaign
     * @param SubscriptionList $list
     */
    public function linkToList(Campaign $campaign, SubscriptionList $list)
    {
        $sql = <<<SQL
INSERT INTO 
  phplist_listmessage
  (
    messageid,
    listid,
    entered
  ) 
VALUES
  (
    ?,
    ?,
    now()
  );
SQL;

        $connectionPDO = $this->connection->getPDO();
        $connectionPDOStmt = $connectionPDO->prepare($sql);
        $connectionPDOStmt->execute([
            $campaign->getId(),
            $list->getId(),
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
  phplist.phplist_message
WHERE 
  id IN (SELECT message_id FROM phplist.phplist_caixa_campaign_call_list WHERE campaign_call_id = ?);
SQL;

        $connectionPDO = $this->connection->getPDO();
        $connectionPDOStmt = $connectionPDO->prepare($sql);
        $connectionPDOStmt->execute([
            $campaignCall->getId(),
        ]);
    }
}
