<?php

namespace phplist\Caixa\Functionality\Infrastructure\DB\DAO;

use phplist\Caixa\Functionality\Domain\Model\SubscriptionList;
use phplist\Caixa\Functionality\Domain\Model\SubscriptionListRepository;
use phplist\Caixa\Functionality\Infrastructure\Shared\AbstractDAO;

/**
 * Class SubscriptionDAO
 *
 * @package phplist\Caixa\Functionality\Infrastructure\DB\DAO
 */
class SubscriptionListDAO extends AbstractDAO implements SubscriptionListRepository
{
    /**
     * @param integer $id
     *
     * @return SubscriptionList
     */
    public function findOne($id)
    {
        $sql = <<<SQL
SELECT 
  id,
  name,
  active,
  owner
FROM 
  phplist.phplist_list
WHERE 
  id = ?;
SQL;

        $connectionPDO = $this->connection->getPDO();
        $connectionPDOStmt = $connectionPDO->prepare($sql);
        $connectionPDOStmt->execute([
            $id,
        ]);

        $subscriptionList = null;
        $fetched = $connectionPDOStmt->fetch(\PDO::FETCH_ASSOC);

        if ($fetched) {
            $subscriptionList = SubscriptionList::fromArray([
                'id' => $fetched['id'],
                'name' => $fetched['name'],
                'active' => $fetched['active'],
                'owner' => $fetched['owner']
            ]);
        }

        return $subscriptionList;
    }

    /**
     * @param string $name
     *
     * @return SubscriptionList
     */
    public function findOneByName($name)
    {
        $sql = <<<SQL
SELECT 
  id,
  name,
  active,
  owner
FROM 
  phplist.phplist_list
WHERE 
  name = ?;
SQL;

        $connectionPDO = $this->connection->getPDO();
        $connectionPDOStmt = $connectionPDO->prepare($sql);
        $connectionPDOStmt->execute([
            $name,
        ]);

        $subscriptionList = null;
        $fetched = $connectionPDOStmt->fetch(\PDO::FETCH_ASSOC);

        if ($fetched) {
            $subscriptionList = SubscriptionList::fromArray([
                'id' => $fetched['id'],
                'name' => $fetched['name'],
                'active' => $fetched['active'],
                'owner' => $fetched['owner']
            ]);
        }

        return $subscriptionList;
    }

    /**
     * @return SubscriptionList[]
     */
    public function findAll()
    {
        $sql = <<<SQL
SELECT 
  id,
  name,
  active,
  owner
FROM
  phplist.phplist_list
ORDER BY
  listorder,
  name;
SQL;

        $connectionPDO = $this->connection->getPDO();
        $connectionPDOStmt = $connectionPDO->prepare($sql);
        $connectionPDOStmt->execute();

        $subscriptionLists = [];
        foreach ($connectionPDOStmt->fetchAll(\PDO::FETCH_ASSOC) as $fetched) {
            $subscriptionLists[] = SubscriptionList::fromArray([
                'id' => $fetched['id'],
                'name' => $fetched['name'],
                'active' => $fetched['active'],
                'owner' => $fetched['owner'],
            ]);
        }

        return $subscriptionLists;
    }

    /**
     * @param SubscriptionList $subscriberList
     *
     * @return void
     */
    public function add(SubscriptionList $subscriberList)
    {
        $sql = <<<SQL
INSERT INTO 
  phplist.phplist_list
  (
    name,
    entered,
    active,
    owner
  )
VALUES
  (
    ?,
    ?,
    ?,
    ?
  );
SQL;

        $connectionPDO = $this->connection->getPDO();
        $connectionPDOStmt = $connectionPDO->prepare($sql);
        $connectionPDOStmt->execute([
            $subscriberList->getName(),
            $this->phpList->getEnteredNow(),
            $subscriberList->getActive(),
            $subscriberList->getOwner()
        ]);

        $subscriberList->setId($connectionPDO->lastInsertId());
    }
}
