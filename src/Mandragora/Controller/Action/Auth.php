<?php
/**
 * Base class for Mandragora controllers that handle authentication process
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
 * @category   Library
 * @package    Mandragora
 * @subpackage Controller_Action
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */

/**
 * Base class for Mandragora controllers that handle authentication process
 *
 * @category   Library
 * @package    Mandragora
 * @subpackage Controller_Action
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */
class   Mandragora_Controller_Action_Auth
extends Mandragora_Controller_Action_Abstract
{
    /**
     * @var array
     */
    protected $sessionOptions;

    /**
     * @return mixed
     * @throws Zend_Controller_Action_Exception
     */
    public function getSessionOption($key)
    {
        if (is_null($this->sessionOptions)) {
            $this->sessionOptions = $this->getInvokeArg('bootstrap')
                                         ->getPluginResource('session')
                                         ->getOptions();
        }
        if (!array_key_exists($key, $this->sessionOptions)) {
            $message = "Session option '$key' does not exists";
            throw new Zend_Controller_Action_Exception($message, 500);
        }
        return $this->sessionOptions[$key];
    }
}