<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Browser\LoginTest;

class ProfileTest extends DuskTestCase
{
    /**
     * Skenario untuk edit halaman profile.
     *
     * @group login
     * @group profile
     * @return void
     */
    public function testProfile()
    {
        // Do login
        $login = new LoginTest;
        // $login->testLogin();

        $this->browse(function (Browser $browser) {
            $browser->click('#v-pills-profile-tab')
                    ->waitForText('Update Profile')
                    ->attach('profiles', __DIR__.'/usop.jpg')
                    ->type('name', 'Jack Bizzy 8')
                    ->type('div > input[type=email]', config('testing.email'))
                    ->type('phone', '0217894762')
                    ->type('company', 'Jack B Corp')
                    ->type('address', 'Kramat Street')
                    ->type('city', 'Seattle')
                    ->type('country', 'USA')
                    ->select('timezone', 'Asia/Pontianak')
                    ->type('zipcode', '13810');
            $browser->element('#form > div.text-center > button')->getLocationOnScreenOnceScrolledIntoView();
            $browser->click('#form > div.text-center > button')
                    ->waitForText('Your Profile has been updated!');
        });

        // Do logout
        $login->testLogOut();
    }
}
