<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Browser\LoginTest;

class PricingTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @group bronzeTrial
     * @return void
     */
    public function testBronzeTrial()
    {
        // Do login
        $login = new LoginTest;
        $login->testLogin('jackbizzy6@mailinator.com', '123456');

        $this->browse(function (Browser $browser) {
            $browser->waitFor('button#trial-1.btn.btn-light.border-primary')
                    ->waitFor('div.choose-plan')
                    ->click('#trial-1')
                    ->waitForText('Success')
                    ->click('button.swal2-confirm.swal2-styled')
                    ->waitFor('div.choose-plan.padding-none.blue')
                    ->waitFor('a.btn.btn-light.block');
        });
    }

    /**
     * Skenario memilih plan anually, yg dicek adalah value table yg lebih murah
     *
     * @group planAnnually
     * @return void
     */
    public function testPlanAnnually()
    {
        // Do login
        $login = new LoginTest;
        $login->testLogin('jackbizzy9@mailinator.com', '123456');

        $this->browse(function (Browser $browser) {
            $browser->click('div.slider.round')
                    ->waitForText('$19')
                    ->waitForText('$29')
                    ->waitForText('$39');
        });
    }

    /**
     * Skenario purchase bronze package dengan plan annually
     * Kondisinya adalah sebelumnya user sudah click trial 7 hari
     *
     * @group purchaseBronze
     * @return void
     */
    public function testPurchaseBronze()
    {
        // Do lgin
        $login = new LoginTest;
        $login->testLogin('jackbizzy7@mailinator.com', '123456');

        $this->browse(function (Browser $browser) {
            $browser->click('div.choose-plan.padding-none.blue > div.plan-footer > a.btn.btn-primary')
                    ->waitForText('Bronze')
                    ->assertSee('Annually')
                    ->assertSeeIn('span#package-price', '$180')
                    ->assertSeeIn('span#total-price', '$180')
                    ->click('button.btn.btn-primary.btn-block')
                    ->assertSee('Card Number')
                    ->type('number', '4000000000000002')
                    ->type('first-name', 'Jack')
                    ->type('last-name', 'Bizzy')
                    ->type('expiry', '112025')
                    ->type('cvc', '123')
                    ->click('button#load')
                    ->waitFor('div.swal2-modal')
                    ->assertSee('Package: Bronze Package')
                    ->click('button.swal2-confirm')
                    ->pause(5000)
                    ->waitFor('iframe[id=sample-inline-frame]')
                    ->switchFrame('sample-inline-frame')
                    ->pause(15000);
                    $browser->driver->switchTo()->frame('authWindow');
                    $browser->assertSee('Merchant: Xendit');
        });
    }
}
