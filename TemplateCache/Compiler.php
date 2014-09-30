<?php

namespace Hshn\AngularBundle\TemplateCache;

use Symfony\Component\Finder\SplFileInfo;

class Compiler
{
    /**
     * @param SplFileInfo[] $files
     * @param string        $moduleName
     * @param bool          $newModule
     *
     * @return string
     */
    public function compile(array $files, $moduleName, $newModule = false)
    {
        $output = "'use strict';\n\n";
        $output .= $newModule ? "angular.module('{$moduleName}', [])\n" : "angular.module('{$moduleName}')\n";
        $output .= "  .run(['\$templateCache', function (\$templateCache) {\n";

        /* @var $file SplFileInfo */
        foreach ($files as $file) {
            $templateId = $file->getRelativePathname();
            $output .= "    \$templateCache.put('{$templateId}',\n";

            $html = array();
            foreach (new \SplFileObject($file->getPathname(), 'r') as $line) {
                $html[] = '    \'' . str_replace(array("\r", "\n", '\''), array('\r', '\n', '\\\''), $line) . "'";
            }

            $output .= implode(" +\n", $html) . ");\n";
        }

        $output .= "  }])\n";
        $output .= ";\n";

        return $output;
    }
}
