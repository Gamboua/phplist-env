<?php
namespace phplist\Caixa\Functionality\Application;
use phplist\Caixa\Functionality\Domain\Service\CampaignCallAttributeTemplateParser;
use phplist\Caixa\Functionality\Domain\Service\InvestmentFundSubscriptionTemplateParser;

/**
 * Class ImportService
 *
 * @package phplist\Caixa\Functionality\Application
 */
class TemplateParserService
{
    /**
     * @var InvestmentFundSubscriptionTemplateParser
     */
    private $investmentFundParser;

    /**
     * @var CampaignCallAttributeTemplateParser
     */
    private $campaignCallAttributeParser;

    /**
     * TemplateParserService constructor.
     *
     * @param InvestmentFundSubscriptionTemplateParser $investmentFundParser
     * @param CampaignCallAttributeTemplateParser $campaignCallAttributeParser
     */
    public function __construct(
        InvestmentFundSubscriptionTemplateParser $investmentFundParser,
        CampaignCallAttributeTemplateParser $campaignCallAttributeParser
    )
    {
        $this->investmentFundParser = $investmentFundParser;
        $this->campaignCallAttributeParser = $campaignCallAttributeParser;
    }

    public function parseOutgoingTextMessage($messageId, $content, $destination, $userdata = null) {
        $content = $this->investmentFundParser->parseOutgoingMessage($messageId, $content, $destination, $userdata);
        return $this->campaignCallAttributeParser->parseOutgoingMessage($messageId, $content, $destination, $userdata);
    }

    public function parseOutgoingHTMLMessage($messageId, $content, $destination, $userdata = null) {
        $content = $this->investmentFundParser->parseOutgoingMessage($messageId, $content, $destination, $userdata);
        return $this->campaignCallAttributeParser->parseOutgoingMessage($messageId, $content, $destination, $userdata);
    }
}
