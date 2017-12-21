<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page as BasePage;

class PriceAndPlan extends BasePage
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/dashboard';
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param  Browser  $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertPathIs($this->url());
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            # Button Trial Aktif
            '@btnTrialBronze' => '#trial-1',
            '@btnTrialSilver' => '#trial-2',
            '@btnTrialGold' => '#trial-3',

            # Button trial disabled
            '@btnTrialBronzeDisabled' => 'div.body-content > div > div:nth-child(1) > div.plan-footer > a.btn.btn-light.disabled',
            '@btnTrialSilverDisabled' => 'div.body-content > div > div:nth-child(2) > div.plan-footer > a.btn.btn-light.disabled',
            '@btnTrialGoldDisabled' => 'div.body-content > div > div:nth-child(3) > div.plan-footer > a.btn.btn-light.disabled',

            # Button trial blocked
            '@btnTrialBronzeBlocked' => 'div.body-content > div > div:nth-child(1) > div.plan-footer > a.btn.btn-light.disabled',
            '@btnTrialSilverBlocked' => 'div.body-content > div > div:nth-child(2) > div.plan-footer > a.btn.btn-light.disabled',
            '@btnTrialGoldBlocked' => 'div.body-content > div > div:nth-child(3) > div.plan-footer > a.btn.btn-light.disabled',

            # Button purchase aktif
            '@btnPurchaseBronze' => 'div.choose-plan:nth-child(1) > div.plan-footer > a.btn.btn-primary',
            '@btnPurchaseSilver' => 'div.choose-plan:nth-child(2) > div.plan-footer > a.btn.btn-primary',
            '@btnPurchaseGold' => 'div.choose-plan:nth-child(3) > div.plan-footer > a.btn.btn-primary',

            # Div paket terpilih
            '@divBronzeSelected' => 'div.body-content > div > div.choose-plan:nth-child(1).blue',
            '@divSilverSelected' => 'div.body-content > div > div.choose-plan:nth-child(2).blue',
            '@divGoldSelected' => 'div.body-content > div > div.choose-plan:nth-child(3).blue',

            # Div paket tidak terpilih
            '@divBronzeUnselected' => 'div.body-content > div > div.choose-plan:nth-child(1).grey',
            '@divSilverUnselected' => 'div.body-content > div > div.choose-plan:nth-child(2).grey',
            '@divGoldUnselected' => 'div.body-content > div > div.choose-plan:nth-child(3).grey',
        ];
    }
}
