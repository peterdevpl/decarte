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
}
