<?php

namespace Hshn\AngularBundle\Tests\Functional;
use Assetic\Factory\AssetFactory;


/**
 * @author Shota Hoshino <lga0503@gmail.com>
 */
class DumpAssetTest extends WebTestCase
{
    /**
     * @test
     */
    public function test()
    {
        static::bootKernel();

        /* @var $factory AssetFactory */
        $factory = static::$kernel->getContainer()->get('assetic.asset_factory');
        $asset = $factory->createAsset(array('@ng_template_cache_foo'));

        $asset->load();

        $this->assertStringEqualsFile(__DIR__.'/Fixtures/ng_template_cache_foo.js', $asset->getContent());
    }
}
