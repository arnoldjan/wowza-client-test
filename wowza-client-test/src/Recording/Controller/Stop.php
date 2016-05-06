<?php

namespace Mi\WowzaGuzzleTest\Recording\Controller;

use Mi\Bundle\WowzaGuzzleClientBundle\Handler\RecordingHandler;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @author Jan Arnold <jan.arnold@movingimage.com>
 *
 * @codeCoverageIgnore
 */
class Stop
{
    private $recordingHandler;

    /**
     * StartDvr constructor.
     *
     * @param RecordingHandler $recordingHandler
     */
    public function __construct(RecordingHandler $recordingHandler)
    {
        $this->recordingHandler = $recordingHandler;
    }

    /**
     * @param string $streamName
     *
     * @return JsonResponse
     */
    public function __invoke($streamName)
    {
        return $this->recordingHandler->stopRecording($streamName);
    }
}
