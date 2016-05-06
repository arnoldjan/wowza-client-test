<?php

namespace Mi\WebcastManager\Application;

use JMS\SerializerBundle\JMSSerializerBundle;
use Mi\Bundle\PuliMetadataFileLocatorBundle\MiPuliMetadataFileLocatorBundle;
use Mi\Bundle\WowzaGuzzleClientBundle\MiWowzaGuzzleClientBundle;
use Mi\WebcastManager\Bundle\MainBundle\MiWebcastManagerMainBundle;
use Puli\SymfonyBundle\PuliBundle;
use Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle;
use Symfony\Bundle\DebugBundle\DebugBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\MonologBundle\MonologBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Bundle\WebProfilerBundle\WebProfilerBundle;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $class = PULI_FACTORY_CLASS;
        /** @var \Puli\GeneratedPuliFactory $repoFactory */
        $repoFactory = new $class;

        $repo = $repoFactory->createRepository();
        $discovery = $repoFactory->createDiscovery($repo);

        $bundles = [
            new JMSSerializerBundle(),
            new SensioFrameworkExtraBundle(),
            new FrameworkBundle(),
            new MonologBundle(),
            new TwigBundle(),
            new PuliBundle(),
            new MiPuliMetadataFileLocatorBundle(),
            new MiWowzaGuzzleClientBundle(),

            new MiWebcastManagerMainBundle($discovery, $repo),
        ];

        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            $bundles[] = new DebugBundle();
            $bundles[] = new WebProfilerBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(sprintf('%s/config/config_%s.yml', __DIR__, $this->getEnvironment()));
    }

    public function getCacheDir()
    {
        return __DIR__ . '/../var/cache/' . $this->getEnvironment();
    }

    public function getLogDir()
    {
        return __DIR__ . '/../var/log';
    }
}
