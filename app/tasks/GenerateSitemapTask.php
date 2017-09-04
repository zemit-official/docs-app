<?php

namespace Docs\Cli\Tasks;

use function file_put_contents;

use Phalcon\CLI\Task;

use function Docs\Functions\app_path;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use FilesystemIterator;
use SplFileInfo;

/**
 * GenerateSitemapTask
 */
class GenerateSitemapTask extends Task
{
    public function mainAction()
    {
        $output   = app_path('public/sitemap.xml');

        $elements    = [];
        $path        = app_path('docs/');
        $dirIterator = new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS);
        $iterator    = new RecursiveIteratorIterator(
            $dirIterator,
            RecursiveIteratorIterator::CHILD_FIRST
        );

        /** @var SplFileInfo $file */
        foreach ($iterator as $file) {
            if ('md' === $file->getExtension() || 'html' === $file->getExtension()) {
                $fullFile   = $file->getPath() . '/' . $file->getFilename();
                $elements[] = str_replace(
                    [
                        app_path('docs/'),
                        '.md',
                        '.html',
                    ],
                    [
                        '',
                        '',
                        '',
                    ],
                    $fullFile
                );
            }
        }

        sort($elements);

        $contents = $this->viewSimple->render(
            'index/sitemap',
            [
                'elements' => $elements,
            ]
        );

        file_put_contents($output, $contents);
    }
}