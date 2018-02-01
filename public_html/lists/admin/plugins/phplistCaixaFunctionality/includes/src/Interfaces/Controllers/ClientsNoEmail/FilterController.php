<?php
namespace phplist\Caixa\Functionality\Interfaces\Controllers\ClientsNoEmail;

use phplist\Caixa\Functionality\Interfaces\Shared\AbstractController;
use phplist\Caixa\Functionality\Infrastructure\Shared\AbstractDAOFactory;
use phplist\Caixa\Functionality\Domain\Model\ClientInvestmentLogRepository;
use phplist\Caixa\Functionality\Infrastructure\DB\PageRequest;

/**
 * Class IndexController
 *
 * @package phplist\Caixa\Functionality\Interfaces\Controllers\CampaignCall
 */
class FilterController extends AbstractController
{

    public function __invoke()
    {
        
        /** @var ClientInvestmentLogRepository $clientInvestmentLogRepository */
        $clientInvestmentLogRepository = AbstractDAOFactory::get(ClientInvestmentLogRepository::class);
        
        $filter = isset($_POST['search']) ? $_POST['search'] : null;
        $filed = isset($_POST['type']) ? $_POST['type'] : null;
        $order = isset($_POST['order']) ? $_POST['order'] : null;

        $pageRequest = new PageRequest(isset($_GET['start']) ? $_GET['start'] : 0);
        $pageRequest->setTotal($clientInvestmentLogRepository->countAll());

        $clientsNoEmail = $clientInvestmentLogRepository->findByFilter($filed, $filter, $order, $pageRequest);

        echo $this->render('reports/clientsnoemail/index', [
            'clientsNoEmail' => $clientsNoEmail,
            'pageRequest' => $pageRequest,
            'filter' => $filter,
            'filed' => $filed,
            'order' => $order,
        ]);
    }
}
