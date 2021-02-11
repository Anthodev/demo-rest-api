# This file contains a user story for demonstration only.
# Learn how to get started with Behat and BDD on Behat's website:
# http://behat.org/en/latest/quick_start.html

Feature: List of users

    Scenario: I want a list of users
        Given I am an unauthenticated user
        When I request a list of users from "/api/user"
        Then The response should be in JSON
        And The header "Content-Type" should be equal to "application/json"
