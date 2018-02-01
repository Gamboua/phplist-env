<?php

namespace phplist\Caixa\Functionality\Interfaces\Controllers\CampaignCall;

use phplist\Caixa\Functionality\Domain\Model\Campaign;
use phplist\Caixa\Functionality\Domain\Model\CampaignCall;
use phplist\Caixa\Functionality\Domain\Model\CampaignCallAttribute;
use phplist\Caixa\Functionality\Domain\Model\CampaignCallAttributeRepository;
use phplist\Caixa\Functionality\Domain\Model\CampaignCallList;
use phplist\Caixa\Functionality\Domain\Model\CampaignCallListRepository;
use phplist\Caixa\Functionality\Domain\Model\CampaignCallRepository;
use phplist\Caixa\Functionality\Domain\Model\CampaignRepository;
use phplist\Caixa\Functionality\Domain\Model\SubscriptionListRepository;
use phplist\Caixa\Functionality\Infrastructure\DB\Connection;
use phplist\Caixa\Functionality\Infrastructure\DB\Transaction;
use phplist\Caixa\Functionality\Infrastructure\Shared\AbstractDAOFactory;
use phplist\Caixa\Functionality\Interfaces\Shared\AbstractController;

/**
 * Class CreateController
 *
 * @package phplist\Caixa\Functionality\Interfaces\Controllers\CampaignCall
 */
class CreateController extends AbstractController
{
    public function __invoke()
    {
        if ('POST' == $_SERVER['REQUEST_METHOD']) {
            if (isset($_POST['campaignCallSave'])) {
                $this->doHttpPostSave();
            }
        } else {
            $this->doHttpGet();
        }
    }

    public function doHttpGet()
    {
        echo $this->render('campaign-call/create', [
            'templates' => $this->getTemplates(),
            'communicationTypes' => $this->getCommunicationTypes(),
            'availableStatus' => $this->getAvailableStatus(),
            'campaignCall' => new CampaignCall(),
        ]);
    }

    public function doHttpPostSave()
    {
        $campaignCall = new CampaignCall();
        $campaignCall->setSubject(isset($_POST['subject']) ? $_POST['subject'] : null);
        $campaignCall->setFromField(isset($_POST['fromField']) ? $_POST['fromField'] : null);
        $campaignCall->setMessage(isset($_POST['message']) ? $_POST['message'] : null);
        $campaignCall->setTemplate(isset($_POST['template']) ? $_POST['template'] : null);
        $campaignCall->setCommunicationType(isset($_POST['communicationType']) ? $_POST['communicationType'] : null);
        $campaignCall->setEmbargo(isset($_POST['embargo']) ? date('Y-m-d H:i:s', mktime(
            $_POST['embargo']['hour'], $_POST['embargo']['minute'], 0,
            $_POST['embargo']['month'], $_POST['embargo']['day'], $_POST['embargo']['year']
        )) : null);
        $campaignCall->setFinishSending(isset($_POST['finishSending']) ? date('Y-m-d H:i:s', mktime(
            $_POST['finishSending']['hour'], $_POST['finishSending']['minute'], 0,
            $_POST['finishSending']['month'], $_POST['finishSending']['day'], $_POST['finishSending']['year']
        )) : null);

        // execute the validation
        $errors = $this->validateAndGetErrors($campaignCall);

        // process errors if they exists
        if (isset($errors) && sizeof($errors) > 0) {
            echo $this->render('campaign-call/create', [
                'errors' => $errors,
                'templates' => $this->getTemplates(),
                'communicationTypes' => $this->getCommunicationTypes(),
                'availableStatus' => $this->getAvailableStatus(),
                'campaignCall' => $campaignCall,
            ]);

            return;
        }

        $transaction = new Transaction(Connection::fromPHPList());
        $transaction->execute(function () use ($campaignCall) {

            /** @var CampaignCallRepository $campaignCallRepository */
            $campaignCallRepository = AbstractDAOFactory::get(CampaignCallRepository::class);
            $campaignCallRepository->add($campaignCall);

            /** @var CampaignCallListRepository $campaignCallListRepository */
            $campaignCallListRepository = AbstractDAOFactory::get(CampaignCallListRepository::class);

            /** @var SubscriptionListRepository $subscriptionListRepository */
            $subscriptionListRepository = AbstractDAOFactory::get(SubscriptionListRepository::class);

            foreach ($_POST['targetlist'] as $targetListId => $targetList) {
                $subscriptionList = $subscriptionListRepository->findOne($targetListId);
                if ($subscriptionList) {
                    $campaignCallList = new CampaignCallList();
                    $campaignCallList->setCampaignCall($campaignCall);
                    $campaignCallList->setSubscriptionList($subscriptionList);
                    $this->setNextCampaignId($campaignCallList);

                    $campaignCallListRepository->add($campaignCallList);
                }
            }

            $this->doProcessImportCSV($campaignCall);

            // the success flash message
            $_SESSION['action_result'] = 'Campanha foi criada com sucesso';

        });

        Redirect($_GET['page'] . '&pi=' . $_GET['pi'] . addCsrfGetToken());
    }

