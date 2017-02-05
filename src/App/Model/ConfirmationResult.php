<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Model;

use App\Model\Gateway\Cache\User;
use Zend_Date;
use Mandragora_Gateway_Doctrine_NoResultsFoundException;

/**
 * This class confirms client account creation
 */
class ConfirmationResult
{
    /**
     * @var string
     */
    const USER_NOT_FOUND = 'userNotFound';

    /**
     * @var string
     */
    const KEY_EXPIRED = 'keyExpired';

    /**
     * @var string
     */
    const CONFIRMATION_SUCCED = 'confirmationSucced';

    /**
     * @var Edeco_Model_Gateway_User
     */
    protected $userGateway;

    /**
     * @var string
     */
    protected $confirmationKey;

    /**
     * @var string
     */
    protected $code;

    /**
     * @param string $confirmationKey
     * @param Edeco_Model_Gateway_User $userGateway
     */
    public function __construct(
        $confirmationKey, User $userGateway
    )
    {
        $this->confirmationKey = $confirmationKey;
        $this->userGateway = $userGateway;
    }

    /**
     * @param $value
     */
    public function isValid()
    {
        try {
            $user = $this->userGateway->findOneByConfirmationKey(
                $this->confirmationKey
            );
            $creationDate = new Zend_Date($user['creationDate'], 'YYYY-MM-dd');
            $currentDate = new Zend_Date(null, 'YYYY-MM-dd');
            $differenceInDays = (
                $currentDate->getTimestamp() - $creationDate->getTimestamp()
            ) / (3600 * 24);
            if ($differenceInDays > 2) {
                $this->code = self::KEY_EXPIRED;
                return false;
            }
            $this->code = self::CONFIRMATION_SUCCED;
            return true;
        } catch(Mandragora_Gateway_Doctrine_NoResultsFoundException $nrfe) {
            $this->code = self::USER_NOT_FOUND;
            return false;
        }
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }
}
