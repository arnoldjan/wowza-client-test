<?php

namespace Mi\WebcastManager\Bundle\MainBundle\Tests\Behat;

use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Mink\Exception\ExpectationException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * @author Alexander Miehe <alexander.miehe@movingimage.com>
 */
class RestContext extends DefaultContext
{
    /**
     * Add an header element in a request
     *
     * @param string $name
     * @param string $value
     *
     * @Then I add :name client header equal to :value
     */
    public function iAddClientHeaderEqualTo($name, $value)
    {
        $this->getSession()->getDriver()->getClient()->setServerParameter($name, $value);
    }

    /**
     * @param string $propertyPath
     *
     * @Then the response should have a violation for :propertyPath
     */
    public function theResponseShouldHaveAViolationFor($propertyPath)
    {
        $json = $this->getJson();

        \PHPUnit_Framework_Assert::assertInternalType('array', $json, 'No violations are found.');

        foreach ($json as $violation) {
            if ($violation->propertyPath === $propertyPath) {
                return;
            }
        }
        throw new ExpectationException(
            sprintf('No violation for propertyPath "%s" found.', $propertyPath),
            $this->getSession()
        );
    }

    /**
     * @Then the response should have a violation for :propertyPath with :message
     */
    public function theResponseShouldHaveAViolationForWith($propertyPath, $message)
    {
        $this->theResponseShouldHaveAViolationFor($propertyPath);

        $json = $this->getJson();

        $currentViolation = new \stdClass();

        foreach ($json as $violation) {
            if ($violation->propertyPath === $propertyPath) {
                $currentViolation = $violation;
                break;
            }
        }

        \PHPUnit_Framework_Assert::assertEquals($message, $currentViolation->message);
    }

    /**
     * @Then the header :name should equal the regex :regex
     */
    public function theHeaderShouldEqualTheRegex($name, $regex)
    {
        \PHPUnit_Framework_Assert::assertRegExp($regex, $this->getHttpHeader($name));
    }

    /**
     * @Then the JSON should be valid according to the json schema :filename
     */
    public function theJsonShouldBeValidAccordingToTheSchema($filename)
    {
        if (false === is_file($filename)) {
            throw new \RuntimeException(
                'The JSON schema doesn\'t exist'
            );
        }

        $this->getJson($filename);
    }

    /**
     * @Given I save the value of JSON node :node as placeholder :placeholder
     */
    public function iSaveTheValueOfJsonNodeAsPlaceholder($node, $placeholder)
    {
        $this->setPlaceholder(
            $placeholder,
            $this->evaluateJson(
                $this->getJson(),
                $node
            )
        );
    }

    /**
     * Sends a HTTP request
     *
     * @Given I send a :method request to :url with placeholder
     */
    public function iSendARequestToWithPlaceholder($method, $url)
    {
        return $this->restContext->iSendARequestTo($method, $this->replacePlaceholder($url));
    }


    /**
     * @When I send a :method request to :url with placeholder and body:
     */
    public function iSendARequestToWithPlaceholderAndBody($method, $url, PyStringNode $body)
    {
        $strings = $body->getStrings();

        foreach ($strings as $key => $string) {
            $strings[$key] = $this->replacePlaceholder($string);
        }

        $this->restContext->iSendARequestToWithBody(
            $method,
            $this->replacePlaceholder($url),
            new PyStringNode($strings, $body->getLine())
        );
    }

