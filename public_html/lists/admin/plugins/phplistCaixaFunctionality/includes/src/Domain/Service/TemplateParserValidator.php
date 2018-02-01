<?php

namespace phplist\Caixa\Functionality\Domain\Service;

use phplist\Caixa\Functionality\Domain\Model\Campaign;
use phplist\Caixa\Functionality\Domain\Model\CampaignCall;
use phplist\Caixa\Functionality\Domain\Model\CampaignCallAttributeRepository;
use phplist\Caixa\Functionality\Domain\Model\CampaignCallListRepository;
use phplist\Caixa\Functionality\Domain\Model\CampaignRepository;
use phplist\Caixa\Functionality\Domain\Model\SubscriptionList;

/**
 * Class TemplateParserValidator
 *
 * @package phplist\Caixa\Functionality\Domain\Service
 */
class TemplateParserValidator
{
    const IGNORED = [
        // phplist
        'CONTENT',
        'SUBJECT',
        'FOOTER',
        'FORWARDURL',
        'SIGNATURE',

        // caixa
        'DT_REFERENCIA',
        'NU_AGENCIA',
        'NU_OPERACAO',
        'NU_CONTA',
        'DE_EMAIL_AGENCIA',
        'NU_MODALIDADE',
        'CAIXA_CLIENTE_IDENTIFICADOR',
        'CAIXA_CLIENTE_NOME',
    ];

    /**
     * @var CampaignCall
     */
    private $campaignCall;

    /**
     * @var CampaignRepository
     */
    private $campaignRepository;

    /**
     * @var CampaignCallListRepository
     */
    private $campaignCallListRepository;

    /**
     * @var CampaignCallAttributeRepository
     */
    private $campaignCallAttributeRepository;

    /**
     * @var array
     */
    private $errors = [];

    /**
     * TemplateParserValidator constructor.
     *
     * @param CampaignCall $campaignCall
     * @param CampaignRepository $campaignRepository
     * @param CampaignCallListRepository $campaignCallListRepository
     * @param CampaignCallAttributeRepository $campaignCallAttributeRepository
     */
    public function __construct(
        CampaignCall $campaignCall,
        CampaignRepository $campaignRepository,
        CampaignCallListRepository $campaignCallListRepository,
        CampaignCallAttributeRepository $campaignCallAttributeRepository
    )
    {
        $this->campaignCall = $campaignCall;
        $this->campaignRepository = $campaignRepository;
        $this->campaignCallListRepository = $campaignCallListRepository;
        $this->campaignCallAttributeRepository = $campaignCallAttributeRepository;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    public function isValid()
    {
        $campaignCallLists = $this->campaignCallListRepository->findAllByCampaignCall($this->campaignCall);
        foreach ($campaignCallLists as $campaignCallList) {
            $campaignId = $campaignCallList->getMessageId();
            $subscriptionList = $campaignCallList->getSubscriptionList();
            $campaign = $this->campaignRepository->findOne($campaignId);

            preg_match_all('/\[[^\[\]]+\]/', $campaign->getMessage(), $contentMatches);
            preg_match_all('/\[[^\[\]]+\]/', $this->loadTemplateContent($campaign), $templateMatches);
            $allMatches = array_merge($contentMatches[0], $templateMatches[0]);

            $withNoMatches = [];
            foreach (array_unique($allMatches) as $matched) {
                if (!$this->hasValidAttributeValue($subscriptionList, trim($matched, '[]'))) {
                    $withNoMatches[] = $matched;
                }
            }

            if (sizeof($withNoMatches) >= 1) {
                $notMatched = implode(', ', $withNoMatches);
                $this->errors[] = "Os seguintes campos do fundo {$subscriptionList->getName()} estão inválidos: <em>{$notMatched}</em>";
            }
        }

        return boolval(sizeof($this->errors) === 0);
    }

    private function hasValidAttributeValue(SubscriptionList $subscriptionList, $name)
    {
        // ignored variables
        if (in_array($name, self::IGNORED)) {
            return true;
        }

        // return true when has valid values for the attribute
        $foundAttr = $this->campaignCallAttributeRepository->findOne($this->campaignCall, $subscriptionList, $name);
        if ($foundAttr && !is_null($foundAttr->getValue()) && strlen(trim($foundAttr->getValue())) > 0) {
            return true;
        }

        return false;
    }

    /**
     * @param Campaign $campaign
     *
     * @return string
     */
    private function loadTemplateContent(Campaign $campaign)
    {
        $req = Sql_Fetch_Row_Query("select template from {$GLOBALS['tables']['template']} where id = {$campaign->getTemplate()}");
        return stripslashes($req[0]);
    }
}
