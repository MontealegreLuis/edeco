<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright  Mandrágora Web-Based Systems 2015 (http://www.mandragora-web-systems.com)
 */
namespace Task;

use \Mage\Task\AbstractTask;

class ComposerInstall extends AbstractTask
{
    public function getName()
    {
        return 'Composer install';
    }

    /**
     * Executes composer install using the shared folder as its vendor directory
     *
     * @return boolean
     */
    public function run()
    {
        $command = "COMPOSER_VENDOR_DIR={$this->getConfig()->deployment('to')}/shared/vendor composer install -o --prefer-dist --no-dev";

        return $this->runCommandRemote($command);
    }
}
