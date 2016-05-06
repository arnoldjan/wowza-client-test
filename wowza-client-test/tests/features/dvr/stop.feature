@dvr
Feature: Stop DVR Recording

  @dvr-stop
  Scenario: Stop a DVR Recording

    Given the wowza client send a response with:
    """
    foo
    """
    Given I add "CONTENT_TYPE" header equal to "application/json"
    And I add "HTTP_ACCEPT" header equal to "application/json"
    When I send a POST request to "/api/dvr/stop/foo"
    Then the response status code should be 200
    And the JSON node message.recordingName should be equal to foo
    And the JSON node message.action should be equal to stop_dvr
    Then the JSON should be valid according to this schema:
    """
    {
      "code": 200,
      "message": {
        "action": "stop_dvr",
        "recordingName": "baz"
      }
    }
    """

  @dvr-stop
  Scenario: Stop a DVR Recording with not existing Streamname

    Given the wowza client send a response with:
    """
    Live stream foo does not exist
    """
    Given I add "CONTENT_TYPE" header equal to "application/json"
    And I add "HTTP_ACCEPT" header equal to "application/json"
    When I send a POST request to "/api/dvr/stop/foo"
    Then the response status code should be 404
    And print last JSON response
    And the JSON node message should be equal to "foo does not exist"
    Then the JSON should be valid according to this schema:
    """
    {
      "code": 200,
      "message": "foo"
    }
    """