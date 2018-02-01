<?php
namespace phplist\Caixa\Functionality\Interfaces\Controllers\ClientsNoEmail;

use phplist\Caixa\Functionality\Domain\Model\ClientInvestmentLog;
use phplist\Caixa\Functionality\Interfaces\Shared\AbstractController;
use phplist\Caixa\Functionality\Infrastructure\Shared\AbstractDAOFactory;
use phplist\Caixa\Functionality\Domain\Model\ClientInvestmentLogRepository;
use phplist\Caixa\Functionality\Infrastructure\DB\PageRequest;

/**
 * Class IndexController
 *
 * @package phplist\Caixa\Functionality\Interfaces\Controllers\ClientsNoEmail
 */
class ExportController extends AbstractController
{

    public function __invoke()
    {
        
        ob_end_clean();
        ob_start();

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=relatorio.csv');


        /** @var ClientInvestmentLogRepository $clientInvestmentLogRepository */
        $clientInvestmentLogRepository = AbstractDAOFactory::get(ClientInvestmentLogRepository::class);

        $filter = isset($_GET['filter']) ? $_GET['filter'] : null;
        $filed = isset($_GET['filed']) ? $_GET['filed'] : null;
        $order = isset($_GET['order']) ? $_GET['order'] : null;

        $clientsNoEmail = $clientInvestmentLogRepository->findByFilter($filed, $filter, $order);

        $cabecalho = [
            'IDENTIFICADOR',
            'NOME',
            'NÚMERO DO FUNDO',
            'NÚMERO AGÊNCIA',
            'NÚMERO OPERAÇÃO',
            'NÚMERO DA CONTA',
            'EMAIL AGÊNCIA',
        ];

        $filename = "lista_nao_vistos.csv";

        $fp = fopen('php://output', 'w');

        fputcsv($fp, $cabecalho, ';');

        foreach ($clientsNoEmail as $clientNoEmail) {

            fputcsv($fp, [
                $clientNoEmail->getIdentifier(),
                $clientNoEmail->getName(),
                $clientNoEmail->getModalityNumber(),
                $clientNoEmail->getAgencyNumber(),
                $clientNoEmail->getOperationNumber(),
                $clientNoEmail->getAccountNumber(),
                $clientNoEmail->getAgencyEmail(),
            ], ';');

        }

        exit();

    }

}
