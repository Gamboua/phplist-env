<?php

namespace phplist\Caixa\Functionality\Domain\Model\Caixa;

/**
 * Interface ClientInvestmentRepository
 *
 * @package phplist\Caixa\Functionality\Domain\Model\Caixa
 */
interface ClientInvestmentRepository
{
    /**
     * @param $modalityNumber
     *
     * @return ClientInvestment[]
     */
    public function findAllByModalityNumber($modalityNumber);

    /**
     * @param $modalityNumber
     * @param callable $callback
     * @return void
     */
    public function fromWithinFindAllByModalityNumber($modalityNumber, callable $callback);

    /**
     * @return integer[]
     */
    public function collectModalityNumbers();

}