<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RegisterTest extends DuskTestCase
{
    /**
     * Skenario untuk melakukan pendaftaran dengan mengisi semua form (Positif)
     *
     * @return void
     */
    public function testRegister()
    {
        $this->browse(function (Browser $browser) {

            # Bagian mengisi field form pendaftaran
            $browser->maximize()
                    ->visit('/register')
                    ->assertSee('REGISTER')
                    ->type('name', 'Jack Bizzy')
                    ->type('email', 'jackbizzy2@mailinator.com')
                    ->type('company', 'Jack B')
                    ->type('phone', '021525235401')
                    ->type('password', '123456')
                    ->type('password2', '123456')
                    ->check('input[type=checkbox]');
            $browser->element('#btnStarted')->getLocationOnScreenOnceScrolledIntoView();
            $browser->click('#btnStarted')
                    ->waitForText('ACTIVATE', 10);
        });
    }

    /**
     * Skenario untuk verifikasi email (Positif)
     *
     * @group one
     * @return void
     */
    public function testVerificationEmail()
    {
        $this->browse(function (Browser $browser) {
            # Bagian aktivasi akun
            $response = $browser->visit('http://mailinator.com')
                                ->assertSee('Mailinator')
                                ->type('input[type=text]', 'jackbizzy2@mailinator.com')
                                ->click('button.btn.btn-dark')
                                ->waitForText('Verify Account')
                                ->click('ul.single_mail-body > li.all_message-item:first-child > div > div.all_message-min_text.all_message-min_text-3')
                                ->waitForText('Verify Account')
                                ->switchFrame('msg_body')
                                ->clickLink('Verify now');
            // Agar stay di tab / halaman status verifikasi
            $window = collect($response->driver->getWindowHandles())->last();
            $response->driver->switchTo()->window($window);
            $response->waitForText('Warning');
        });
    }
}
