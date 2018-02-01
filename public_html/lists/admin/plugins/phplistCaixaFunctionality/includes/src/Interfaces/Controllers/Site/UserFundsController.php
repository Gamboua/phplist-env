<?php

namespace phplist\Caixa\Functionality\Interfaces\Controllers\Site;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use phplist\Caixa\Functionality\Domain\Shared\AbstractServiceFactory;
use phplist\Caixa\Functionality\Domain\UserImportService;
use phplist\Caixa\Functionality\Infrastructure\DB\Connection;
use phplist\Caixa\Functionality\Infrastructure\DB\DAO\CaixaDAO;
use phplist\Caixa\Functionality\Infrastructure\DB\DAO\PHPListDAO;
use phplist\Caixa\Functionality\Infrastructure\Shared\AbstractDAOFactory;
use phplist\Caixa\Functionality\Interfaces\Shared\AbstractController;


/**
 * Class UserFundsController
 *
 * @package phplist\Caixa\Functionality\Interfaces\Controllers\Site
 */
class UserFundsController extends AbstractController
{

    public function __invoke()
    {
        $this->doGetRequest();
        $this->doPutRequest();
    }

    public function doGetRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            /** @var PHPListDAO $phpListDAO */
            $phpListDAO = AbstractDAOFactory::get(PHPListDAO::class);
            $investmentFunds = $phpListDAO->findUserFunds($_GET['usrid']);

            echo $this->render('userfunds/index', [
                'userfunds' => $investmentFunds
            ]);
        }
    }

    public function doPutRequest()
    {
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
        $isPost = $_SERVER['REQUEST_METHOD'] === 'POST';

        if ($isAjax && $isPost) {
            ob_end_clean();

            /** @var PHPListDAO $phpListDAO */
            $phpListDAO = AbstractDAOFactory::get(PHPListDAO::class);
            $phpListDAO->updateListInvestmentFundByView($_POST['userId'], $_POST['listId'], $_POST['name'], $_POST['value']);

//            print(json_encode(array(
//                'listId' => $_POST['listId'],
//                'userId' => $_POST['userId'],
//                'name' => $_POST['name'],
//                'value' => $_POST['value']
//            )));


            die();
        }

    }


}
