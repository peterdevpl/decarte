Feature:
  In order to buy a product
  As a customer
  I want to browse the products catalog

  Scenario: The home page shows basic informations
    When I am on the homepage
    Then I should see the company logo

  Scenario: Browsing collections of products
    When I open "zaproszenia-slubne" products
    Then I should see 1 collection
    And that collection's name should be "Mystic Moments"
