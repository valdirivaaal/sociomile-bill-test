<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RegisterTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testRegister()
    {
        $this->browse(function (Browser $browser) {

            # Bagian mengisi field form pendaftaran
            // $browser->maximize()
            //         ->visit('/register')
            //         ->assertSee('REGISTER')
            //         ->type('name', 'John Shy')
            //         ->type('email', 'johnshy@mailinator.com')
            //         ->type('company', 'John S')
            //         ->type('phone', '021525235401')
            //         ->type('password', '123456')
            //         ->type('password2', '123456')
            //         ->check('input[type=checkbox]')
            //         ->click('REGISTER')
            //         ->waitFor('ACTIVATE', 10);

            # Bagian aktivasi akun
            $browser->visit('http://mailinator.com')
                    ->assertSee('Mailinator')
                    ->type('input[type=text]', 'johnsnow@mailinator.com')
                    ->click('button.btn.btn-dark')
                    ->waitForText('Verify Account')
                    ->click('');
        });
    }
}
