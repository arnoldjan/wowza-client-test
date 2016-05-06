@cuepoint
Feature: Start DVR Recording

  @cuepoint-set
  Scenario: Start an MP4 Recording

    Given the wowza client send a response with:
    """
    foobar
    """
    Given I add "CONTENT_TYPE" header equal to "application/json"
    And I add "HTTP_ACCEPT" header equal to "application/json"
    When I send a POST request to "/api/stream/bar/cuepoint/set/foobar"
    Then the response status code should be 200
    And the JSON node message should be equal to foobar
    Then the JSON should be valid according to this schema:
    """
    {
      "code": 200,
      "message":  "baz"
    }
    """