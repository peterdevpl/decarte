<?php

declare(strict_types=1);

namespace Decarte\Shop\Behat\Context;

use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\MinkContext;

final class FeatureContext extends MinkContext implements Context
{
    /**
     * @Then I should see the company logo
     */
    public function iShouldSeeTheCompanyLogo()
    {
        $this->assertElementOnPage('header .site-logo a img');
    }

    /**
     * @When I open :type products
     */
    public function iOpenProductsType(string $type): void
    {
        $this->visit('/sklep/' . $type);
    }

    /**
     * @Then I should see :count collection(s)
     */
    public function iShouldSeeCollections(int $count): void
    {
        $this->assertNumElements($count, '#collections li');
    }

    /**
     * @Then that collection's name should be :name
     */
    public function collectionNameShouldBe(string $name): void
    {
        $this->assertElementContainsText('#collections li:first-child figure figcaption h2', $name);
    }
}
