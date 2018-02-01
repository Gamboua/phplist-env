<?php

namespace phplist\Caixa\Functionality\Infrastructure\DB\DAO;

use phplist\Caixa\Functionality\Domain\Model\SubscriptionList;
use phplist\Caixa\Functionality\Domain\Model\User;
use phplist\Caixa\Functionality\Domain\Model\UserSubscription;
use phplist\Caixa\Functionality\Domain\Model\UserSubscriptionRepository;
use phplist\Caixa\Functionality\Infrastructure\DB\Connection;
use phplist\Caixa\Functionality\Infrastructure\Shared\AbstractDAO;
use phplist\Caixa\Functionality\Infrastructure\Shared\PHPList;

/**
 * Class UserSubscriptionDAO
 *
 * @package phplist\Caixa\Functionality\Infrastructure\DB\DAO
 */
class UserSubscriptionDAO extends AbstractDAO implements UserSubscriptionRepository
{
    /**
     * @param User $user
     * @param SubscriptionList $subscriptionList
     *
     * @return UserSubscription
     */
    public function findOne(User $user, SubscriptionList $subscriptionList)
    {
        $sql = <<<SQL
SELECT 
  userid,
  listid
FROM 
  phplist.phplist_listuser
WHERE 
  userid = ?
  AND listid = ?;
SQL;

        $connectionPDO = $this->connection->getPDO();
        $connectionPDOStmt = $connectionPDO->prepare($sql);
        $connectionPDOStmt->execute([
            $user->getId(),
            $subscriptionList->getId(),
        ]);

        $userSubscription = null;
        $fetched = $connectionPDOStmt->fetch(\PDO::FETCH_ASSOC);

        if ($fetched) {
            $userSubscription = UserSubscription::fromArray([
                'user' => $user,
                'subscriptionList' => $subscriptionList,
            ]);
        }

        return $userSubscription;
    }

    /**
     * @param UserSubscription $userSubscription
     */
    public function add(UserSubscription $userSubscription)
    {
        $sql = <<<SQL
INSERT INTO 
  phplist.phplist_listuser
  (
    userid,
    listid,
    entered
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
            $userSubscription->getUser()->getId(),
            $userSubscription->getSubscriptionList()->getId(),
            $this->phpList->getEnteredNow(),
        ]);
    }
}
