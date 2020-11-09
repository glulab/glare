<?php

namespace Glare\Support\Helpers;

class DiskHelper {

    public function deleteDirectory($dir, $deleteParent = true)
    {
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $fileinfo) {
            $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
            $todo($fileinfo->getRealPath());
        }

        if ($deleteParent) {
            rmdir($dir);
        }
    }
}
