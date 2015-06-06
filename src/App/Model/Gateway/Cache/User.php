<?php
class App_Model_Gateway_Cache_User
    extends Mandragora_Gateway_Decorator_CacheAbstract
{
    /**
     * @param string $username
     * @return array
     */
    public function findOneByUsernameAndStateActive($username)
    {
        $user = $this->getCache()->load('user' . md5(serialize($username)));
        if (!$user || $user['state'] != App_Enum_UserState::Active) {
            $user = $this->gateway->findOneByUsernameAndStateActive($username);
            $this->getCache()->save($user, 'user' . md5(serialize($username)));
        }
        return $user;
    }

    /**
     * @param string $username
     * @return array
     */
    public function findOneByUsername($username)
    {
        $user = $this->getCache()->load('user' . md5(serialize($username)));
        if (!$user) {
            $user = $this->gateway->findOneByUsername($username);
            $this->getCache()->save($user, 'user' . md5(serialize($username)));
        }
        return $user;
    }

    /**
     * @param string $confirmationKey
     * @return array
     * @throws Mandragora_Doctrine_Gateway_NoResultsFoundException
     */
    public function findOneByConfirmationKey($confirmationKey)
    {
        $client = $this->getCache()->load('client' . $confirmationKey);
        if (!$client) {
            $client = $this->gateway
                           ->findOneByConfirmationKey($confirmationKey);
            $this->getCache()->save($client, 'client' . $confirmationKey);
        }
        return $client;
    }

    /**
     * @param array $userInformation
     * @return int
     */
    public function updateClientAccountToConfirmed(array $userInformation)
    {
        $this->gateway->updateClientAccountToConfirmed($userInformation);
        $cache = $this->getCache();
        $cache->remove('client' . $userInformation['confirmationKey']);
        $cache->remove('user' .  md5(serialize($userInformation['username'])));
    }

    /**
     * @param string $userName
     * @param string $newState
     * @return void
     */
    public function updateUserState($userName, $newState)
    {
        $this->gateway->updateUserState($userName, $newState);
        $this->getCache()->remove('user' . md5(serialize($userName)));
    }

    /**
     * @param Mandragora_Model_Abstract $ser
     */
    public function insert(Mandragora_Model_Abstract $user)
    {
        $this->gateway->insert($user);
        $this->getCache()->save(
            $user->toArray(), 'user' . md5(serialize($user->username))
        );
    }

    /**
     * @param Mandragora_Model_Abstract $user
     */
    public function update(Mandragora_Model_Abstract $user)
    {
        $this->gateway->update($user);
        $this->getCache()->save(
            $user->toArray(), 'user' . md5(serialize($user->username))
        );
    }

    /**
     * @param Mandragora_Model_Abstract $user
     */
    public function delete(Mandragora_Model_Abstract $user)
    {
        $this->gateway->delete($user);
        $this->getCache()->remove('user' . md5(serialize($user->username)));
    }

}