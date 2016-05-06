<?php

namespace Mi\WebcastManager\Bundle\MainBundle\Tests\Behat;

use Behat\Gherkin\Node\PyStringNode;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

/**
 * @author Jan Arnold <jan.arnold@movingimage.com>
 */
class WowzaContext extends DefaultContext
{
    /**
     * @var String[]
     */
    private $clients;


    /**
     * @BeforeScenario
     */
    public function initMockPlugin()
    {
        $this->clients = [];
    }

    /**
     * @param PyStringNode $content
     *
     * @Given the wowza client send a response with:
     */
    public function theVMClientSendAResponseWith(PyStringNode $content)
    {
        $this->getClient()->enableProfiler();
        //set to false to avoid kernel shutdown which resets the kernel and so the client with mock plugin
        $hasPerformedRequest = new \ReflectionProperty($this->getClient(), 'hasPerformedRequest');
        $hasPerformedRequest->setAccessible(true);
        $hasPerformedRequest->setValue($this->getClient(), false);
        $this->getSession()->getDriver()->reset();

        /** @var Client $guzzleClient */
        $guzzleClient = $this->getClient()->getContainer()->get('guzzle_client_wowza');

        $mock = new MockHandler(
            [
                new Response(200, ['X-Foo' => 'Bar'], $content)
            ]
        );

        /** @var HandlerStack $handlerStack */
        $handlerStack = $guzzleClient->getConfig('handler');
        $handlerStack->setHandler($mock);
    }
}