    /**
     * @param CampaignCall $campaignCall
     *
     * @return array
     */
    private function validateAndGetErrors(CampaignCall $campaignCall)
    {
        $errors = [];

        if (is_null($campaignCall->getSubject())
            || !filter_var($campaignCall->getSubject(), FILTER_SANITIZE_STRING)
        ) {
            $errors['subject'] = 'O campo \'assunto\' deve ser do tipo texto e é obrigatório.';
        }

        if (is_null($campaignCall->getFromField())
            || !filter_var($campaignCall->getFromField(), FILTER_VALIDATE_EMAIL)
        ) {
            $errors['fromField'] = 'O campo \'de\' deve ser do tipo e-mail e é obrigatório.';
        }

        if (is_null($campaignCall->getTemplate())
            || !filter_var($campaignCall->getTemplate(), FILTER_VALIDATE_INT)
        ) {
            $errors['template'] = 'O campo \'modelo\' é obrigatório.';
        }

        if (is_null($campaignCall->getCommunicationType())
            || !filter_var($campaignCall->getCommunicationType(), FILTER_SANITIZE_STRING)
        ) {
            $errors['communicationType'] = 'O campo \'tipo de comunicado\' é obrigatório.';
        }

        return $errors;
    }

    /**
     * @param CampaignCall $campaignCall
     */
    private function doProcessImportCSV(CampaignCall $campaignCall)
    {
        if (isset($_FILES['importCSV']) && UPLOAD_ERR_NO_FILE == $_FILES['importCSV']['error']) {
            // no file to be imported
            return;
        }

        /** @var SubscriptionListRepository $subscriptionListRepository */
        $subscriptionListRepository = AbstractDAOFactory::get(SubscriptionListRepository::class);

        /** @var CampaignCallAttributeRepository $campaignCallAttributeRepository */
        $campaignCallAttributeRepository = AbstractDAOFactory::get(CampaignCallAttributeRepository::class);
        $campaignCallAttributeRepository->removeAllByCampaignCall($campaignCall);

        if (isset($_FILES['importCSV']) && isset($_FILES['importCSV']['tmp_name'])) {
            $importCSV = new \SplFileObject($_FILES['importCSV']['tmp_name']);
            $importCSV->setFlags(\SplFileObject::READ_CSV);

            $fieldNames = [];
            if ($importCSV->valid()) {
                $fieldNames = $importCSV->fgets();
                $fieldNames = explode(';', $fieldNames);
            }

            while ($importCSV->valid()) {
                $line = $importCSV->fgets();
                $line = explode(';', $line);

                if (sizeof($line) <= 1) {
                    // ignore blank lines
                    continue;
                }

                $subscriptionList = $subscriptionListRepository->findOneByName($line[0]);
                foreach ($fieldNames as $key => $fieldName) {
                    if (0 == $key) {
                        // ignore list column
                        continue;
                    };

                    $campaignCallAttribute = new CampaignCallAttribute();
                    $campaignCallAttribute->setCampaignCall($campaignCall);
                    $campaignCallAttribute->setSubscriptionList($subscriptionList);
                    $campaignCallAttribute->setName(trim($fieldName));
                    $campaignCallAttribute->setValue(trim($line[$key]));

                    $campaignCallAttributeRepository->add($campaignCallAttribute);
                }
            }
        }
    }

    /**
     * @param CampaignCallList $campaignCallList
     */
    private function setNextCampaignId(CampaignCallList $campaignCallList)
    {
        $campaignCall = $campaignCallList->getCampaignCall();
        $subscriptionList = $campaignCallList->getSubscriptionList();

        $campaign = new Campaign();
        $campaign->setSubject($campaignCall->getSubject());
        $campaign->setFromField($campaignCall->getFromField());
        $campaign->setMessage($campaignCall->getMessage());
        $campaign->setStatus(Campaign::STATUS_DRAFT);
        $campaign->setTemplate($campaignCall->getTemplate());
        $campaign->setEmbargo($campaignCall->getEmbargo());
        $campaign->setFinishSending($campaignCall->getFinishSending());

        /** @var CampaignRepository $campaignRepository */
        $campaignRepository = AbstractDAOFactory::get(CampaignRepository::class);
        $campaignRepository->add($campaign);

        // make relation between campaign and list
        $campaignRepository->linkToList($campaign, $subscriptionList);

        // set next campaign id
        $campaignCallList->setMessageId($campaign->getId());
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

    /**
     * @return array
     */
    private function getTemplates()
    {
        global $tables;

        $query = <<<SQL
SELECT 
  id,
  title 
FROM 
  {$tables['template']} 
ORDER BY 
  listorder;
SQL;

        $queryResult = Sql_Query($query);
        while ($template = Sql_Fetch_Array($queryResult)) {
            if ($template['title']) {
                $templates[] = $template;
            }
        }

        return isset($templates) ? $templates : [];
    }
}
