<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Browser\LoginTest;

class PricingTest extends DuskTestCase
{
    /**
     * Skenario untuk user memilih trial bronze package.
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

        // Logout
        $login->testLogOut();
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
        $login->testLogin('jackbizzy8@mailinator.com', '123456');

        $this->browse(function (Browser $browser) {

            // Scroll ke button purchase
            $browser->element('div.choose-plan.padding-none.blue > div.plan-footer > a.btn.btn-primary')->getLocationOnScreenOnceScrolledIntoView();

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
                    ->waitFor('iframe[id=sample-inline-frame]');

                    // Switch ke frame parent
                    $browser->switchFrame('sample-inline-frame');

                    // Switch ke parent child
                    $browser->driver->switchTo()->frame('authWindow');
                    $browser->assertSee('Merchant: Xendit')
                            ->type('external.field.password', '1234')
                            ->press('Submit')
                            ->waitUntilMissing('authWindow')
                            ->waitUntilMissing('sample-inline-frame');
                    $browser->driver->switchTo()->defaultContent();
                    $browser->waitForText('Payment Success!', 15);

                // Logout
                $login->testLogOut();
        });
    }

    /**
     * Skenario untuk user melihat invoice bulanan
     *
     * @group invoiceBulanan
     * @return void
     */
    public function testInvoiceBulanan()
    {
        // Do login
        $login = new LoginTest;
        $login->testLogin('jackbizzy8@mailinator.com', '123456');

        $this->browse(function (Browser $browser) {
            $browser->click('#v-pills-billing-tab')
                    ->waitForText('View Invoice')
                    ->click('#v-pills-billing > div > div.card-body > table > tbody > tr > td:nth-child(7) > a');

            // Stay di tab invoice
            $tabInvoice = collect($browser->driver->getWindowHandles())->last();
            $browser->driver->switchTo()->window($tabInvoice);
            $browser->waitForText('INVOICE')
                    ->click('#HTMLtoPdfInvoice > div > p > i.fa.fa-file-pdf-o.fa-2x')
                    ->pause(1000);

            //Stay di tab download pdf
            // $tabDownload = collect($browser->driver->getWindowHandles())->last();
            // $browser->assertPathIs('/me/pdf-invoice');
        });


    }
}
