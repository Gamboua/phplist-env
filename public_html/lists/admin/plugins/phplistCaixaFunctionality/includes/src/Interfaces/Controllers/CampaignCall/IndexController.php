<?php

namespace phplist\Caixa\Functionality\Interfaces\Controllers\CampaignCall;

use phplist\Caixa\Functionality\Domain\Model\CampaignCall;
use phplist\Caixa\Functionality\Domain\Model\CampaignCallRepository;
use phplist\Caixa\Functionality\Infrastructure\DB\PageRequest;
use phplist\Caixa\Functionality\Infrastructure\Shared\AbstractDAOFactory;
use phplist\Caixa\Functionality\Interfaces\Shared\AbstractController;

/**
 * Class IndexController
 *
 * @package phplist\Caixa\Functionality\Interfaces\Controllers\CampaignCall
 */
class IndexController extends AbstractController
{
    public function __invoke()
    {
        /** @var CampaignCallRepository $campaignCallRepository */
        $campaignCallRepository = AbstractDAOFactory::get(CampaignCallRepository::class);

        $pageRequest = new PageRequest(isset($_GET['start']) ? $_GET['start'] : 0);
        $pageRequest->setTotal($campaignCallRepository->countAll());
        $campaignCalls = $campaignCallRepository->findAll($pageRequest);

        echo $this->render('campaign-call/index', [
            'communicationTypes' => $this->getCommunicationTypes(),
            'availableStatus' => $this->getAvailableStatus(),
            'pageRequest' => $pageRequest,
            'campaignCalls' => $campaignCalls,
        ]);
    }

    /**
     * @return array
     */
    private function getCommunicationTypes()
    {
        return [
            CampaignCall::TYPE_COMMUNICATION => 'Comunicado',
            CampaignCall::TYPE_CONVOCATION => 'Convocação',
            CampaignCall::TYPE_RELEVANT_FACT => 'Fato Relevante',
            CampaignCall::TYPE_SUMMARY => 'Resumo',
        ];
    }

    /**
     * @return array
     */
    private function getAvailableStatus()
    {
        return [
            CampaignCall::STATUS_DRAFT => 'Pendente',
            CampaignCall::STATUS_SUBMITTED => 'Finalizado',
        ];
    }
}
