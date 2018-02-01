<?php

namespace phplist\Caixa\Functionality\Infrastructure\DB;

/**
 * Class PageRequest
 *
 * @package phplist\Caixa\Functionality\Infrastructure\DB
 */
class PageRequest
{
    /**
     * @var integer
     */
    private $start;

    /**
     * @var integer
     */
    private $numberPerPage;

    /**
     * @var integer
     */
    private $total = 0;

    /**
     * PageRequest constructor.
     *
     * @param $start
     * @param $numberPerPage
     */
    public function __construct($start = 0, $numberPerPage = 15)
    {
        $this->start = $start;
        $this->numberPerPage = $numberPerPage;
    }

    /**
     * @return int
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @return int
     */
    public function getNumberPerPage()
    {
        return $this->numberPerPage;
    }

    /**
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param int $total
     */
    public function setTotal($total)
    {
        $this->total = $total;
    }
}
