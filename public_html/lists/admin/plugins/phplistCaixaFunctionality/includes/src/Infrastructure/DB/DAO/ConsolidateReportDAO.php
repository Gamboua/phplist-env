<?php

namespace phplist\Caixa\Functionality\Infrastructure\DB\DAO;

use phplist\Caixa\Functionality\Domain\Model\ConsolidateReportRepository;
use phplist\Caixa\Functionality\Domain\Shared\ConsolidatedReportQueryObject;
use phplist\Caixa\Functionality\Infrastructure\Shared\AbstractDAO;

/**
 * Class ConsolidateReportDAO
 *
 * @package phplist\Caixa\Functionality\Infrastructure\DB\DAO
 */
class ConsolidateReportDAO extends AbstractDAO implements ConsolidateReportRepository
{

    /**
     * @param ConsolidatedReportQueryObject $queryObject
     *
     * @return array
     */
    public function executeQueryObject(ConsolidatedReportQueryObject $queryObject)
    {
        $sql = <<<SQL
SELECT 
    um.status as status,
	count(um.status) as total
FROM 
	phplist_usermessage um 
	INNER JOIN phplist_user_user_attribute AS uua1 ON uua1.userid = um.userid AND uua1.attributeid = 1 
	INNER JOIN phplist_listmessage AS lm ON lm.messageid = um.messageid
	INNER JOIN phplist_list AS l ON l.id = lm.listid
	INNER JOIN phplist_caixa_campaign_call_list AS cccl ON cccl.list_id = l.id AND cccl.message_id = um.messageid
	INNER JOIN phplist_caixa_campaign_call AS ccc ON ccc.id = cccl.campaign_call_id
WHERE 
    1 = 1
    ## condition01
    ## condition02
    ## condition03
    ## condition04
    ## condition05
GROUP BY
    um.status;
SQL;

        $inputParameters = [];

        if ($queryObject->getFundName() && strlen(trim($queryObject->getFundName())) > 0) {
            $sql = str_replace('## condition01', "AND l.name = ?", $sql);
            $inputParameters[] = $queryObject->getFundName();
        }

        if ($queryObject->getFundGroup() && strlen(trim($queryObject->getFundGroup())) > 0) {
            $sql = str_replace('## condition02', "AND l.category = ?", $sql);
            $inputParameters[] = $queryObject->getFundGroup();
        }

        if (
            ($queryObject->getMessageDateStarted() && strlen(trim($queryObject->getMessageDateStarted())) > 0)
            && ($queryObject->getMessageDateFinished() && strlen(trim($queryObject->getMessageDateFinished())) > 0)
        ) {
            $sql = str_replace('## condition03', "AND um.entered BETWEEN ? AND ?", $sql);
            $inputParameters[] = $queryObject->getMessageDateStarted();
            $inputParameters[] = $queryObject->getMessageDateFinished();
        }

        if ($queryObject->getClientIdentifier() && strlen(trim($queryObject->getClientIdentifier())) > 0) {
            $sql = str_replace('## condition04', "AND uua1.value = ?", $sql);
            $inputParameters[] = $queryObject->getClientIdentifier();
        }

        if ($queryObject->getCommunicationType() && strlen(trim($queryObject->getCommunicationType())) > 0) {
            $sql = str_replace('## condition05', "AND ccc.communication_type = ?", $sql);
            $inputParameters[] = $queryObject->getCommunicationType();
        }

        $connectionPDO =  $this->connection->getPDO();
        $connectionPDOStmt = $connectionPDO->prepare($sql);
        $connectionPDOStmt->execute($inputParameters);

        $fetched = $connectionPDOStmt->fetchAll();
        return $fetched;
    }

}
