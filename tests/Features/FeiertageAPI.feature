Feature: Talking to the "Feiertage API"
  In order to use the workday API,
  the workday API must access the "Feiertage API".

  Scenario: Requesting Feiertage from "Feiertage API"
    Given calling the url "https://feiertage-api.de/api/" is possible.
    When I request "https://feiertage-api.de/api/?jahr=2018&nur_land=National"
    Then I should receive a json response