    /**
     * @param string $url
     * @param TableNode $post
     *
     * @throws \Exception
     *
     * @Given I upload to :url with values:
     */
    public function iUpload($url, TableNode $post)
    {
        $files = [];
        if (null !== $this->getMinkParameter('files_path')) {

            foreach ($post->getHash() as $row) {
                if (!isset($row['key']) || !isset($row['value'])) {
                    throw new \Exception("You must provide a 'key' and 'value' column in your table node.");
                }

                $fullPath = realpath($this->getMinkParameter('files_path')) . DIRECTORY_SEPARATOR . $row['value'];
                if (is_file($fullPath)) {
                    $fileTmp = new File($fullPath);
                    $fileSystem = new Filesystem();
                    $tmpPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $fileTmp->getBasename();
                    $fileSystem->copy($fullPath, $tmpPath);
                    $fileTmp = new File($tmpPath);
                    $file    = new UploadedFile(
                        $fileTmp->getRealPath(),
                        $fileTmp->getBasename(),
                        $fileTmp->getMimeType(),
                        $fileTmp->getSize(),
                        UPLOAD_ERR_OK
                    );
                    $files[$row['key']] = $file;
                }
            }
        }

        $parameters = array();
        foreach ($post->getHash() as $row) {
            if (!array_key_exists('key', $row) || !array_key_exists('value', $row)) {
                throw new \Exception('You must provide a "key" and "value" column in your table node.');
            }
            $parameters[$row['key']] = $this->replacePlaceholder($row['value']);
        }

        $this->getSession()->getDriver()->getClient()->followRedirects(false);
        $this->getSession()->getDriver()->getClient()->request('POST', $this->locatePath($url), $parameters, $files);
        $this->getSession()->getDriver()->getClient()->followRedirects(true);
    }

    /**
     * @param string $url
     * @param TableNode $post
     *
     * @throws \Exception
     *
     * @Given I upload with placeholders to :url with values:
     */
    public function iUploadWithPlaceholders($url, TableNode $post)
    {
        $files = [];
        $url = $this->replacePlaceholder($url);
        if (null !== $this->getMinkParameter('files_path')) {

            foreach ($post->getHash() as $row) {
                if (!isset($row['key']) || !isset($row['value'])) {
                    throw new \Exception("You must provide a 'key' and 'value' column in your table node.");
                }

                $fullPath = realpath($this->getMinkParameter('files_path')) . DIRECTORY_SEPARATOR . $row['value'];
                if (is_file($fullPath)) {
                    $fileTmp = new File($fullPath);
                    $fileSystem = new Filesystem();
                    $tmpPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $fileTmp->getBasename();
                    $fileSystem->copy($fullPath, $tmpPath);
                    $fileTmp = new File($tmpPath);
                    $file    = new UploadedFile(
                        $fileTmp->getRealPath(),
                        $fileTmp->getBasename(),
                        $fileTmp->getMimeType(),
                        $fileTmp->getSize(),
                        UPLOAD_ERR_OK
                    );
                    $files[$row['key']] = $file;
                }
            }
        }

        $parameters = array();
        foreach ($post->getHash() as $row) {
            if (!array_key_exists('key', $row) || !array_key_exists('value', $row)) {
                throw new \Exception('You must provide a "key" and "value" column in your table node.');
            }
            $parameters[$row['key']] = $this->replacePlaceholder($row['value']);
        }

        $this->getSession()->getDriver()->getClient()->followRedirects(false);
        $this->getSession()->getDriver()->getClient()->request('POST', $this->locatePath($url), $parameters, $files);
        $this->getSession()->getDriver()->getClient()->followRedirects(true);
    }


    /**
     * @param string $name
     *
     * @return string
     */
    private function getHttpHeader($name)
    {
        $name = strtolower($name);
        $header = $this->getHttpHeaders();

        if (array_key_exists($name, $header)) {
            if (is_array($header[$name])) {
                $value = implode(', ', $header[$name]);
            }
            else {
                $value = $header[$name];
            }
        }
        else {
            throw new \OutOfBoundsException(
                sprintf('The header "%s" doesn\'t exist', $name)
            );
        }
        return $value;
    }

    /**
     * @return array
     */
    private function getHttpHeaders()
    {
        return array_change_key_case(
            $this->getSession()->getResponseHeaders(),
            CASE_LOWER
        );
    }

    /**
     * @param \stdClass $json
     * @param string    $expression
     *
     * @return mixed
     * @throws \Exception
     */
    private function evaluateJson($json, $expression)
    {

        if (preg_match('/^(?:root)(.*)/', $expression, $matches)) {
            $accessor = PropertyAccess::createPropertyAccessor();
            if (strpos($matches[1], '.') === 0) {
                $matches[1] = substr($matches[1], 1);
            }

            return $accessor->getValue($json, $matches[1]);
        }
        throw new \Exception(sprintf('Failed to evaluate expression "%s".', $expression));
    }
}
