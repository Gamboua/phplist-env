<?php
namespace phplist\Caixa\Functionality\Domain\Service;

use phplist\Caixa\Functionality\Domain\Model\Caixa\Client;
use phplist\Caixa\Functionality\Domain\Model\User;
use phplist\Caixa\Functionality\Domain\Model\UserAttribute;
use phplist\Caixa\Functionality\Domain\Model\UserAttributeRepository;
use phplist\Caixa\Functionality\Domain\Model\UserRepository;
use phplist\Caixa\Functionality\Domain\Shared\EmailValidator;

/**
 * Class ImportUser
 *
 * @package phplist\Caixa\Functionality\Domain\Service
 */
class ImportUser
{

    /**
     *
     * @var UserRepository
     */
    private $userRepository;

    /**
     *
     * @var UserAttributeRepository
     */
    private $userAttributeRepository;

    /**
     *
     * @var integer
     */
    private $totalNewUsers = 0;

    /**
     *
     * @var integer
     */
    private $totalUpdatedUsers = 0;

    /**
     * ImportUser constructor.
     *
     * @param UserRepository $userRepository
     * @param UserAttributeRepository $userAttributeRepository
     */
    public function __construct(UserRepository $userRepository, UserAttributeRepository $userAttributeRepository)
    {
        $this->userRepository = $userRepository;
        $this->userAttributeRepository = $userAttributeRepository;
    }

    /**
     *
     * @param Client $client
     *
     * @return User
     */
    public function findOrSave(Client $client)
    {
        if (!EmailValidator::isValid($client->getEmail())) {
            // if invalid or no e-mail, do not proceed with the import
            return null;
        }

        $user = $this->userRepository->findOneByUserAttribute(UserAttribute::CLIENT_IDENTIFIER, $client->getIdentifier());
        if (!$user) {

            $user = new User();
            $user->setEmail($client->getEmail());
            $this->userRepository->add($user);

            $clientIdentifier = new UserAttribute();
            $clientIdentifier->setUser($user);
            $clientIdentifier->setAttributeId(UserAttribute::CLIENT_IDENTIFIER);
            $clientIdentifier->setValue($client->getIdentifier());
            $this->userAttributeRepository->add($clientIdentifier);

            $clientName = new UserAttribute();
            $clientName->setUser($user);
            $clientName->setAttributeId(UserAttribute::CLIENT_NAME);
            $clientName->setValue($client->getName());
            $this->userAttributeRepository->add($clientName);
            $this->totalNewUsers++;
        } else {

            $user->setEmail($client->getEmail());
            $this->userRepository->merge($user);

            $clientName = new UserAttribute();
            $clientName->setUser($user);
            $clientName->setAttributeId(UserAttribute::CLIENT_NAME);
            $clientName->setValue($client->getName());
            $this->userAttributeRepository->merge($clientName);
            $this->totalUpdatedUsers++;
        }

        return $user;
    }

    /**
     * @return int
     */
    public function getTotalNewUsers()
    {
        return $this->totalNewUsers;
    }

    /**
     * @return int
     */
    public function getTotalUpdatedUsers()
    {
        return $this->totalUpdatedUsers;
    }

}
