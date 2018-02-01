<?php

namespace phplist\Caixa\Functionality\Domain\Service;

use phplist\Caixa\Functionality\Domain\Model\InvestmentFundSubscription;
use phplist\Caixa\Functionality\Domain\Model\CampaignCallList;
use phplist\Caixa\Functionality\Domain\Model\User;
use phplist\Caixa\Functionality\Domain\Model\CampaignCallListRepository;
use phplist\Caixa\Functionality\Domain\Model\InvestmentFundSubscriptionRepository;
use phplist\Caixa\Functionality\Domain\Model\UserRepository;

/**
 * Class InvestmentFundSubscriptionTemplateParser
 *
 * @package phplist\Caixa\Functionality\Domain\Service
 */
class InvestmentFundSubscriptionTemplateParser
{

    /**
     * co_identificador_cliente
     * de_email_cliente
     * no_cliente
     * dt_referencia
     * nu_agencia
     * nu_operacao
     * nu_conta
     * de_email_agencia
     * nu_modalidade
     * 
     * @var array
     */
    private $holders = [
        'DT_REFERENCIA' => 'referenceDate',
        'NU_AGENCIA' => 'agencyNumber',
        'NU_OPERACAO' => 'operationNumber',
        'NU_CONTA' => 'accountNumber',
        'DE_EMAIL_AGENCIA' => 'agencyEmail',
        'NU_MODALIDADE' => 'modalityNumber'
    ];

    /**
     *
     * @var CampaignCallListRepository
     */
    private $campaignCallListRepository;

    /**
     *
     * @var InvestmentFundSubscriptionRepository
     */
    private $investmentFundSubscriptionRepository;

    /**
     *
     * @var UserRepository
     */
    private $userRepository;

    /**
     *
     * @var CampaignCallList
     */
    private $campaignCallList;

    /**
     *
     * @var InvestmentFundSubscription
     */
    private $investmentFundSubscription;

    /**
     *
     * @var User
     */
    private $user;

    public function __construct(CampaignCallListRepository $campaignCallListRepository, InvestmentFundSubscriptionRepository $investmentFundSubscriptionRepository, UserRepository $userRepository)
    {
        $this->campaignCallListRepository = $campaignCallListRepository;
        $this->investmentFundSubscriptionRepository = $investmentFundSubscriptionRepository;
        $this->userRepository = $userRepository;
    }

    public function parseOutgoingMessage($messageId, $content, $destination, $userData = null)
    {
        $this->user = $this->userRepository->findOne($userData['id']);
        
        $this->campaignCallList = $this->campaignCallListRepository->findOneByMessageId($messageId);
        
        $subscriptionList = $this->campaignCallList->getSubscriptionList();
        
        $this->investmentFundSubscription = $this->investmentFundSubscriptionRepository->findOne($this->user, $subscriptionList);
        
        $content = $this->doParse($content);
        
        return $content;
    }

    private function parseAll($placeholder, $content)
    {
        preg_match_all("/\[$placeholder:?(.*?)\]/i", $content, $matches);
        
        $string = implode(',', $matches[0]);
        
        file_put_contents('/tmp/holders', $string, FILE_APPEND);
        
        foreach ($matches[0] as $holder) {
            $content = str_replace($holder, $this->doDataInvestmentFundReplacement($placeholder), $content);
        }
        
        return $content;
    }

    private function doParse($content)
    {
        foreach (array_keys($this->holders) as $holder) {
            $content = $this->parseAll($holder, $content);
        }
        
        return $content;
    }

    private function doDataInvestmentFundReplacement($placeholder)
    {
        $method = 'get' . ucfirst($this->holders[$placeholder]);
        
        if(method_exists($this->investmentFundSubscription, $method)){
            return $this->investmentFundSubscription->$method();
        }
    }
}
