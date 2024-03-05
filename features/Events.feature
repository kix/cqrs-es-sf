
Feature: Events
  Background:
    Given there is an event calendar
  # Steps make us talk about separate application interactions straight away
  Scenario: Submitting events from anonymous users
    Given I am an anonymous user
    When I submit an event named "Rammstein in Belgrade" happening on "24.05.2024 20:00" ending on "24.05.2024 23:00" in "Usce park"
    Then I do not see any events in the event list
    When the event named "Rammstein in Belgrade" happening on "24.05.2024 20:00" ending on "24.05.2024 23:00" in "Usce park" is approved by an admin
    Then I see the event named "Rammstein in Belgrade" happening on "24.05.2024 20:00" in "Usce park" in the event list

  Scenario: Approving several events in one place is not allowed
    Given I am an anonymous user
    When I submit an event named "Rammstein in Belgrade" happening on "24.05.2024 20:00" ending on "24.05.2024 23:00" in "Usce park"
    And I submit an event named "Kids stuff" happening on "24.05.2024 19:00" ending on "24.05.2024 21:00" in "Usce park"
    When the event named "Rammstein in Belgrade" happening on "24.05.2024 20:00" ending on "24.05.2024 23:00" in "Usce park" is approved by an admin
    And the event named "Kids stuff" happening on "24.05.2024 19:00" ending on "24.05.2024 21:00" in "Usce park" is approved by an admin
    Then I should get an invariant violation
#  Scenario: Approving events as an administrator
#
#  Scenario: Submitting events as an administrator
#    Given I am an administrator
#    When I submit an event named "Rammstein in Belgrage" happening on "24.05.2024 20:00" ending on "24.05.2024 23:00" in "Usce park"
#
#
#  Scenario: Putting tickets up for sale as a seller
