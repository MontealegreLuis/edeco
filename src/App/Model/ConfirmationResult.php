<?php
/**
 * This class confirms client account creation
 *
 * PHP version 5
 *
 * LICENSE: Redistribution and use of this file in source and binary forms,
 * with or without modification, is not permitted under any circumstance
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @category   Application
 * @package    Edeco
 * @subpackage Model
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */

/**
 * This class confirms client account creation
 *
 * @category   Application
 * @package    Edeco
 * @subpackage Model
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */
class App_Model_ConfirmationResult
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
        $confirmationKey, App_Model_Gateway_Cache_User $userGateway
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