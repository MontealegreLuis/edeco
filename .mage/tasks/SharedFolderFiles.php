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

class SharedFolderFiles extends AbstractTask
{
    public function getName()
    {
        return 'Creating symlinks to shared folder files';
    }

    public function run()
    {
        $sharedFiles = $this->getParameter('sharedFiles', []);
        $folderTo = $this->getConfig()->deployment('to');

        foreach (explode(',', $sharedFiles) as $folder) {
            $command = "ln -s $folderTo/shared/$folder application/files/$folder";
            $this->runCommandRemote($command);
        }

        return true;
    }
}
