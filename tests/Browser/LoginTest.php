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
    public function testLogin()
    {
        $this->browse(function (Browser $browser) {
            $browser->maximize()
                    ->visit('/login')
                    ->assertSee('Login Now')
                    ->type('email', 'johnsnow@mailinator.com')
                    ->type('password', '123456')
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
}
