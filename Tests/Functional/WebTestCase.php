<?php


namespace Hshn\AngularBundle\Tests\Functional;

use Symfony\Component\Finder\Finder;

/**
 * @author Shota Hoshino <lga0503@gmail.com>
 */
class WebTestCase extends \Symfony\Bundle\FrameworkBundle\Test\WebTestCase
{
    /**
     * {@inheritdoc}
     */
    protected static function getKernelClass()
    {
        require __DIR__.'/AppKernel.php';

        return 'Hshn\AngularBundle\Tests\Functional\AppKernel';
    }
}
