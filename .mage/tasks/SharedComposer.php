<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright  Mandrágora Web-Based Systems 2015 (http://www.mandragora-web-systems.com)
 */
namespace Task;

use Mage\Task\AbstractTask;

class SharedComposer extends AbstractTask
{
    public function getName()
    {
        return 'Creating symlinks to composer\'s shared folder';
    }

    public function run()
    {
        $folderTo = $this->getConfig()->deployment('to');

        return $this->runCommandRemote("ln -s $folderTo/shared/vendor ./");
    }
}
