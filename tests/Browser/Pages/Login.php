<?php

namespace Tests\Browser\Pages;

use App\Models\User;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Page as BasePage;

class Login extends BasePage
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/login';
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
            '@smEmail' => '#f > div.group-input > div:nth-child(1) > input',
            '@smPassword' => '#f > div.group-input > div:nth-child(3) > input',
            '@smCheckRemember' => '#f > label > input',
            '@btnLogin' => '#btnlogin',
        ];
    }
}
