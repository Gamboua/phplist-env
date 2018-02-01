<?php

namespace phplist\Caixa\Functionality\Infrastructure\DB\DAO;

use phplist\Caixa\Functionality\Domain\Model\User;
use phplist\Caixa\Functionality\Domain\Model\UserRepository;
use phplist\Caixa\Functionality\Infrastructure\DB\Connection;
use phplist\Caixa\Functionality\Infrastructure\Shared\AbstractDAO;
use phplist\Caixa\Functionality\Infrastructure\Shared\PHPList;

/**
 * Class UserDAO
 *
 * @package phplist\Caixa\Functionality\Infrastructure\DB\DAO
 */
class UserDAO extends AbstractDAO implements UserRepository
{
    /**
     * @param integer $id
     * @return User
     */
    public function findOne($id)
    {
        $sql = <<<SQL
SELECT 
  id,
  email
FROM 
  phplist.phplist_user_user
WHERE 
  id = ?;
SQL;

        $connectionPDO = $this->connection->getPDO();
        $connectionPDOStmt = $connectionPDO->prepare($sql);
        $connectionPDOStmt->execute([
            $id,
        ]);

        $user = null;
        $fetched = $connectionPDOStmt->fetch(\PDO::FETCH_ASSOC);

        if ($fetched) {
            $user = User::fromArray([
                'id' => $fetched['id'],
                'email' => $fetched['email'],
            ]);
        }

        return $user;
    }

    /**
     * @param $attributeId
     * @param $value
     * @return User
     */
    public function findOneByUserAttribute($attributeId, $value)
    {
        $sql = <<<SQL
SELECT 
  user.id AS id,
  user.email AS email
FROM 
  phplist.phplist_user_user  user
  INNER JOIN phplist_user_user_attribute attr1 ON user.id = attr1.userid
WHERE 
  attr1.attributeid = ?
  AND attr1.value = ?;
SQL;

        $connectionPDO = $this->connection->getPDO();
        $connectionPDOStmt = $connectionPDO->prepare($sql);
        $connectionPDOStmt->execute([
            $attributeId,
            $value,
        ]);

        $user = null;
        $fetched = $connectionPDOStmt->fetch(\PDO::FETCH_ASSOC);

        if ($fetched) {
            $user = User::fromArray([
                'id' => $fetched['id'],
                'email' => $fetched['email'],
            ]);
        }

        return $user;
    }

    /**
     * @param User $user
     * @return void
     */
    public function add(User $user)
    {
        $sql = <<<SQL
INSERT INTO 
  phplist.phplist_user_user
  (
    email,
    confirmed,
    entered,
    uniqid,
    htmlemail
  )
VALUES
  (
    ?,
    ?,
    ?,
    ?,
    ?
  );
SQL;

        $connectionPDO = $this->connection->getPDO();
        $connectionPDOStmt = $connectionPDO->prepare($sql);
        $connectionPDOStmt->execute([
            $user->getEmail(),
            1,
            $this->phpList->getEnteredNow(),
            $this->phpList->getUniqid(),
            1
        ]);

        $user->setId($connectionPDO->lastInsertId());
    }

    /**
     * @param User $user
     * @return void
     */
    public function merge(User $user)
    {
        $sql = <<<SQL
UPDATE 
  phplist.phplist_user_user
SET 
  email = ?
WHERE 
  id = ?;
SQL;

        $connectionPDO = $this->connection->getPDO();
        $connectionPDOStmt = $connectionPDO->prepare($sql);
        $connectionPDOStmt->execute([
            $user->getEmail(),
            $user->getId(),
        ]);
    }
}
