<?php

namespace phplist\Caixa\Functionality\Domain\Model;

/**
 * Class CampaignCallAttribute
 *
 * @package phplist\Caixa\Functionality\Domain\Model
 */
class CampaignCallAttribute
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
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $value;

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
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @param array $properties
     *
     * @return CampaignCallAttribute
     */
    public static function fromArray(array $properties)
    {
        $campaignCallAttribute = new CampaignCallAttribute();
        $campaignCallAttribute->campaignCall = $properties['campaignCall'];
        $campaignCallAttribute->subscriptionList = $properties['subscriptionList'];
        $campaignCallAttribute->name = $properties['name'];
        $campaignCallAttribute->value = $properties['value'];

        return $campaignCallAttribute;
    }

}
