<?php

namespace phplist\Caixa\Functionality\Infrastructure\DB\DAO;

use phplist\Caixa\Functionality\Domain\Model\User;
use phplist\Caixa\Functionality\Domain\Model\UserAttribute;
use phplist\Caixa\Functionality\Domain\Model\UserAttributeRepository;
use phplist\Caixa\Functionality\Infrastructure\Shared\AbstractDAO;

/**
 * Class UserAttributeDAO
 *
 * @package phplist\Caixa\Functionality\Infrastructure\DB\DAO
 */
class UserAttributeDAO extends AbstractDAO implements UserAttributeRepository
{
    /**
     * @param User $user
     * @param $attributeId
     *
     * @return UserAttribute
     */
    public function findOne(User $user, $attributeId)
    {
        $sql = <<<SQL
SELECT 
  value
FROM 
  phplist.phplist_user_user_attribute
WHERE 
  userid = ?
  AND attributeid = ?;
SQL;

        $connectionPDO = $this->connection->getPDO();
        $connectionPDOStmt = $connectionPDO->prepare($sql);
        $connectionPDOStmt->execute([
            $user->getId(),
            $attributeId,
        ]);

        $userAttribute = null;
        $fetched = $connectionPDOStmt->fetch(\PDO::FETCH_ASSOC);

        if ($fetched) {
            $userAttribute = UserAttribute::fromArray([
                'user' => $user,
                'attributeId' => $attributeId,
                'value' => $fetched['value'],
            ]);
        }

        return $userAttribute;
    }

    /**
     * @param UserAttribute $userAttribute
     */
    public function add(UserAttribute $userAttribute)
    {
        $sql = <<<SQL
INSERT INTO 
  phplist.phplist_user_user_attribute
  (
    userid,
    attributeid,
    value
  )
VALUES
  (
    ?,
    ?,
    ?
  );
SQL;

        $connectionPDO = $this->connection->getPDO();
        $connectionPDOStmt = $connectionPDO->prepare($sql);
        $connectionPDOStmt->execute([
            $userAttribute->getUser()->getId(),
            $userAttribute->getAttributeId(),
            $userAttribute->getValue(),
        ]);
    }

    /**
     * @param UserAttribute $userAttribute
     */
    public function merge(UserAttribute $userAttribute)
    {
        $sql = <<<SQL
UPDATE 
  phplist.phplist_user_user_attribute
SET 
  value = ?
WHERE 
  userid = ?
  AND attributeid = ?;
SQL;

        $connectionPDO = $this->connection->getPDO();
        $connectionPDOStmt = $connectionPDO->prepare($sql);
        $connectionPDOStmt->execute([
            $userAttribute->getValue(),
            $userAttribute->getUser()->getId(),
            $userAttribute->getAttributeId(),
        ]);
    }
}
