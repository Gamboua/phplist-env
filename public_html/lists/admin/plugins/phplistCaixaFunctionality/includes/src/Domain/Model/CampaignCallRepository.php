<?php

namespace phplist\Caixa\Functionality\Domain\Model;

use phplist\Caixa\Functionality\Infrastructure\DB\PageRequest;

/**
 * Interface CampaignCallRepository
 *
 * @package phplist\Caixa\Functionality\Domain\Model
 */
interface CampaignCallRepository
{
    /**
     * @param integer $id
     *
     * @return CampaignCall mixed
     */
    public function findOne($id);

    /**
     * @param PageRequest $pageRequest
     *
     * @return CampaignCall[]
     */
    public function findAll(PageRequest $pageRequest);

    /**
     * @return integer
     */
    public function countAll();

    /**
     * @param CampaignCall $campaignCall
     *
     * @return void
     */
    public function add(CampaignCall $campaignCall);

    /**
     * @param CampaignCall $campaignCall
     *
     * @return void
     */
    public function merge(CampaignCall $campaignCall);

    public function getUserLogged($userId);

    public function getTemplateContent($templateId);
}
