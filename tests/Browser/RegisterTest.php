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
     * @group registerTrue
     * @group registration
     * @return void
     */
    public function testRegister()
    {
        $this->browse(function (Browser $browser) {

            # Bagian mengisi field form pendaftaran
            $browser->maximize()
                    ->visit('/register')
                    ->assertSee('REGISTER')
                    ->type('email', config('testing.email'))
                    ->type('password', config('testing.password'))
                    ->type('password2', '123456')
                    ->check('input[type=checkbox]');
            $browser->element('#btnStarted')->getLocationOnScreenOnceScrolledIntoView();
            $browser->click('#btnStarted')
                    ->waitForText('ACTIVATE', 10);
        });
    }

    /**
     * Skenario untuk melakukan pendaftaran dengan mengisi semua form (Positif)
     *
     * @group registerUncomplete
     * @return void
     */
    public function testRegisterUncomplete()
    {
        $this->browse(function (Browser $browser) {

            # Bagian mengisi field form pendaftaran
            $browser->maximize()
                    ->visit('/register')
                    ->assertSee('REGISTER')
                    ->type('email', config('testing.email'))
                    ->type('password', config('testing.password'))
                    ->check('input[type=checkbox]');
            $browser->element('#btnStarted')->getLocationOnScreenOnceScrolledIntoView();
            $browser->click('#btnStarted')
                    ->waitForText('ACTIVATE', 10);
        });
    }

    /**
     * Skenario untuk verifikasi email (Positif)
     *
     * @group verifyTrue
     * @group registration
     * @return void
     */
    public function testVerificationEmail()
    {
        $this->browse(function (Browser $browser) {
            # Bagian aktivasi akun
            $response = $browser->visit('http://mailinator.com')
                                ->assertSee('Mailinator')
                                ->type('input[type=text]', config('testing.email'))
                                ->click('button.btn.btn-dark')
                                ->waitForText('Verify Account')
                                ->click('ul.single_mail-body > li.all_message-item:first-child > div > div.all_message-min_text.all_message-min_text-3')
                                ->waitForText('Verify Account')
                                ->switchFrame('msg_body')
                                ->clickLink('Verify now');

            // Agar stay di tab / halaman status verifikasi
            $window = collect($response->driver->getWindowHandles())->last();
            $response->driver->switchTo()->window($window);
            $response->waitForText('Verification Success!');
        });
    }

    /**
     * Skenario untuk verifikasi email (Wrong Validation Key)
     *
     * @group verifyWrongKey
     * @return void
     */
    public function testVerificationEmailWrongKey()
    {
        $this->browse(function (Browser $browser) {
            # Bagian aktivasi akun
            $response = $browser->visit('http://mailinator.com')
                                ->assertSee('Mailinator')
                                ->type('input[type=text]', config('testing.email'))
                                ->click('button.btn.btn-dark')
                                ->waitForText('Verify Account')
                                ->click('ul.single_mail-body > li.all_message-item:first-child > div > div.all_message-min_text.all_message-min_text-3')
                                ->waitForText('Verify Account')
                                ->switchFrame('msg_body');

            // Ambil kode verifikasi
            $code = $browser->text('div.item > p > small > code:nth-child(3)');

            // Lanjut ke halaman verifikasi
            $browser->visit('https://billing-sm.s45.in/verification?code='.$code.'k')
                    ->assertSee('Wrong verification code');
        });
    }

    /**
     * Skenario registrasi dan verifikasi melalui form verifikasi dengan input kode verifikasi dari email
     *
     *
     * @group fullRegistration
     */
    public function testRegisterWithEmailVerification()
    {
        $this->browse(function ($first, $second) {
            /* Registrasi */
            $first->visit('/register')
                    ->assertSee('REGISTER')
                    ->type('email', config('testing.email'))
                    ->type('password', config('testing.password'))
                    ->type('password2', config('testing.password'))
                    ->check('input[type=checkbox]');
            $first->element('#btnStarted')->getLocationOnScreenOnceScrolledIntoView();
            $first->click('#btnStarted')
                    ->waitForText('ACTIVATE', 10);

            /* Verifikasi email */
            $second->visit(config('testing.mail'))
                    ->assertSee('Mailinator')
                    ->type('input[type=text]', config('testing.email'))
                    ->click('button.btn.btn-dark')
                    ->waitForText('Verify Account')
                    ->click('ul.single_mail-body > li.all_message-item:first-child > div > div.all_message-min_text.all_message-min_text-3')
                    ->waitForText('Verify Account')
                    ->switchFrame('msg_body');

                    // Ambil kode verifikasi
                    $code = $second->text('div.item > p > small > code:nth-child(3)');

            /* Mengisi kode verifikasi */
            $first->click('#ver-mail-link')
                    ->waitForText('Input Your Verification Code')
                    ->pause(5000)
                    ->type('#ver-f > div > div.form-group.mb-42 > input', $code)
                    ->click('#btnStarted2')
                    ->waitForText('Verification Success!')
                    ->click('#btnStarted')
                    ->waitForText('Login Now');
        });
    }
}
