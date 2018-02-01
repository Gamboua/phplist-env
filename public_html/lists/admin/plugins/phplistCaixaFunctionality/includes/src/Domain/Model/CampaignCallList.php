<?php

namespace phplist\Caixa\Functionality\Domain\Model;

/**
 * Class CampaignCallList
 *
 * @package phplist\Caixa\Functionality\Domain\Model
 */
class CampaignCallList
{
    /**
     * @var CampaignCall
     */
    private $campaignCall;

    /**
     * @var SubscriptionList
     */
    private $subscriptionList;

    /**
     * @var integer
     */
    private $messageId;

    /**
     * @return CampaignCall
     */
    public function getCampaignCall()
    {
        return $this->campaignCall;
    }

    /**
     * @param CampaignCall $campaignCall
     */
    public function setCampaignCall($campaignCall)
    {
        $this->campaignCall = $campaignCall;
    }

    /**
     * @return SubscriptionList
     */
    public function getSubscriptionList()
    {
        return $this->subscriptionList;
    }

    /**
     * @param SubscriptionList $subscriptionList
     */
    public function setSubscriptionList($subscriptionList)
    {
        $this->subscriptionList = $subscriptionList;
    }

    /**
     * @return int
     */
    public function getMessageId()
    {
        return $this->messageId;
    }

    /**
     * @param int $messageId
     */
    public function setMessageId($messageId)
    {
        $this->messageId = $messageId;
    }

    /**
     * @param array $properties
     *
     * @return CampaignCallList
     */
    public static function fromArray(array $properties)
    {
        $campaignCallList = new CampaignCallList();
        $campaignCallList->campaignCall = $properties['campaignCall'];
        $campaignCallList->subscriptionList = $properties['subscriptionList'];
        $campaignCallList->messageId = $properties['messageId'];

        return $campaignCallList;
    }

}
