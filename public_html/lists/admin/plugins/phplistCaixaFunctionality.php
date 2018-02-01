<?php
defined('PHPLISTINIT') || die();

use phplist\Caixa\Functionality\Application\TemplateParserService;
use phplist\Caixa\Functionality\Application\Shared\AbstractServiceFactory;
use phplist\Caixa\Functionality\Domain\Shared\AbstractServiceFactory as AbstractServiceFactory2;
use phplist\Caixa\Functionality\Domain\MessageDataService;
use phplist\Caixa\Functionality\Domain\UserMessageService;
use phplist\Caixa\Functionality\Interfaces\Models\MessageTab;
use phplist\Caixa\Functionality\Interfaces\Shared\View;

/**
 * Class phplistCaixaFunctionality
 */
class phplistCaixaFunctionality extends phplistPlugin
{
    const DIR = __DIR__;
    const FILE = __FILE__;

    public $name = 'Caixa Functionality';

    public $description = 'Biblioteca de funcionalidades do phplistCaixa';

    public $version = '0.1.0';

    public $pageTitles = array(
        'site' => 'phpList da Caixa',
        'about' => 'Sobre o phpList da Caixa',
        'campaigncall' => 'Agendamento de Envio de Documentos',
        'analyticalreport' => 'Relatório de Envio',
        'consolidatedreport' => 'Relatório Consolidado',
        'template' => 'Modelos & Templates',
        'clientsnoemail' => 'Clientes s/ E-Mail',
    );

    public $topMenuLinks = array(
        'site' => array(
            'category' => 'system'
        ),
        'campaigncall' => array(
            'category' => 'campaigns'
        ),
        'analyticalreport' => array (
            'category' => 'statistics'
        ),
        'consolidatedreport' => array (
            'category' => 'statistics'
        ),
        'clientsnoemail' => array (
            'category' => 'statistics'
        ),
    );

    public $commandlinePluginPages = array(
        'processGetAllModalityNumbers',
        'processImportByModalityNumber'
    );

    /**
     * phplistCaixaFunctionality constructor.
     */
    function __construct()
    {
        $this->coderoot = dirname(__FILE__) . '/phplistCaixaFunctionality/';
        parent::__construct();
    }

    public function activate()
    {
        global $plugins;
        require_once $plugins['phplistCaixaFunctionality']->coderoot . '3rd-party/vendor/autoload.php';
        require_once $plugins['phplistCaixaFunctionality']->coderoot . 'includes/src/autoload.php';

        parent::activate();
    }

    function adminMenu()
    {
        return $this->pageTitles;
    }

    public function sendMessageTab($messageId = 0, $messageData = array())
    {
        if (MessageTab::isSubmitted($_POST)) {

            $messageTab = MessageTab::fromPost($_POST);

            /** @var MessageDataService $messageDataService */
            $messageDataService = AbstractServiceFactory::get(MessageDataService::class);
            $messageDataService->setAlwaysSendToNotViewed($messageId, $messageTab->alwaysSendToNotViewed);
        } else {
            $messageTab = MessageTab::fromMessage($messageData);
        }

        $view = new View('sendMessage/tab', [
            'messageTab' => $messageTab
        ]);

        return $view->render();
    }

    public function sendMessageTabTitle($messageId = 0)
    {
        return 'Caixa';
    }

    public function messageQueued($messageId)
    {
        /** @var UserMessageService $userMessageService */
        $userMessageService = AbstractServiceFactory2::get(UserMessageService::class);
        $userMessageService->maybeSendToNotViewed($messageId);
    }

    public function messageReQueued($messageId)
    {
        /** @var UserMessageService $userMessageService */
        $userMessageService = AbstractServiceFactory2::get(UserMessageService::class);
        $userMessageService->maybeSendToNotViewed($messageId);
    }

    public function displayUsers($user, $rowid, $list)
    {
        $url = PageURL2("site&pi=phplistCaixaFunctionality&usrid=" . $user['id'] . "&action=userfunds" . addCsrfGetToken());
        $list->addColumn($rowid, 'Fundo de Investimento', '<a href=' . $url . '>Detalhes</a>');
    }

    public function parseOutgoingTextMessage($messageId, $content, $destination, $userdata = null)
    {
        /** @var TemplateParserService $templateParserService */
        $templateParserService = AbstractServiceFactory::get(TemplateParserService::class);
        $content = $templateParserService->parseOutgoingTextMessage($messageId, $content, $destination, $userdata);

        return $content;
    }

    public function parseOutgoingHTMLMessage($messageId, $content, $destination, $userdata = null)
    {
        /** @var TemplateParserService $templateParserService */
        $templateParserService = AbstractServiceFactory::get(TemplateParserService::class);
        $content = $templateParserService->parseOutgoingHTMLMessage($messageId, $content, $destination, $userdata);
        return $content;
    }

    public function hookCaixaListPlaceHolders()
    {
        $string = '<p>O modelo suporta variáveis utilizadas no metadados da campanha  e as variáveis pré definidas abaixo:</p>';
        $string .= '<p><strong>[CAIXA_CLIENTE_NOME] | [CO_IDENTIFICADOR_CLIENTE] | [DE_EMAIL_CLIENTE] | [NO_CLIENTE] | [DT_REFERENCIA] | [NU_AGENCIA] | [NU_OPERACAO] | [NU_CONTA] | [DE_EMAIL_AGENCIA] | [NU_MODALIDADE]</strong></p>';

        return $string;
    }

    public function hookCaixaContentOfTheTemplateOptionalLine()
    {
        $string = '<br><p>O modelo suporta variáveis utilizadas no metadados da campanha  e as variáveis pré definidas abaixo:</p>';
        $string .= '<p><strong>[CAIXA_CLIENTE_NOME] | [CO_IDENTIFICADOR_CLIENTE] | [DE_EMAIL_CLIENTE] | [NO_CLIENTE] | [DT_REFERENCIA] | [NU_AGENCIA] | [NU_OPERACAO] | [NU_CONTA] | [DE_EMAIL_AGENCIA] | [NU_MODALIDADE]</strong></p>';

        return $string;
    }
}
