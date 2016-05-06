<?php

namespace Mi\WowzaGuzzleTest\Cuepoint\Controller;

use Mi\Bundle\WowzaGuzzleClientBundle\Handler\CuepointHandler;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @author Jan Arnold <jan.arnold@movingimage.com>
 *
 * @codeCoverageIgnore
 */
class Set
{
    private $cuepointHandler;

    /**
     * StartDvr constructor.
     *
     * @param CuepointHandler $cuepointHandler
     */
    public function __construct(CuepointHandler $cuepointHandler)
    {
        $this->cuepointHandler = $cuepointHandler;
    }

    /**
     * @param string $streamName
     * @param string $text
     *
     * @return JsonResponse
     */
    public function __invoke($streamName, $text = '')
    {
        return $this->cuepointHandler->insertCuepoint($streamName, $text);
    }
}
