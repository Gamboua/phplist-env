<?php

namespace phplist\Caixa\Functionality\Domain\Model;

/**
 * Interface CampaignRepository
 *
 * @package phplist\Caixa\Functionality\Domain\Model
 */
interface CampaignRepository
{
    /**
     * @param integer $id
     *
     * @return Campaign
     */
    public function findOne($id);

    /**
     * @param CampaignCall $campaignCall
     *
     * @return integer
     */
    public function countQueuedToBeProcessed(CampaignCall $campaignCall);

    /**
     * @param Campaign $campaign
     *
     * @return void
     */
    public function add(Campaign $campaign);

    /**
     * @param Campaign $campaign
     *
     * @return void
     */
    public function merge(Campaign $campaign);

    /**
     * @param Campaign $campaign
     * @param SubscriptionList $list
     *
     * @return void
     */
    public function linkToList(Campaign $campaign, SubscriptionList $list);

    /**
     * @param CampaignCall $campaignCall
     *
     * @return void
     */
    public function removeAllByCampaignCall(CampaignCall $campaignCall);
}
