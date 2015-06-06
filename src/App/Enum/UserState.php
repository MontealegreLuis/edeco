<?php
/**
 * Enumeration for the user account states
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
 * @subpackage Enum
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */

/**
 * Enumeration for the user account states
 *
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @version    SVN: $Id$
 * @copyright  Mandrágora Web-Based Systems 2010
 * @category   Application
 * @package    Edeco
 * @subpackage Enum
 * @history    may 27, 2010
 *             LMV
 *             - Class Creation
 */
abstract class App_Enum_UserState implements Mandragora_Enum_Interface
{
    /**
     * User account active
     *
     * @var string
     */
    const Active = 'active';

    /**
     * New unconfirmed user account
     *
     * @var string
     */
    const Unconfirmed = 'unconfirmed';

    /**
     * Inactive user account
     *
     * @var string
     */
    const Inactive = 'inactive';

    /**
     * Banned user account
     *
     * @var string
     */
    const Banned = 'banned';

    /**
     * Return all the available types as an associative array
     *
     * @return array
     */
    public static function values()
    {
        return array(
            self::Active => 'Activo',
            self::Unconfirmed => 'Sin confirmar',
            self::Inactive => 'Inactivo',
            self::Banned => 'Bloqueado',
        );
    }

}