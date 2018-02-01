<?php
namespace phplist\Caixa\Functionality\Domain\Model;

use phplist\Caixa\Functionality\Infrastructure\DB\PageRequest;

/**
 * Interface ClientInvestmentLogRepository
 *
 * @package phplist\Caixa\Functionality\Domain\Model
 */
interface ClientInvestmentLogRepository
{

    /**
     *
     * @param
     *            $identifier
     * @param
     *            $modalityNumber
     *            
     * @return ClientInvestmentLog
     */
    public function findOne($identifier, $modalityNumber);

    /**
     *
     * @param string $filter            
     * @param string $field            
     */
    public function findByFilter($field, $filter, $order, PageRequest $pageRequest);

    /**
     *
     * @return ClientInvestmentLog[]
     */
    public function findAll(PageRequest $pageRequest);

    /**
     *
     * @return integer
     */
    public function countAll();

    /**
     *
     * @param ClientInvestmentLog $clientInvestmentLog            
     *
     * @return void
     */
    public function add(ClientInvestmentLog $clientInvestmentLog);

    /**
     *
     * @param ClientInvestmentLog $clientInvestmentLog            
     *
     * @return void
     */
    public function merge(ClientInvestmentLog $clientInvestmentLog);

    /**
     *
     * @param ClientInvestmentLog $clientInvestmentLog            
     *
     * @return void
     */
    public function remove(ClientInvestmentLog $clientInvestmentLog);
}
