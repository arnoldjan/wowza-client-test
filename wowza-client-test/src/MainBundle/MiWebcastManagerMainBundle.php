<?php

namespace Mi\WebcastManager\Bundle\MainBundle;

use Mi\PuliBundlePlugins\PuliBundleWithPlugins;

/**
 * @author Alexander Miehe <alexander.miehe@movingimage.com>
 *
 * @codeCoverageIgnore
 */
class MiWebcastManagerMainBundle extends PuliBundleWithPlugins
{
    protected function getAlias()
    {
        return 'mi_webcast_manager';
    }
}