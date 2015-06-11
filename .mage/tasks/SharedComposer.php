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
        return 'Creating symlinks to shared folder composer';
    }

    public function run()
    {
        $folderTo = $this->getConfig()->deployment('to');

        $command = "ln -s $folderTo/shared/vendor ./";
        return $this->runCommandRemote($command);
    }
}
