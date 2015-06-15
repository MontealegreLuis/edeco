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

class AccessFile extends AbstractTask
{
    public function getName()
    {
        return 'Copy access files';
    }

    public function run()
    {
        $folders = $this->getParameter('foldersAccess', []);
        $folderTo = $this->getConfig()->deployment('to');

        foreach (explode(',', $folders) as $folder) {
            $command = "cp $folderTo/shared/.htaccess $folder/";
            $this->runCommandRemote($command);
        }

        return true;
    }
}
