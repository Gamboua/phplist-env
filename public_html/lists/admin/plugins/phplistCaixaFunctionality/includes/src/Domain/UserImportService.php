<?php
/**
 * Created by PhpStorm.
 * User: gustavo
 * Date: 06/11/17
 * Time: 14:42
 */

namespace phplist\Caixa\Functionality\Domain;


use phplist\Caixa\Functionality\Infrastructure\DB\DAO\CaixaDAO;
use phplist\Caixa\Functionality\Infrastructure\DB\DAO\PHPListDAO;

class UserImportService
{

    private $phpListDAO;
    private $caixaDAO;

    /**
     * UserImportService constructor.
     *
     * @param CaixaDAO $caixaDAO
     * @param PHPListDAO $phpListDAO
     */
    public function __construct(CaixaDAO $caixaDAO, PHPListDAO $phpListDAO)
    {
        $this->caixaDAO = $caixaDAO;
        $this->phpListDAO = $phpListDAO;
    }

    private function findOrCreateListByName($name)
    {
        $list = $this->getListByName($name);

        if (!$list) {
            $listId = $this->phpListDAO->insertList($name);
            $list = $this->getListByName($name);
        }

        return $list;
    }

    private function getListByName($name)
    {
        return $this->phpListDAO->findListByName($name);
    }

    public function executeUserImportByFund($fundNumber)
    {
        $this->caixaDAO->refreshListaEmailView();
        $listEmails = $this->caixaDAO->findListEmailByNuModalidade($fundNumber);

        foreach ($listEmails as $listEmail) {

            $cliente = $this->phpListDAO->findUserByIdentficadorCliente($listEmail['co_identificador_cliente']);
            $list = $this->findOrCreateListByName($listEmail['nu_modalidade']);

            if (!$cliente) {
                //CUSTOMER WITH EMAIL
                if ($listEmail['de_email_cliente']) {

                    //VERIFICA NA TABELA DE CLIENTES SEM EMAIL SE O USUÁRIO JÁ ESTÁ CADASTRADO
                    //SE JÁ TIVER CADASTRADO REMOVE
                    $customerWithoutEmail = $this->phpListDAO->verifyCustomerWithoutEmail($listEmail["co_identificador_cliente"]);
                    if($customerWithoutEmail) {
                        $this->phpListDAO->removeCustomerWithoutEmail($customerWithoutEmail);
                    }

                    $userId = $this->phpListDAO->insertUser($listEmail['de_email_cliente']);
                    $this->phpListDAO->insertUserAttributeValue($userId, PHPListDAO::USER_ATTR_CLIENTE_IDENTIFICADOR, $listEmail['co_identificador_cliente']);
                    $this->phpListDAO->insertUserAttributeValue($userId, PHPListDAO::USER_ATTR_CLIENTE_NOME, $listEmail['no_cliente']);
                }
                //CUSTOMER WITHOUT EMAIL
                else {
                    $this->phpListDAO->insertUserWithoutEmail($cliente, $listEmail);
                }
            } else {
                $this->phpListDAO->updateUser($cliente['id'], $listEmail['de_email_cliente']);
                $this->phpListDAO->updateUserAttributeValue($cliente['id'], PHPListDAO::USER_ATTR_CLIENTE_NOME, $listEmail['no_cliente']);
            }

            $cliente = $this->phpListDAO->findUserByIdentficadorCliente($listEmail['co_identificador_cliente']);
            $userList = $this->phpListDAO->findUserList($cliente['id'], $list['id']);
            $listInvestmentFund = $this->phpListDAO->findOneListInvestmentFund($cliente['id'], $list['id']);

            if (!$userList) {
                $this->phpListDAO->insertUserList($cliente['id'], $list['id']);
            }

            if (!$listInvestmentFund) {
                $this->phpListDAO->insertListInvestmentFund($cliente['id'], $list['id'], $listEmail);
            } else {
                $this->phpListDAO->updateListInvestmentFund($cliente['id'], $list['id'], $listEmail);
            }

        }
    }

}