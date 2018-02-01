<?php

namespace phplist\Caixa\Functionality\Domain\Model;
use phplist\Caixa\Functionality\Infrastructure\DB\PageRequest;
use phplist\Caixa\Functionality\Interfaces\Models\AnalyticalReportModel;


/**
 * Interface AnalyticalReportRepository
 *
 * @package phplist\Caixa\Functionality\Domain\Model
 */
interface AnalyticalReportRepository
{
    /**
     * @return AnalyticalReport[]
     */
    public function findAll(AnalyticalReportModel $model, $exportData);

    /**
     * @return int
     */
    public function countAll(AnalyticalReportModel $model);


    /**
     * @param AnalyticalReport $analyticalReport
     *
     * @return AnalyticalReport[]
     */
    public function findAllSorted(AnalyticalReport $analyticalReport);


}
