<?php

namespace Hshn\AngularBundle\TemplateCache;

use Symfony\Component\Finder\SplFileInfo;

class Compiler
{
    /**
     * @param SplFileInfo  $files
     * @param string       $moduleName
     *
     * @return string
     */
    public function compile(array $files, $moduleName)
    {
        $output = "'use strict';\n";
        $output .= "var app = angular.module('{$moduleName}', [])\n";

        /* @var $file SplFileInfo */
        foreach ($files as $file) {
            $templateId = $file->getRelativePathname();
            $output .= "  .run(['\$templateCache', function (\$templateCache) {\n";
            $output .= "    \$templateCache.put('{$templateId}',\n";

            $html = array();
            foreach (new \SplFileObject($file->getPathname(), 'r') as $line) {
                $html[] = '    \'' . str_replace(array("\r", "\n", '\''), array('\r', '\n', '\\\''), $line) . "'";
            }

            $output .= implode(" +\n", $html) . ");\n";
            $output .= "  }])\n";
        }

        $output .= ";\n";

        return $output;
    }
}
