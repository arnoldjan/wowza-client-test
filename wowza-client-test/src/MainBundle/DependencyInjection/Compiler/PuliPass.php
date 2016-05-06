<?php

namespace Mi\WebcastManager\Bundle\MainBundle\DependencyInjection\Compiler;

use Puli\Discovery\Api\Discovery;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * @author Alexander Miehe <alexander.miehe@movingimage.com>
 */
abstract class PuliPass implements CompilerPassInterface
{
    /**
     * @var Discovery
     */
    protected $discovery;

    /**
     *
     * @param Discovery $discovery
     */
    public function __construct($discovery)
    {
        $this->discovery = $discovery;
    }
}
