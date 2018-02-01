<?php

namespace phplist\Caixa\Functionality\Interfaces\Controllers\ConsolidatedReport;

use phplist\Caixa\Functionality\Domain\Model\CampaignCall;
use phplist\Caixa\Functionality\Domain\Model\ConsolidateReportRepository;
use phplist\Caixa\Functionality\Domain\Model\SubscriptionListRepository;
use phplist\Caixa\Functionality\Domain\Shared\ConsolidatedReportQueryObject;
use phplist\Caixa\Functionality\Infrastructure\Shared\AbstractDAOFactory;
use phplist\Caixa\Functionality\Interfaces\Shared\AbstractController;

/**
 * Class IndexController
 *
 * @package phplist\Caixa\Functionality\Interfaces\Controllers\ConsolidatedReport
 */
class IndexController extends AbstractController
{
    public function __invoke()
    {
        if ('POST' == $_SERVER['REQUEST_METHOD']) {
            $this->doHttpPost();
        } else {
            $this->doHttpGet();
        }
    }

    private function doHttpGet()
    {
        /** @var SubscriptionListRepository $subscriptionListRepository */
        $subscriptionListRepository = AbstractDAOFactory::get(SubscriptionListRepository::class);

        echo $this->render('reports/consolidated/index', [
            'betterStatusNames' => ConsolidatedReportQueryObject::betterStatusNames(),
            'communicationTypes' => $this->getCommunicationTypes(),
            'subscriptionLists' => $subscriptionListRepository->findAll(),
            'queryObject' => new ConsolidatedReportQueryObject(),
            'queryResult' => null,
        ]);
    }

    private function doHttpPost()
    {
        /** @var SubscriptionListRepository $subscriptionListRepository */
        $subscriptionListRepository = AbstractDAOFactory::get(SubscriptionListRepository::class);

        /** @var ConsolidateReportRepository $consolidateReportRepository */
        $consolidateReportRepository = AbstractDAOFactory::get(ConsolidateReportRepository::class);

        $queryObject = new ConsolidatedReportQueryObject();
        $queryObject->setFundName(isset($_POST['fundName']) ? $_POST['fundName'] : null);
        $queryObject->setFundGroup(isset($_POST['fundGroup']) ? $_POST['fundGroup'] : null);
        $queryObject->setClientIdentifier(isset($_POST['clientIdentifier']) ? $_POST['clientIdentifier'] : null);
        $queryObject->setCommunicationType(isset($_POST['communicationType']) ? $_POST['communicationType'] : null);
        $queryObject->setMessageDateStarted(isset($_POST['messageDateStarted']) ? date('Y-m-d H:i:s', mktime(
            $_POST['messageDateStarted']['hour'], $_POST['messageDateStarted']['minute'], 0,
            $_POST['messageDateStarted']['month'], $_POST['messageDateStarted']['day'], $_POST['messageDateStarted']['year']
        )) : null);
        $queryObject->setMessageDateFinished(isset($_POST['messageDateFinished']) ? date('Y-m-d H:i:s', mktime(
            $_POST['messageDateFinished']['hour'], $_POST['messageDateFinished']['minute'], 0,
            $_POST['messageDateFinished']['month'], $_POST['messageDateFinished']['day'], $_POST['messageDateFinished']['year']
        )) : null);

        $queryResult = $consolidateReportRepository->executeQueryObject($queryObject);

        echo $this->render('reports/consolidated/index', [
            'betterStatusNames' => ConsolidatedReportQueryObject::betterStatusNames(),
            'communicationTypes' => $this->getCommunicationTypes(),
            'subscriptionLists' => $subscriptionListRepository->findAll(),
            'queryObject' => $queryObject,
            'queryResult' => $queryResult,
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

}
