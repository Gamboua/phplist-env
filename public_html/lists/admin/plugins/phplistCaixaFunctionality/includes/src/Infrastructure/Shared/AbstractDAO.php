<?php

namespace phplist\Caixa\Functionality\Infrastructure\Shared;

use phplist\Caixa\Functionality\Infrastructure\DB\Connection;

/**
 * Class AbstractDAO
 *
 * @package phplist\Caixa\Functionality\Infrastructure\Shared
 */
abstract class AbstractDAO
{
    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @var PHPList
     */
    protected $phpList;

    /**
     * @var array
     */
    private $tables;

    /**
     * @var string
     */
    private $tablePrefix;
    
    /**
     * 
     * @var PHPListCaixaLogger
     */
    private $logger;

    /**
     * AbstractDAO constructor.
     *
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;

        global $tables;
        global $table_prefix;

        $this->tables = $tables;
        $this->tablePrefix = $table_prefix;
    }

    /**
     * @param Connection $connection
     */
    public function setConnection($connection)
    {
        $this->connection = $connection;
    }
    
    public function setPhpListCaixaLogger(PHPListCaixaLogger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @return PHPListCaixaLogger
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param PHPList $phpList
     */
    public function setPhpList($phpList)
    {
        $this->phpList = $phpList;
    }

    /**
     * @return array
     */
    protected function getTables()
    {
        return $this->tables;
    }

    /**
     * @return string
     */
    protected function getTablePrefix()
    {
        return $this->tablePrefix;
    }
}
