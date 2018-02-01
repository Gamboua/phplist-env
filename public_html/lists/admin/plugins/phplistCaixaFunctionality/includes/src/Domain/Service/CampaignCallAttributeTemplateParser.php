<?php

namespace phplist\Caixa\Functionality\Domain\Service;

use phplist\Caixa\Functionality\Domain\Model\CampaignCallAttributeRepository;
use phplist\Caixa\Functionality\Domain\Model\CampaignCallListRepository;

/**
 * Class CampaignCallAttributeTemplateParser
 *
 * @package phplist\Caixa\Functionality\Domain\Service
 */
class CampaignCallAttributeTemplateParser
{

    private $campaignCallListRepository;
    private $campaignCallAttributeRepository;

    public function __construct(CampaignCallListRepository $campaignCallListRepository, CampaignCallAttributeRepository $campaignCallAttributeRepository)
    {
        $this->campaignCallListRepository = $campaignCallListRepository;
        $this->campaignCallAttributeRepository = $campaignCallAttributeRepository;
    }

    public function parseOutgoingMessage($messageId, $content, $destination, $userData = null)
    {
        $campaignCallList = $this->campaignCallListRepository->findOneByMessageId($messageId);

        if ($campaignCallList) {

            $list = $campaignCallList->getSubscriptionList();
            $campaignCall = $campaignCallList->getCampaignCall();

            preg_match_all('/\[[^\[\]]+\]/', $content, $match);
            foreach ($match[0] as $m) {
                $attribute = $this->campaignCallAttributeRepository->findOne($campaignCall, $list, trim($m, '[]'));
                if ($attribute) {
                    $content = str_replace($m, $attribute->getValue(), $content);
                }
            }

        }

        return $content;
    }
}
