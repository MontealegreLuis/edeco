<?php
/**
 * Accuracy for the Address
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
 * @subpackage Accuracy
 * @author     LNJ <lemuel.nonoal@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */

/**
 * Accuracy for the Addresss
 *
 * @author     LNJ <lemuel.nonoal@mandragora-web-systems.com>
 * @version    SVN: $Id$
 * @copyright  Mandrágora Web-Based Systems 2010
 * @category   Application
 * @package    Edeco
 * @subpackage Accuracy
 */
abstract class App_Enum_AddressAccuracy implements Mandragora_Enum_Interface
{
    /**
     * Accurancy unknown for Address
     *
     * @var string
     */
    const ACCURACY_UNKNOWN = 0;

    /**
     * Accuracy country for Address
     *
     * @var string
     */
    const ACCURACY_COUNTRY = 1;

    /**
     * Accuracy region for Address
     *
     * @var string
     */
    const ACCURACY_REGION = 2;

    /**
     * Accuracy subregion for Address
     *
     * @var string
     */
    const ACCURACY_SUBREGION = 3;

    /**
     * Accuracy town for Address
     *
     * @var string
     */
    const ACCURACY_TOWN = 4;

    /**
     * Accuracy postcode for Address
     *
     * @var string
     */
    const ACCURACY_POSTCODE = 5;

    /**
     * Accuracy street for Address
     *
     * @var string
     */
    const ACCURACY_STREET = 6;

    /**
     * Accuracy intersection for Address
     *
     * @var string
     */
    const ACCURACY_INTERSECTION = 7;

    /**
     * Accuracy address for Address
     *
     * @var string
     */
    const ACCURACY_ADDRESS = 8;

    /**
     * Accuracy address for Premise (building name, property name,
     * shopping center, etc.) level accuracy
     *
     * @var string
     */
    const ACCURACY_PREMISE = 9;

    /**
     * Return all the accuracy as an associative array
     *
     * @return array
     */
    public static function values()
    {
        return array(
            self::ACCURACY_UNKNOWN => 'Desconocido',
            self::ACCURACY_COUNTRY => 'País',
		    self::ACCURACY_REGION => 'Región',
		    self::ACCURACY_SUBREGION => 'Subregión',
		    self::ACCURACY_TOWN => 'Ciudad',
		    self::ACCURACY_POSTCODE => 'Código Postal',
		    self::ACCURACY_STREET => 'Calle',
		    self::ACCURACY_INTERSECTION => 'Intersección',
		    self::ACCURACY_ADDRESS => 'Dirección',
		    self::ACCURACY_PREMISE =>
                'Nombre del edificio, lugar público, centro comercial, etc.',
        );
    }

}