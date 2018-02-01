<?php
/**
 * Created by PhpStorm.
 * User: gustavo
 * Date: 18/01/18
 * Time: 12:03
 */

namespace phplist\Caixa\Functionality\Interfaces\Controllers\AnalyticalReport;


use phplist\Caixa\Functionality\Domain\Model\AnalyticalReport;
use phplist\Caixa\Functionality\Domain\Shared\ConsolidatedReportQueryObject;

class AnalyticalReportExport
{

    public function export($analyticalReport)
    {

        ob_end_clean();
        ob_start();

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=relatorioanalitico.csv');

        $betterStatusNames = ConsolidatedReportQueryObject::betterStatusNames();

        $cabecalho = [
            'CPF/CNPJ do Cotista',
            'Grupo',
            'Fundo',
            'Conta',
            'E-mail do Cotista',
            'Data Envio',
            'Nº de Tentativas',
            'Situação'
        ];

        $fp = fopen('php://output', 'w');

        fputcsv($fp, $cabecalho, ';');

        /** @var AnalyticalReport $report */

        foreach ($analyticalReport as $report) {

            fputcsv($fp, [
                $report->getRegistration(),
                $report->getGroup(),
                $report->getFund(),
                $report->getAccount(),
                $report->getEmail(),
                (new \DateTime($report->getDtSent()))->format('d/m/Y - H:m'),
                $report->getAttempts(),
                isset($betterStatusNames[$report->getStatus()]) ? $betterStatusNames[$report->getStatus()] : $report->getStatus(),
            ], ';');
        }

        exit();
    }

}