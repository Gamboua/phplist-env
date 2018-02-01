<?php

namespace phplist\Caixa\Functionality\Infrastructure\DB\DAO;

use phplist\Caixa\Functionality\Domain\Model\CampaignCall;
use phplist\Caixa\Functionality\Domain\Model\CampaignCallRepository;
use phplist\Caixa\Functionality\Domain\Model\User;
use phplist\Caixa\Functionality\Infrastructure\DB\PageRequest;
use phplist\Caixa\Functionality\Infrastructure\Shared\AbstractDAO;

/**
 * Class UserSubscriptionDAO
 *
 * @package phplist\Caixa\Functionality\Infrastructure\DB\DAO
 */
class CampaignCallDAO extends AbstractDAO implements CampaignCallRepository
{
    /**
     * @param integer $id
     *
     * @return CampaignCall mixed
     */
    public function findOne($id)
    {
        $sql = <<<SQL
SELECT 
  id,
  subject,
  fromfield,
  message,
  template,
  communication_type,
  embargo,
  finish_sending,
  status
FROM 
  phplist.phplist_caixa_campaign_call
WHERE 
  id = ?;
SQL;

        $connectionPDO = $this->connection->getPDO();
        $connectionPDOStmt = $connectionPDO->prepare($sql);
        $connectionPDOStmt->execute([
            $id,
        ]);

        $campaignCall = null;
        $fetched = $connectionPDOStmt->fetch(\PDO::FETCH_ASSOC);

        if ($fetched) {
            $campaignCall = CampaignCall::fromArray([
                'id' => $fetched['id'],
                'subject' => $fetched['subject'],
                'fromField' => $fetched['fromfield'],
                'message' => $fetched['message'],
                'template' => $fetched['template'],
                'communicationType' => $fetched['communication_type'],
                'embargo' => $fetched['embargo'],
                'finishSending' => $fetched['finish_sending'],
                'status' => $fetched['status'],
            ]);
        }

        return $campaignCall;
    }

    /**
     * @param PageRequest $pageRequest
     *
     * @return CampaignCall[]
     */
    public function findAll(PageRequest $pageRequest)
    {
        $sql = <<<SQL
select
  id,
  subject,
  fromfield,
  message,
  template,
  communication_type,
  embargo,
  finish_sending,
  status
from 
  phplist.phplist_caixa_campaign_call
order by 
  embargo desc
limit {$pageRequest->getNumberPerPage()}
offset {$pageRequest->getStart()};
SQL;

        $connectionPDO = $this->connection->getPDO();
        $connectionPDOStmt = $connectionPDO->prepare($sql);
        $connectionPDOStmt->execute();

        $campaignCalls = [];
        foreach ($connectionPDOStmt->fetchAll(\PDO::FETCH_ASSOC) as $fetched) {
            $campaignCalls[] = CampaignCall::fromArray([
                'id' => $fetched['id'],
                'subject' => $fetched['subject'],
                'fromField' => $fetched['fromfield'],
                'message' => $fetched['message'],
                'template' => $fetched['template'],
                'communicationType' => $fetched['communication_type'],
                'embargo' => $fetched['embargo'],
                'finishSending' => $fetched['finish_sending'],
                'status' => $fetched['status'],
            ]);
        }

        return $campaignCalls;
    }

    /**
     * @return integer
     */
    public function countAll()
    {
        $sql = <<<SQL
select 
  count(*)
from 
  phplist.phplist_caixa_campaign_call;
SQL;

        $connectionPDO = $this->connection->getPDO();
        $connectionPDOStmt = $connectionPDO->prepare($sql);
        $connectionPDOStmt->execute();

        $fetched = $connectionPDOStmt->fetch();
        return intval($fetched[0]);
    }

    /**
     * @param CampaignCall $campaignCall
     *
     * @return void
     */
    public function add(CampaignCall $campaignCall)
    {
        $sql = <<<SQL
INSERT INTO 
  phplist.phplist_caixa_campaign_call
  (
    subject,
    fromfield,
    message,
    template,
    communication_type,
    embargo,
    finish_sending,
    status
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
            $campaignCall->getSubject(),
            $campaignCall->getFromField(),
            $campaignCall->getMessage(),
            $campaignCall->getTemplate(),
            $campaignCall->getCommunicationType(),
            $campaignCall->getEmbargo(),
            $campaignCall->getFinishSending(),
            $campaignCall->getStatus(),
        ]);

        $campaignCall->setId($connectionPDO->lastInsertId());
    }

    /**
     * @param CampaignCall $campaignCall
     *
     * @return void
     */
    public function merge(CampaignCall $campaignCall)
    {
        $sql = <<<SQL
UPDATE 
  phplist.phplist_caixa_campaign_call
SET 
  subject = ?,
  fromfield = ?,
  message = ?,
  template = ?,
  communication_type = ?,
  embargo = ?,
  finish_sending = ?,
  status = ?,
  user_id = ?,
  user_email = ?,
  template_content = ?
WHERE 
  id = ?;
SQL;

        $connectionPDO = $this->connection->getPDO();
        $connectionPDOStmt = $connectionPDO->prepare($sql);
        $connectionPDOStmt->execute([
            $campaignCall->getSubject(),
            $campaignCall->getFromField(),
            $campaignCall->getMessage(),
            $campaignCall->getTemplate(),
            $campaignCall->getCommunicationType(),
            $campaignCall->getEmbargo(),
            $campaignCall->getFinishSending(),
            $campaignCall->getStatus(),
            $campaignCall->getUserId(),
            $campaignCall->getUserEmail(),
            $campaignCall->getTemplateContent(),
            $campaignCall->getId(),
        ]);
    }

    public function getUserLogged($userId)
    {
        $sql = <<<SQL
SELECT 
  id,
  email
FROM 
  phplist.phplist_user_user
WHERE 
  id = ?;
SQL;

        $connectionPDO = $this->connection->getPDO();
        $connectionPDOStmt = $connectionPDO->prepare($sql);
        $connectionPDOStmt->execute([$userId]);

        $user = null;
        $fetched = $connectionPDOStmt->fetch(\PDO::FETCH_ASSOC);

        if ($fetched) {
            $user = User::fromArray([
                'id' => $fetched['id'],
                'email' => $fetched['email'],
            ]);
        }

        return $user;
    }

    public function getTemplateContent($templateId) {
        $sql = <<<SQL
SELECT 
  template
FROM 
  phplist_template
WHERE 
  id = ?;
SQL;

        $connectionPDO = $this->connection->getPDO();
        $connectionPDOStmt = $connectionPDO->prepare($sql);
        $connectionPDOStmt->execute([$templateId]);

        $fetched = $connectionPDOStmt->fetch();

        return $fetched[0];

    }
}
