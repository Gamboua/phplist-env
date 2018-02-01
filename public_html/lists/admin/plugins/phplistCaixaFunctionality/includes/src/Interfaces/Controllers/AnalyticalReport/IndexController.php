<?php

namespace phplist\Caixa\Functionality\Interfaces\Controllers\AnalyticalReport;

use phplist\Caixa\Functionality\Domain\Model\AnalyticalReportRepository;
use phplist\Caixa\Functionality\Domain\Model\SubscriptionListRepository;
use phplist\Caixa\Functionality\Domain\Shared\ConsolidatedReportQueryObject;
use phplist\Caixa\Functionality\Infrastructure\DB\PageRequest;
use phplist\Caixa\Functionality\Infrastructure\Shared\AbstractDAOFactory;
use phplist\Caixa\Functionality\Interfaces\Shared\AbstractController;
use phplist\Caixa\Functionality\Interfaces\Models\AnalyticalReportModel;

/**
 * Class IndexController
 *
 * @package phplist\Caixa\Functionality\Interfaces\Controllers\AnalyticalReport
 */
class IndexController extends AbstractController
{
    const PAGE_SIZE = 20;

    public function __invoke()
    {
        /** @var SubscriptionListRepository $subscriptionListRepository */
        $subscriptionListRepository = AbstractDAOFactory::get(SubscriptionListRepository::class);

        /** @var AnalyticalReportExport $exportcontroller */
        $exportcontroller = new AnalyticalReportExport();

        /** @var AnalyticalReportRepository $repository */
        $repository = AbstractDAOFactory::get(AnalyticalReportRepository::class);
        $pageRequest = new PageRequest(isset($_GET['start']) ? $_GET['start'] : 0, IndexController::PAGE_SIZE);
        $orderBy = isset($_POST['orderBy']) ? $_POST['orderBy'] : null;
        $sort = isset($_POST['sort']) ? $_POST['sort'] : null;

        /**
         * FILTER
         */
        $category = isset($_POST['category']) ? $_POST['category'] : null;
        $fund = isset($_POST['fund']) ? $_POST['fund'] : null;
        $account = isset($_POST['account']) ? $_POST['account'] : null;
        $registration = isset($_POST['registration']) ? $_POST['registration'] : null;
        $email = isset($_POST['email']) ? $_POST['email'] : null;
        $attempts = isset($_POST['attempts']) ? $_POST['attempts'] : null;
        $status = isset($_POST['status']) ? $_POST['status'] : null;
        $messageDateStarted = isset($_POST['messageDateStarted']) ? $_POST['messageDateStarted'] : null;
        $messageDateFinished = isset($_POST['messageDateFinished']) ? $_POST['messageDateFinished'] : null;
        $exportData = isset($_GET['exportData']) ? $_GET['exportData'] : null;

        /** @var AnalyticalReportModel $model */
        $model = new AnalyticalReportModel(
            $pageRequest, $orderBy, $sort, $category, $fund,
            $account, $registration, $email, $attempts, $status);

        $model->setCategory($category);

        if($messageDateStarted && $messageDateFinished) {
            $model->setMessageDateStarted(date('Y-m-d H:i:s', mktime(
                $messageDateStarted['hour'], $messageDateStarted['minute'], 0,
                $messageDateStarted['month'], $messageDateStarted['day'], $messageDateStarted['year']
            )));
            $model->setMessageDateFinished(date('Y-m-d H:i:s', mktime(
                $messageDateFinished['hour'], $messageDateFinished['minute'], 0,
                $messageDateFinished['month'], $messageDateFinished['day'], $messageDateFinished['year']
            )));
        }


        $analyticalReport = $repository->findAll($model, $exportData);
        $pageRequest->setTotal($repository->countAll($model));

        if($exportData) {
            $exportcontroller->export($analyticalReport);

        } else {
            echo $this->render('reports/analyticalreport/index', [
                'betterStatusNames' => ConsolidatedReportQueryObject::betterStatusNames(),
                'subscriptionLists' => $subscriptionListRepository->findAll(),
                'pageRequest' => $pageRequest,
                'reports' => $analyticalReport,
                'model' => $model,
                'orderBy' => $orderBy,
                'sort' => $sort
            ]);
        }

    }
}
