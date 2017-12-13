<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LoginTest extends DuskTestCase
{
    /**
     * Skenario untuk login.
     *
     * @group login
     * @return void
     */
    public function testLogin($email, $password)
    {
        $this->browse(function (Browser $browser) use ($email, $password) {
            $browser->maximize()
                    ->visit('/login')
                    ->assertSee('Login Now')
                    ->type('email', $email)
                    ->type('password', $password)
                    ->click('#btnlogin')
                    ->waitForText('Price and Plan')
                    ->assertSee('Price and Plan');
        });
    }

    /**
     * Skenario untuk login dengan invalid email
     *
     * @group loginInvalidEmail
     * @return void
     */
    public function testLoginInvalidEmail()
    {
        $this->browse(function (Browser $browser) {
            $browser->maximize()
                    ->visit('/login')
                    ->assertSee('Login Now')
                    ->type('email', 'jackmerijack@mailinator.com')
                    ->type('password', '123456')
                    ->click('#btnlogin')
                    ->assertSee('Your email and password combination does not match');
        });
    }

    /**
     * Skenario untuk login dengan invalid password
     *
     * @group loginInvalidPassword
     * @return void
     */
    public function testLoginInvalidPassword()
    {
        $this->browse(function (Browser $browser) {
            $browser->maximize()
                    ->visit('/login')
                    ->assertSee('Login Now')
                    ->type('email', 'johnsnow@mailinator.com')
                    ->type('password', '12345')
                    ->click('#btnlogin')
                    ->pause(3000)
                    ->assertSee('Your email and password combination does not match');
        });
    }

    /**
     * Skenario untuk lupa password
     *
     * @group forgetPassword
     * @return void
     */
    public function testForgetPassword()
    {
        $this->browse(function (Browser $browser) {
            $browser->maximize()
                    ->visit('/forgot-password')
                    ->assertSee('Forgot your password?')
                    ->type('email', 'johnsnow@mailinator.com')
                    ->click('#btnlogin')
                    ->waitForText('Password reset link Sent');
        });
    }

    /**
     * Skenario buka link lupa password lewat email
     *
     * @group linkForgetPassword
     * @return void
     */
    public function testLinkForgetPassword()
    {
        $this->browse(function (Browser $browse) {
            $response = $browser->maximize()
                                ->visit('http://mailinator.com')
                                ->assertSee('Mailinator')
                                ->type('input[type=text]', 'johnsnow@mailinator.com')
                                ->click('button.btn.btn-dark')
                                ->waitForText('Reset Password')
                                ->click('ul.single_mail-body > li.all_message-item:first-child > div > div.all_message-min_text.all_message-min_text-3')
                                ->waitForText('Password Reset Confirmation')
                                ->switchFrame('msg_body')
                                ->clickLink('Reset now');
            // Stay di tab reset password
            $window = collect($response->driver->getWindowHandles())->last();
            $response->driver->switchTo->window($window);
            $response->waitForText('Please enter your new password.')
                     ->type('password', '123456')
                     ->type('password2', '123456')
                     ->click('#btnlogin');
            /**
             * Note :
             * Seharusnya setelah ini tampil halaman reset password sukses,
             * sedangkan sekarang yg tampil malah halaman link reset password terkirim
             *
             */
            $response->assertSee('Success');
        });
    }

    /**
     * Skenario logout
     *
     * @group logout
     * @return void
     */
    public function testLogOut()
    {
        // Do login
        $this->testLogin('jackbizzy6@mailinator.com', '123456');

        $this->browse(function (Browser $browse) {
            $browse->click('#dropdown10')
                    ->assertSee('Logout')
                    ->click('#navbarCollapse > ul > li > div > a:nth-child(3)')
                    ->waitForText('Login');
        });
    }
}
