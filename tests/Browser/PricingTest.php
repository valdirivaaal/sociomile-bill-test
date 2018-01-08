<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Browser\LoginTest;
use Tests\Browser\Pages\PriceAndPlan;


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
        $login->testLogin();

        $this->browse(function (Browser $browser) {
            $browser->on(new PriceAndPlan)
                    ->driver->executeScript('window.scrollTo(0, 500)');
            $browser->waitFor('@btnTrialBronze')
                    ->click('@btnTrialBronze')
                    ->whenAvailable('.swal2-modal', function($modal) {
                        $modal->waitForText('Success')
                              ->click('button.swal2-confirm.swal2-styled');
                      })
                    ->waitUntilMissing('.swal2-modal')
                    ->waitForText('You have selected 7 days') // Teks paket terpilih
                    ->assert('@divBronzeSelected') // Div paket terpilih berubah menjadi biru
                    ->assert('@divSilverUnselected') // Div paket yg tidak terpilih menjadi abu
                    ->assert('@divGoldUnselected') // Div paket yg tidak terpilih menjadi abu
                    ->assert('@btnTrialBronzeBlocked') // Button bronze blocked
                    ->assert('@btnTrialSilverDisabled') // Button silver disabled
                    ->assert('@btnTrialGoldDisabled'); // Button gold disabled
        });

        // Logout
        $login->testLogOut();
    }

    /**
     * Skenario untuk user memilih trial silver package.
     *
     * @group silverTrial
     * @return void
     */
    public function testSilverTrial()
    {
        // Do login
        $login = new LoginTest;
        $login->testLogin();

        $this->browse(function (Browser $browser) {
            $browser->on(new PriceAndPlan)
                    ->driver->executeScript('window.scrollTo(0, 500)');
            $browser->waitFor('@btnTrialSilver')
                    ->click('@btnTrialSilver')
                    ->whenAvailable('.swal2-modal', function($modal) {
                        $modal->waitForText('Success')
                              ->click('button.swal2-confirm.swal2-styled');
                      })
                    ->waitUntilMissing('.swal2-modal')
                    ->waitForText('You have selected 7 days') // Teks paket terpilih
                    ->assert('@divBronzeUnselected') // Div paket terpilih berubah menjadi biru
                    ->assert('@divSilverSelected') // Div paket yg tidak terpilih menjadi abu
                    ->assert('@divGoldUnselected') // Div paket yg tidak terpilih menjadi abu
                    ->assert('@btnTrialBronzeDisabled') // Button bronze blocked
                    ->assert('@btnTrialSilverBlocked') // Button silver disabled
                    ->assert('@btnTrialGoldDisabled'); // Button gold disabled
        });

        // Logout
        $login->testLogOut();
    }

    /**
     * Skenario untuk user memilih trial gold package.
     *
     * @group goldTrial
     * @return void
     */
    public function testGoldTrial()
    {
        // Do login
        $login = new LoginTest;
        $login->testLogin();

        $this->browse(function (Browser $browser) {
            $browser->on(new PriceAndPlan)
                    ->driver->executeScript('window.scrollTo(0, 500)');
            $browser->waitFor('@btnTrialGold')
                    ->click('@btnTrialGold')
                    ->whenAvailable('.swal2-modal', function($modal) {
                        $modal->waitForText('Success')
                              ->click('button.swal2-confirm.swal2-styled');
                      })
                    ->waitUntilMissing('.swal2-modal')
                    ->waitForText('You have selected 7 days') // Teks paket terpilih
                    ->assert('@divBronzeUnselected') // Div paket terpilih berubah menjadi biru
                    ->assert('@divSilverUnselected') // Div paket yg tidak terpilih menjadi abu
                    ->assert('@divGoldSelected') // Div paket yg tidak terpilih menjadi abu
                    ->assert('@btnTrialBronzeDisabled') // Button bronze blocked
                    ->assert('@btnTrialSilverDisabled') // Button silver disabled
                    ->assert('@btnTrialGoldBlocked'); // Button gold disabled
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
        $login->testLogin();

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
        $login->testLogin();

        $this->browse(function (Browser $browser) {

            // Beli paket bronze
            $this->purchaseBronze($browser);
        });

        // Logout
        $login->testLogOut();
    }

    /**
     * Skenario purchase silver package dengan plan annually
     * Kondisinya adalah sebelumnya user sudah click trial 7 hari
     *
     * @group purchaseSilver
     * @return void
     */
    public function testPurchaseSilver()
    {
        // Do lgin
        $login = new LoginTest;
        $login->testLogin();

        $this->browse(function (Browser $browser) {

            // Beli paket silver
            $this->purchaseSilver($browser);
        });

        // Logout
        $login->testLogOut();
    }

    /**
     * Skenario purchase gold package dengan plan annually
     * Kondisinya adalah sebelumnya user sudah click trial 7 hari
     *
     * @group purchaseGold
     * @return void
     */
    public function testPurchaseGold()
    {
        // Do lgin
        $login = new LoginTest;
        $login->testLogin();

        $this->browse(function (Browser $browser) {

            // Beli paket gold
            $this->purchaseGold($browser);
        });

        // Logout
        $login->testLogOut();
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
        $login->testLogin();

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

    /**
     * Skenario untuk cek apakah suatu paket sudah aktif atau belum
     *
     * @group checkActivePackage
     * @return void
     */
    public function testCheckActivePackage()
    {
        // Do login
        $login = new LoginTest;
        $login->testLogin();

        $this->browse(function (Browser $browser) {

            $package = "Silver";
            $nthChild = "2";

            $browser->assertSee('You have selected '.$package.' Package');
            $isSilver = $browser->script('return $("#v-pills-subscrip > div > div.card-body > div.body-content > div > div:nth-child('.$nthChild.')").hasClass("blue")');
        });

        // Logout
        $login->testLogOut();
    }

    /**
     * Skenario untuk test payment jika data credit card kosong
     *
     * @group paymentBlankCC
     * @return void
     */
    public function testPaymentBlankCC()
    {
        // Do login
        $login = new LoginTest;
        $login->testLogin();

        $this->browse(function (Browser $browser) {

            $package = "Bronze";
            $nthChild = "1";

            $browser->waitForText('You have selected '.$package.' Package');

            $browser->element('div.choose-plan:nth-child('.$nthChild.') > div.plan-footer > a.btn.btn-primary')->getLocationOnScreenOnceScrolledIntoView();

            $browser->click('div.choose-plan:nth-child('.$nthChild.') > div.plan-footer > a.btn.btn-primary')
                    ->waitForText($package)
                    ->click('button.btn.btn-primary.btn-block')
                    ->waitForText('Card Number')
                    ->click('button#load')
                    ->assertDontSee('Package: '.$package.' Package');
        });

        // Logout
        $login->testLogOut();
    }

    /**
     * Skenario untuk test payment jika data credit card kosong dan sudah terisi kembali
     *
     * @group paymentUpdateCC
     * @return void
     */
    public function testPaymentUpdateCC()
    {
        // Do login
        $login = new LoginTest;
        $login->testLogin();

        $this->browse(function (Browser $browser) {

            $package = "Bronze";
            $nthChild = "1";

            $browser->waitForText('You have selected '.$package.' Package');

            $browser->element('div.choose-plan:nth-child('.$nthChild.') > div.plan-footer > a.btn.btn-primary')->getLocationOnScreenOnceScrolledIntoView();

            $browser->click('div.choose-plan:nth-child('.$nthChild.') > div.plan-footer > a.btn.btn-primary')
                    ->waitForText($package)
                    ->click('button.btn.btn-primary.btn-block')
                    ->waitForText('Card Number')
                    ->click('button#load')
                    ->assertDontSee('Package: '.$package.' Package')
                    ->type('number', '4000000000000002')
                    ->type('first-name', 'Jack')
                    ->type('last-name', 'Bizzy')
                    ->type('expiry', '112025')
                    ->type('cvc', '123')
                    ->click('button#load')
                    ->waitFor('div.swal2-modal')
                    ->assertSee('Package: '.$package.' Package');
        });

        // Logout
        $login->testLogOut();
    }

    /**
     * Skenario untuk test payment dengan mengisi payment method secara tidak lengkap
     *
     * @group paymentIncomplete
     * @return void
     */
    public function testIncompletePaymentMethod()
    {
        // Do login
        $login = new LoginTest;
        $login->testLogin();

        $this->browse(function (Browser $browser) {

            $package = "Bronze";
            $nthChild = "1";

            $browser->waitForText('You have selected '.$package.' Package');

            $browser->element('div.choose-plan:nth-child('.$nthChild.') > div.plan-footer > a.btn.btn-primary')->getLocationOnScreenOnceScrolledIntoView();

            $browser->click('div.choose-plan:nth-child('.$nthChild.') > div.plan-footer > a.btn.btn-primary')
                    ->waitForText($package)
                    ->click('button.btn.btn-primary.btn-block')
                    ->waitForText('Card Number')
                    ->type('number', '4000000000000002')
                    ->type('first-name', 'Jack')
                    ->type('expiry', '112025')
                    ->type('cvc', '123')
                    ->click('button#load')
                    ->assertDontSee('Package: '.$package.' Package')
                    ->click('button#load');
        });

        // Logout
        $login->testLogOut();
    }

    /**
     * Skenario untuk test payment dengan mengisi informasi payment method yang salah
     *
     * @group paymentInvalid
     * @return void
     */
    public function testInvalidPaymentMethod()
    {
        // Do login
        $login = new LoginTest;
        $login->testLogin();

        $this->browse(function (Browser $browser) {

            $package = "Bronze";
            $nthChild = "1";

            $browser->waitForText('You have selected '.$package.' Package');

            $browser->element('div.choose-plan:nth-child('.$nthChild.') > div.plan-footer > a.btn.btn-primary')->getLocationOnScreenOnceScrolledIntoView();

            $browser->click('div.choose-plan:nth-child('.$nthChild.') > div.plan-footer > a.btn.btn-primary')
                    ->waitForText($package)
                    ->click('button.btn.btn-primary.btn-block')
                    ->waitForText('Card Number')
                    ->type('number', '5000000000000002')
                    ->type('first-name', 'Jack')
                    ->type('last-name', 'B')
                    ->type('expiry', '112025')
                    ->type('cvc', '123')
                    ->click('button#load')
                    ->waitFor('div.swal2-modal')
                    ->assertSee('Package: '.$package.' Package')
                    ->click('button.swal2-confirm')
                    ->waitUntilMissing('div.swal2-modal')
                    ->waitFor('div.swal2-modal')
                    ->assertSee('VALIDATION_ERROR')
                    ->assertSee('One or more validation errors occurred')
                    ->click('button.swal2-confirm');
        });

        // Logout
        $login->testLogOut();
    }

    /**
     * Skenario untuk test akun yang sudah expired
     *
     * @group expiredAccount
     * @return void
     */
    public function testExpiredPackage()
    {
        // Do login
        $login = new LoginTest;
        $login->testLogin();

        $this->browse(function (Browser $browser) {
            // Cek kondisi apakah benar halaman expire
            $browser->on(new PriceAndPlan)
                    ->waitForText('Your trial period is end, please select package to continue')
                    ->assert('@btnTrialBronzeDisabled')
                    ->assert('@btnTrialSilverDisabled')
                    ->assert('@btnTrialGoldDisabled');

            // Beli paket bronze
            $this->purchaseBronze($browser);
        });

        // LogOut
        $login->testLogOut();
    }

    /**
     * Method untuk skenario purchase package
     *
     * @param Browser $browser
     * @return void
     */
    public function purchaseBronze($browser)
    {
        $browser->on(new PriceAndPlan)
                ->waitForText('Price and Plan')
                ->driver->executeScript('window.scrollTo(0, 500)');
        $browser->click('@btnPurchaseBronze')
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
                ->pause(10000)
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
                $browser->waitForText('Payment Success!', 20)
                        ->visit('/dashboard#v-pills-billing')
                        ->waitForText('Billing')
                        ->assert('@rowBillingTable');
    }

    /**
     * Method untuk skenario purchase package
     *
     * @param Browser $browser
     * @return void
     */
    public function purchaseSilver($browser)
    {
        $browser->on(new PriceAndPlan)
                ->waitForText('Price and Plan')
                ->driver->executeScript('window.scrollTo(0, 500)');
        $browser->click('@btnPurchaseSilver')
                ->waitForText('Silver')
                ->assertSee('Annually')
                ->assertSeeIn('span#package-price', '$264')
                ->assertSeeIn('span#total-price', '$264')
                ->click('button.btn.btn-primary.btn-block')
                ->assertSee('Card Number')
                ->type('number', '4000000000000002')
                ->type('first-name', 'Jack')
                ->type('last-name', 'Bizzy')
                ->type('expiry', '112025')
                ->type('cvc', '123')
                ->click('button#load')
                ->waitFor('div.swal2-modal')
                ->assertSee('Package: Silver Package')
                ->click('button.swal2-confirm')
                ->pause(10000)
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
                $browser->waitForText('Payment Success!', 20)
                        ->visit('/dashboard#v-pills-billing')
                        ->waitForText('Billing')
                        ->assert('@rowBillingTable');
    }

    /**
     * Method untuk skenario purchase package
     *
     * @param Browser $browser
     * @return void
     */
    public function purchaseGold($browser)
    {
        $browser->on(new PriceAndPlan)
                ->waitForText('Price and Plan')
                ->driver->executeScript('window.scrollTo(0, 500)');
        $browser->click('@btnPurchaseGold')
                ->waitForText('Gold')
                ->assertSee('Annually')
                ->assertSeeIn('span#package-price', '$384')
                ->assertSeeIn('span#total-price', '$384')
                ->click('button.btn.btn-primary.btn-block')
                ->assertSee('Card Number')
                ->type('number', '4000000000000002')
                ->type('first-name', 'Jack')
                ->type('last-name', 'Bizzy')
                ->type('expiry', '112025')
                ->type('cvc', '123')
                ->click('button#load')
                ->waitFor('div.swal2-modal')
                ->assertSee('Package: Gold Package')
                ->click('button.swal2-confirm')
                ->pause(10000)
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
                $browser->waitForText('Payment Success!', 20)
                        ->visit('/dashboard#v-pills-billing')
                        ->waitForText('Billing')
                        ->assert('@rowBillingTable');
    }
}
