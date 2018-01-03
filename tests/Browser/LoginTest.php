<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Browser\Pages\Login;
use App\User;

class LoginTest extends DuskTestCase
{
    /**
     * Skenario untuk login.
     *
     * @group login
     * @test
     */
    public function testLogin()
    {
        $this->browse(function (Browser $browser) {
            $browser->maximize()
                    ->visit('/login')
                    ->assertSee('Login Now')
                    ->type('email', config('testing.email'))
                    ->type('password', config('testing.password'))
                    ->click('#btnlogin')
                    ->waitForText('Price and Plan')
                    ->assertSee('Price and Plan');
        });
    }

    /**
     * Skenario untuk login dengan invalid email
     *
     * @group loginInvalidEmail
     * @test
     */
    public function testLoginInvalidEmail()
    {
        $this->browse(function (Browser $browser) {
            $browser->maximize()
                    ->visit('/login')
                    ->assertSee('Login Now')
                    ->type('email', 'jackmerijack@mailinator.com')
                    ->type('password', config('testing.password'))
                    ->click('#btnlogin')
                    ->waitForText('Your email and password combination does not match');
        });
    }

    /**
     * Skenario untuk login dengan invalid password
     *
     * @group loginInvalidPassword
     * @test
     */
    public function testLoginInvalidPassword()
    {
        $this->browse(function (Browser $browser) {
            $browser->maximize()
                    ->visit('/login')
                    ->assertSee('Login Now')
                    ->type('email', config('testing.email'))
                    ->type('password', '12345')
                    ->click('#btnlogin')
                    ->pause(3000)
                    ->waitForText('Your email and password combination does not match');
        });
    }

    /**
     * Skenario untuk lupa password
     * @group forget
     * @group forgetPassword
     * @test
     */
    public function testForgetPassword()
    {
        $this->browse(function (Browser $browser) {
            $browser->maximize()
                    ->visit('/forgot-password')
                    ->assertSee('Forgot your password?')
                    ->type('email', config('testing.email'))
                    ->click('#btnlogin')
                    ->waitForText('Password reset link Sent');
        });
    }

    /**
     * Skenario buka link lupa password lewat email
     * @group forget
     * @group linkForgetPassword
     * @test
     */
    public function testLinkForgetPassword()
    {
        $this->browse(function (Browser $browser) {
            $response = $browser->maximize()
                                ->visit(config('testing.mail'))
                                ->assertSee('Mailinator')
                                ->type('input[type=text]', config('testing.email'))
                                ->click('button.btn.btn-dark')
                                ->waitForText('Reset Password')
                                ->click('ul.single_mail-body > li.all_message-item:first-child > div > div.all_message-min_text.all_message-min_text-3')
                                ->switchFrame('msg_body')
                                ->waitForText('Password Reset Confirmation')
                                ->clickLink('Reset now');
            // Stay di tab reset password
            $window = collect($response->driver->getWindowHandles())->last();
            $response->driver->switchTo()->window($window);
            $response->waitForText('Please enter your new password.')
                     ->type('password', config('testing.password'))
                     ->type('password2', config('testing.password'))
                     ->click('#btnlogin');
            /**
             * Note :
             * Seharusnya setelah ini tampil halaman reset password sukses,
             * sedangkan sekarang yg tampil malah halaman link reset password terkirim
             *
             */
            $response->waitForText('Success');
        });
    }

    /**
     * Skenario logout
     *
     * @group logout
     * @test
     */
    public function testLogOut()
    {
        // Do login
        // $this->testLogin('jackbizzy6@mailinator.com', '123456');

        $this->browse(function (Browser $browser) {
            $browser->visit('/dashboard')
                    ->click('#dropdown10')
                    ->assertSee('Logout')
                    ->click('#navbarCollapse > ul > li > div > a:nth-child(3)')
                    ->waitForText('Login');
        });
    }

    /**
     * Skenario Login testing dengan menggunakan LoginPage
     *
     * @group loginWithPageMethod
     *
     */
    public function testLoginWithPageMethod()
    {
        // $user = factory(User::class)->create([
        //     'email' => 'jackbizzy6@mailinator.com'
        // ]);
        User::where('name', 'jack')->delete();
        User::create(['name' => 'haidar', 'email' => 'haidarafifmaulana@gmail.com', 'password' => bcrypt('rahasia')]);

        $this->browse(function (Browser $browser) {
            $browser->on(new Login)
                    ->login()
                    ->waitForText('Pricing');
        });
    }
}
