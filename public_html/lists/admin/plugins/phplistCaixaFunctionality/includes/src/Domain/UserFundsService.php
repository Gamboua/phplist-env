<?php

namespace phplist\Caixa\Functionality\Domain;

use phplist\Caixa\Functionality\Infrastructure\DB\DAO\PHPListDAO;
use phplist\Caixa\Functionality\Infrastructure\Shared\PHPList;

/**
 * Class UserFundsService
 *
 * @package phplist\Caixa\Functionality\Domain
 */
class UserFundsService
{
    private $phpList;
    private $phpListDAO;

    public function __construct(PHPList $phpList, PHPListDAO $phpListDAO)
    {
        $this->phpList = $phpList;
        $this->phpListDAO = $phpListDAO;
    }

    public function findUserFunds($userid)
    {
        $this->phpListDAO->findUserFunds($userid);
    }


}
