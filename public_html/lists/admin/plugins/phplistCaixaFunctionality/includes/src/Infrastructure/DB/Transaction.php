<?php

namespace phplist\Caixa\Functionality\Infrastructure\DB;

/**
 * Class Transaction
 *
 * @package phplist\Caixa\Functionality\Infrastructure\DB
 */
class Transaction
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * Transaction constructor.
     *
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param callable $action
     * @return mixed
     * @throws \Exception
     */
    public function execute(callable $action)
    {
        $connectionPDO = $this->connection->getPDO();

        try {
            $connectionPDO->beginTransaction();
            $result = call_user_func($action);
            $connectionPDO->commit();
        } catch (\Exception $e) {
            $connectionPDO->rollBack();
            throw $e;
        }

        return $result;
    }
}
