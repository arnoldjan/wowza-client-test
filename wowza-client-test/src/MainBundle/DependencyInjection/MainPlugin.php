<?php

namespace Mi\WebcastManager\Bundle\MainBundle\DependencyInjection;

use Mi\PuliBundlePlugins\ServiceBundlePlugin;
use Mi\WebcastManager\Bundle\MainBundle\DependencyInjection\Compiler\ValidatorPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Enrico Hoffmann <enrico.hoffmann@movingimage.com>
 * @author Volker Bredow <volker.bredow@movingimage.com>
 */
class MainPlugin extends ServiceBundlePlugin
{

    /**
     * @inheritDoc
     */
    public function name()
    {
        return 'main';
    }

    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ValidatorPass($this->discovery));
    }

    /**
     * @param array            $pluginConfiguration The part of the bundle configuration for this plugin
     * @param ContainerBuilder $container
     */
    protected function loadInternal(array $pluginConfiguration, ContainerBuilder $container)
    {

    }

}
