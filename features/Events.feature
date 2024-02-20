Feature: Events

# Steps make us talk about separate application interactions straight away
Scenario: Submitting events from anonymous users
  As an anonmous user
  When I submit an event named "Rammstein in Belgrage" happening on "24.05.2024" at "20:00" in "Usce park"
  And the event named "Rammstein in Belgrage" happening on "24.05.2024" at "20:00" in "Usce park" is approved by an admin
  Then I see the event named "Rammstein in Belgrage" happening on "24.05.2024" at "20:00" in "Usce park" in the event list
