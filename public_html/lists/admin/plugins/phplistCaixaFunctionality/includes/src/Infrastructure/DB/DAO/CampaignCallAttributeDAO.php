<?php

namespace phplist\Caixa\Functionality\Infrastructure\DB\DAO;

use phplist\Caixa\Functionality\Domain\Model\CampaignCall;
use phplist\Caixa\Functionality\Domain\Model\CampaignCallAttribute;
use phplist\Caixa\Functionality\Domain\Model\CampaignCallAttributeRepository;
use phplist\Caixa\Functionality\Domain\Model\CampaignCallList;
use phplist\Caixa\Functionality\Domain\Model\SubscriptionList;
use phplist\Caixa\Functionality\Infrastructure\Shared\AbstractDAO;

/**
 * Class CampaignCallAttributeDAO
 *
 * @package phplist\Caixa\Functionality\Infrastructure\DB\DAO
 */
class CampaignCallAttributeDAO extends AbstractDAO implements CampaignCallAttributeRepository
{
    /**
     * @param CampaignCall $campaignCall
     * @param SubscriptionList $list
     * @param string $name
     *
     * @return CampaignCallAttribute
     */
    public function findOne(CampaignCall $campaignCall, SubscriptionList $list, $name)
    {
        $sql = <<<SQL
SELECT 
  campaign_call_id,
  list_id,
  name,
  value
FROM 
  phplist.phplist_caixa_campaign_call_attribute
WHERE 
  campaign_call_id = ?
  AND list_id = ?
  AND name = ?;
SQL;

        $connectionPDO = $this->connection->getPDO();
        $connectionPDOStmt = $connectionPDO->prepare($sql);
        $connectionPDOStmt->execute([
            $campaignCall->getId(),
            $list->getId(),
            $name,
        ]);

        $campaignCallAttribute = null;
        $fetched = $connectionPDOStmt->fetch(\PDO::FETCH_ASSOC);

        if ($fetched) {
            $campaignCallAttribute = CampaignCallAttribute::fromArray([
                'campaignCall' => $campaignCall,
                'subscriptionList' => $list,
                'name' => $fetched['name'],
                'value' => $fetched['value'],
            ]);
        }

        return $campaignCallAttribute;
    }

    /**
     * @param CampaignCall $campaignCall
     *
     * @return CampaignCallAttribute[]
     */
    public function findAllByCampaignCall(CampaignCall $campaignCall)
    {
        $sql = <<<SQL
SELECT
  list.id as list_id,
  list.name as list_name,
  campaigncalattr.name as name,
  campaigncalattr.value as value
FROM
  phplist.phplist_caixa_campaign_call_attribute campaigncalattr
  INNER JOIN phplist.phplist_list AS list ON list.id = campaigncalattr.list_id
WHERE
  campaigncalattr.campaign_call_id = ?
ORDER BY
  campaigncalattr.list_id ASC;
SQL;

        $connectionPDO = $this->connection->getPDO();
        $connectionPDOStmt = $connectionPDO->prepare($sql);
        $connectionPDOStmt->execute([
            $campaignCall->getId(),
        ]);

        $campaignCallAttributes = [];
        foreach ($connectionPDOStmt->fetchAll(\PDO::FETCH_ASSOC) as $fetched) {

            $list = new SubscriptionList();
            $list->setId($fetched['list_id']);
            $list->setName($fetched['list_name']);

            $campaignCallAttributes[] = CampaignCallAttribute::fromArray([
                'campaignCall' => $campaignCall,
                'subscriptionList' => $list,
                'name' => $fetched['name'],
                'value' => $fetched['value'],
            ]);
        }

        return $campaignCallAttributes;
    }

    /**
     * @param CampaignCallList $campaignCallList
     *
     * @return CampaignCallAttribute[]
     */
    public function findAllByCampaignCallList(CampaignCallList $campaignCallList)
    {
        $sql = <<<SQL
SELECT
  campaign_call_id,
  list_id,
  name,
  value
FROM
  phplist.phplist_caixa_campaign_call_attribute
WHERE
  campaign_call_id = ?
  AND list_id = ?
ORDER BY
  list_id ASC;
SQL;

        $connectionPDO = $this->connection->getPDO();
        $connectionPDOStmt = $connectionPDO->prepare($sql);
        $connectionPDOStmt->execute([
            $campaignCallList->getCampaignCall()->getId(),
            $campaignCallList->getSubscriptionList()->getId(),
        ]);

        $campaignCallAttributes = [];
        foreach ($connectionPDOStmt->fetchAll(\PDO::FETCH_ASSOC) as $fetched) {
            $campaignCallAttributes[] = CampaignCallAttribute::fromArray([
                'campaignCall' => $campaignCallList->getCampaignCall(),
                'subscriptionList' => $campaignCallList->getSubscriptionList(),
                'name' => $fetched['name'],
                'value' => $fetched['value'],
            ]);
        }

        return $campaignCallAttributes;
    }

    /**
     * @param CampaignCallAttribute $campaignCallAttribute
     *
     * @return void
     */
    public function add(CampaignCallAttribute $campaignCallAttribute)
    {
        $sql = <<<SQL
INSERT INTO 
  phplist.phplist_caixa_campaign_call_attribute
  (
    campaign_call_id,
    list_id,
    name,
    value
  )
VALUES
  (
    ?,
    ?,
    ?,
    ?
  );
SQL;

        $connectionPDO = $this->connection->getPDO();
        $connectionPDOStmt = $connectionPDO->prepare($sql);
        $connectionPDOStmt->execute([
            $campaignCallAttribute->getCampaignCall()->getId(),
            $campaignCallAttribute->getSubscriptionList()->getId(),
            $campaignCallAttribute->getName(),
            $campaignCallAttribute->getValue(),
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
  phplist.phplist_caixa_campaign_call_attribute
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
