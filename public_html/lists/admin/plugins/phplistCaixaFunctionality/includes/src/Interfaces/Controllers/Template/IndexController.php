<?php

namespace phplist\Caixa\Functionality\Interfaces\Controllers\Template;

use phplist\Caixa\Functionality\Application\Shared\AbstractServiceFactory;
use phplist\Caixa\Functionality\Application\TemplateParserService;
use phplist\Caixa\Functionality\Infrastructure\DB\Connection;
use phplist\Caixa\Functionality\Interfaces\Shared\AbstractController;

/**
 * Class IndexController
 *
 * @package phplist\Caixa\Functionality\Interfaces\Controllers\Template
 */
class IndexController extends AbstractController
{
    public function __invoke()
    {
        $messageId = sprintf('%d', $_GET['messageId']);
        $userId = sprintf('%d', $_GET['userId']);

        if (isset($_GET['ajaxed'])) {

            @ob_end_clean();
            @ob_start();

            // render the found template markup only
            print($this->getParsedContent($messageId, $userId));

            exit;

        } else {

            // render the found template
            echo $this->render('template/index', [
                'messageId' => $messageId,
                'userId' => $userId,
                'template' => $this->getParsedContent($messageId, $userId),
            ]);

        }
    }

    private function getParsedContent($messageId, $userId)
    {
        $foundData = $this->executeGetTemplate($messageId, $userId);
        if (isset($foundData['template']) && strlen($foundData['template']) > 0) {
            $templateContent = trim($foundData['template']);
            $templateContent = str_replace('[SUBJECT]', $foundData['subject'], $templateContent);
            $templateContent = str_replace('[CONTENT]', $foundData['message'], $templateContent);

            /** @var TemplateParserService $templateParser */
            $templateParser = AbstractServiceFactory::get(TemplateParserService::class);
            $templateContent = $templateParser->parseOutgoingHTMLMessage($messageId, $templateContent, null, [
                'id' => $userId,
            ]);
        }

        return isset($templateContent) ? $templateContent : null;
    }

    /**
     * Get the template content
     *
     * @param $messageId
     * @param $userId
     *
     * @return mixed
     */
    private function executeGetTemplate($messageId, $userId)
    {
        $sql = <<<SQL
SELECT 
	um.messageid as messageId,
	um.userid as userId,
	lm.listid as listId,
	ccc.template_content as template,
	m.subject as subject,
	m.message as message
FROM 
	phplist_usermessage um
	INNER JOIN phplist_listmessage AS lm ON lm.messageid = um.messageid
	INNER JOIN phplist_message AS m ON m.id = um.messageid
	INNER JOIN phplist_caixa_campaign_call_list AS cccl ON cccl.list_id = lm.listid AND cccl.message_id = um.messageid
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
