<?php

namespace Mi\WowzaGuzzleTest\Dvr\Controller;

use Mi\Bundle\WowzaGuzzleClientBundle\Handler\DvrHandler;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @author Jan Arnold <jan.arnold@movingimage.com>
 *
 * @codeCoverageIgnore
 */
class Start
{
    private $dvrHandler;

    /**
     * StartDvr constructor.
     *
     * @param DvrHandler $dvrHandler
     */
    public function __construct(DvrHandler $dvrHandler)
    {
        $this->dvrHandler = $dvrHandler;
    }

    /**
     * @param string $streamName
     *
     * @return JsonResponse
     */
    public function __invoke($streamName)
    {
        return $this->dvrHandler->startDvr($streamName, $streamName . 'Dvr');
    }
}
