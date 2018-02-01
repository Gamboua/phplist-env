<?php

namespace phplist\Caixa\Functionality\Domain\Model;

/**
 * Interface CampaignCallListRepository
 *
 * @package phplist\Caixa\Functionality\Domain\Model
 */
interface CampaignCallListRepository
{
    /**
     * @param CampaignCall $campaignCall
     * @param SubscriptionList $list
     *
     * @return CampaignCallList
     */
    public function findOne(CampaignCall $campaignCall, SubscriptionList $list);

    /**
     * @param integer $messageId
     *
     * @return CampaignCallList
     */
    public function findOneByMessageId($messageId);

    /**
     * @param CampaignCall $campaignCall
     *
     * @return CampaignCallList[]
     */
    public function findAllByCampaignCall(CampaignCall $campaignCall);

    /**
     * @param CampaignCallList $campaignCallList
     *
     * @return void
     */
    public function add(CampaignCallList $campaignCallList);

    /**
     * @param CampaignCall $campaignCall
     *
     * @return void
     */
    public function removeAllByCampaignCall(CampaignCall $campaignCall);
}
