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

class SharedFolderImages extends AbstractTask
{
    public function getName()
    {
        return 'Creating symlinks to shared folder images';
    }

    public function run()
    {
        $sharedImages = $this->getParameter('sharedImages', false);

        if ($sharedImages) {
            $folderTo = $this->getConfig()->deployment('to');
            $releaseId = $this->getConfig()->getReleaseId();
            foreach (explode(',', $sharedImages) as $folder) {
                $command = "ln -s $folderTo/shared/$folder edeco.mx/images/$folder";
                $this->runCommandRemote($command);
            }

            return true;
        } else {
            return false;
        }
    }
}
