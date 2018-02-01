<?php

namespace phplist\Caixa\Functionality\Domain\Model;
use phplist\Caixa\Functionality\Domain\Shared\ConsolidatedReportQueryObject;


/**
 * Interface ConsolidateReportRepository
 *
 * @package phplist\Caixa\Functionality\Domain\Model
 */
interface ConsolidateReportRepository
{
    /**
     * @param ConsolidatedReportQueryObject $queryObject
     *
     * @return array
     */
    public function executeQueryObject(ConsolidatedReportQueryObject $queryObject);

}
