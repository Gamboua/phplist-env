<?php

namespace phplist\Caixa\Functionality\Domain\Model;

/**
 * Interface CampaignCallAttributeRepository
 *
 * @package phplist\Caixa\Functionality\Domain\Model
 */
interface CampaignCallAttributeRepository
{
    /**
     * @param CampaignCall $campaignCall
     * @param SubscriptionList $list
     * @param string $name
     * @return CampaignCallAttribute
     */
    public function findOne(CampaignCall $campaignCall, SubscriptionList $list, $name);

    /**
     * @param CampaignCallAttribute $campaignCallAttribute
     *
     * @return void
     */
    public function add(CampaignCallAttribute $campaignCallAttribute);

    /**
     * @param CampaignCall $campaignCall
     *
     * @return CampaignCallAttribute[]
     */
    public function findAllByCampaignCall(CampaignCall $campaignCall);

    /**
     * @param CampaignCallList $campaignCallList
     *
     * @return CampaignCallAttribute[]
     */
    public function findAllByCampaignCallList(CampaignCallList $campaignCallList);

    /**
     * @param CampaignCall $campaignCall
     *
     * @return void
     */
    public function removeAllByCampaignCall(CampaignCall $campaignCall);

}
