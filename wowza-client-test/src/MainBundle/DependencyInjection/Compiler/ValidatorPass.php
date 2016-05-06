<?php

namespace Mi\WebcastManager\Bundle\MainBundle\DependencyInjection\Compiler;

use Puli\Discovery\Binding\ResourceBinding;
use Puli\Repository\Resource\FileResource as PuliFileResource;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Volker Bredow <volker.bredow@movingimage.com>
 * @author Jan Arnold <jan.arnold@movingimage.com>
 */
class ValidatorPass extends PuliPass
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('validator.builder')) {
            return;
        }

        $validatorBuilder = $container->getDefinition('validator.builder');
        $validatorFiles = [];

        $bindinigs = $this->discovery->findBindings('mi/validation');

        /** @var ResourceBinding $bindinig */
        foreach ($bindinigs as $bindinig) {
            /** @var PuliFileResource $resource */
            foreach ($bindinig->getResources()->toArray() as $resource) {
                $validatorFiles[] = $resource->getFilesystemPath();
                // add resources to the container to refresh cache after updating a file
                $container->addResource(new FileResource($resource->getFilesystemPath()));
            }
        }
        $validatorBuilder->addMethodCall('addXmlMappings', [$validatorFiles]);
    }
}
