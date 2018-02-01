<?php

namespace phplist\Caixa\Functionality\Interfaces\Controllers\AnalyticalReport;

use phplist\Caixa\Functionality\Domain\Model\CampaignCall;
use phplist\Caixa\Functionality\Domain\Shared\ConsolidatedReportQueryObject;
use phplist\Caixa\Functionality\Infrastructure\DB\Connection;
use phplist\Caixa\Functionality\Interfaces\Shared\AbstractController;

/**
 * Class ViewController
 *
 * @package phplist\Caixa\Functionality\Interfaces\Controllers\AnalyticalReport
 */
class ViewController extends AbstractController
{
    public function __invoke()
    {
        $messageId = sprintf('%d', $_GET['messageId']);
        $userId = sprintf('%d', $_GET['userId']);

        echo $this->render('reports/analyticalreport/view', [
            'betterStatusNames' => ConsolidatedReportQueryObject::betterStatusNames(),
            'betterEditStatusNames' => $this->getBetterEditStatusNames(),
            'communicationTypes' => $this->getCommunicationTypes(),
            'templates' => $this->getTemplates(),
            'messageId' => $messageId,
            'userId' => $userId,
            'foundData' => $this->executeFindOne($messageId, $userId),
        ]);
    }

    /**
     * @return array
     */
    private function getCommunicationTypes()
    {
        return [
            CampaignCall::TYPE_COMMUNICATION => 'Comunicado',
            CampaignCall::TYPE_CONVOCATION => 'Convocação',
            CampaignCall::TYPE_RELEVANT_FACT => 'Fato Relevante',
            CampaignCall::TYPE_SUMMARY => 'Resumo',
        ];
    }

    /**
     * @return array
     */
    private function getBetterEditStatusNames()
    {
        return [
            CampaignCall::STATUS_DRAFT => 'Pendente',
            CampaignCall::STATUS_SUBMITTED => 'Finalizado',
        ];
    }

    /**
     * @return array
     */
    private function getTemplates()
    {
        global $tables;

        $query = <<<SQL
SELECT 
  id,
  title 
FROM 
  {$tables['template']} 
ORDER BY 
  listorder;
SQL;

        $queryResult = Sql_Query($query);
        while ($template = Sql_Fetch_Array($queryResult)) {
            if ($template['title']) {
                $templates[$template['id']] = $template;
            }
        }

        return isset($templates) ? $templates : [];
    }

    /**
     * Execute the find one.
     *
     * @param $messageId
     * @param $userId
     *
     * @return mixed
     */
    private function executeFindOne($messageId, $userId)
    {
        $sql = <<<SQL
SELECT
	ccc.subject as ccc_subject,
	ccc.message as ccc_message,
	ccc.template as ccc_template,
	ccc.communication_type as ccc_communication_type,
	ccc.embargo as ccc_embargo,
	ccc.finish_sending as ccc_finish_sending,
	ccc.status as ccc_status,
	l.category as l_category,
	l.name as l_name,
	clif.account_number as clif_account_number,
	uua1.value as uua1_value,
	uu.email as uu_email,
	um.entered as um_entered,
	uu.bouncecount as uu_bouncecount,
	um.status as um_status
FROM
	phplist_usermessage um
	INNER JOIN phplist_user_user AS uu ON uu.id = um.userid
	INNER JOIN phplist_user_user_attribute AS uua1 ON uua1.attributeid = 1 AND uua1.userid = um.userid
	INNER JOIN phplist_listmessage AS lm ON lm.messageid = um.messageid
	INNER JOIN phplist_list AS l ON l.id = lm.listid
	INNER JOIN phplist_caixa_list_investment_fund AS clif ON clif.user_id = um.userid AND clif.list_id = l.id
	INNER JOIN phplist_caixa_campaign_call_list AS cccl ON cccl.list_id = l.id AND cccl.message_id = um.messageid
	INNER JOIN phplist_caixa_campaign_call AS ccc ON ccc.id = cccl.campaign_call_id
WHERE
  um.messageid = ?
	AND um.userid = ?;
SQL;

        $connectionPDO = Connection::fromPHPList()->getPDO();
        $connectionPDOStmt = $connectionPDO->prepare($sql);
        $connectionPDOStmt->execute([
            $messageId,
            $userId,
        ]);

        $fetched = $connectionPDOStmt->fetch();
        return $fetched;
    }
}
