<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Browser\RegisterTest;
use Tests\Browser\PricingTest;
use Tests\Browser\Pages\Login;
use Tests\Browser\Pages\Settings;

class FullTest extends DuskTestCase
{
    /**
     * Skenario full dari register sampai beli paket bronze
     *
     * @group caseBronze
     */
    public function testFullScenarioBronze()
    {
        /* Registrasi */
        $registrasi = new RegisterTest;
        $registrasi->testRegisterWithEmailVerification();

        /* Beli paket bronze */
        $purchase = new PricingTest;
        $purchase->testPurchaseBronze();
    }

    /**
     * Skenario full dari register sampai beli paket silver
     *
     * @group caseSilver
     */
    public function testFullScenarioSilver()
    {
        /* Registrasi */
        $registrasi = new RegisterTest;
        $registrasi->testRegisterWithEmailVerification();

        /* Beli paket bronze */
        $purchase = new PricingTest;
        $purchase->testPurchaseSilver();
    }

    /**
     * Skenario full dari register sampai beli paket gold
     *
     * @group caseGold
     */
    public function testFullScenarioGold()
    {
        /* Registrasi */
        $registrasi = new RegisterTest;
        $registrasi->testRegisterWithEmailVerification();

        /* Beli paket bronze */
        $purchase = new PricingTest;
        $purchase->testPurchaseGold();
    }

    /**
     * Skenario untuk testing csat sociomile
     *
     * @group caseCSAT
     */
    public function testCSAT()
    {
        $this->browse(function($first, $second){
            $first  ->resize(1440, 900  )
                    ->visit(config('testing.url'))
                    ->assertSee('Login')
                    ->on(new Login)
                    ->type('@smEmail', config('testing.email'))
                    ->type('@smPassword', config('testing.password'))
                    ->click('@btnLogin')
                    ->waitForText('DASHBOARD', 10)

                    /* BAGIAN PENAMBAHAN AKUN CHANNEL */
                    ->click('#setting > a')// Click navigasi setting
                    ->assertSee('User Settings')// Assert yg ada di halaman project
                    ->click('#main > div.layout-two-menu.hidden-xs.hidden-sm > div.section-main > ul > li:nth-child(4) > a')// Click tab menu account channel
                    ->assertSee('Account Channel Settings')// Assert halaman account channel
                    ->waitForText('ADD ACCOUNT')
                    ->click('#layout-setting > div > div > div.col-md-6.text-right > button')// Click button tambah akun
                    ->waitFor('#layout-setting > div.modal.modal-form')// Wait modal muncul
                    ->select('media', 'email_standar')
                    ->waitForText('Email')
                    ->type('name', 'Alay')
                    ->type('user', config('testing.email1'))
                    ->type('password', config('testing.email_password'))
                    ->click('#form-connect > div.modal-footer > div > div > button')
                    ->waitForText('Connecting email')
                    ->waitFor('.loader')
                    ->waitUntilMissing('#layout-setting > div.modal.modal-form', 60)
                    ->waitForText('Success', 10)
                    ->pause(2000)
                    ->click('button.confirm')->pause(2000);

                    /* SETUP CSAT */
                    $first  ->click('#layout-setting > div > table > tbody:nth-child(2) > tr:nth-child(2) > td:nth-child(8) > a')
                            ->waitFor('.modal');

                            // Validasi apakah csat sudah di set atau blm, klo udah skip
                            $disabled = $first->script('return $("textarea[name=csat_message]").attr("disabled")');

                            if($disabled[0] === null) {
                                $first  ->click('div.modal.modal-form > div > div > form > div.modal-footer > div > div > button');
                            } else {
                                $first  ->click('div.slider.round')->pause(2000)
                                        ->type('csat_message', 'Terima kasih telah menghubungi kami, silahkan klik link untuk mengukur kepuasan anda akan layanan kami.')
                                        ->click('div.modal.modal-form > div > div > form > div.modal-footer > div > div > button');
                            }
                    $first  ->waitUntilMissing('.modal')->pause(2000)
                            ->waitFor('.sweet-alert')
                            ->assertSee('success')
                            ->click('button.confirm')->pause(3000);

                    /* BAGIAN MENGIRIM EMAIL MELALUI GMAIL */
                    $second ->visit(config('testing.gmail'))
                            ->pause(2000)
                            ->type('identifier', config('testing.email2'))
                            ->click('#identifierNext')->pause(2000)
                            ->type('password', config('testing.email_password'))
                            ->click('#passwordNext')->pause(2000)
                            // ->click('#yDmH0d > c-wiz.yip5uc.SSPGKf > c-wiz > div > div.p9lFnc > div > div > div > div.ZRg0lb.Kn8Efe > div:nth-child(3) > div > div.yKBrKe > div')
                            ->waitForText('Gmail')
                            ->click('.aic > div > div')->pause(2000)
                            ->type('to', config('testing.email1'))
                            ->type('subjectbox', 'Automate Mail by Dusk')
                            ->type('div.Am.Al.editable', 'Hai ini pesan otomatis yg dibuat dengan laravel dusk')
                            ->click('div.btA > div:nth-child(2)')
                            ->pause(5000);

                    /* BAGIAN MEMBUKA TIKET */
                    $first  ->click('#ticket')
                            ->waitForText('view 1 new update tickets', 600)->pause(3000)
                            ->click('div.new-stream')->pause(2000)
                            ->waitFor('.ticket-card', 10)->pause(2000);
                    $first  ->element('#cmiddle > stream > div.timelines > div:nth-child(2) > div:nth-child(2) > div.tl-footer > div.pull-right > a')->getLocationOnScreenOnceScrolledIntoView();
                    $first  ->click('#cmiddle > stream > div.timelines > div:nth-child(2) > div:nth-child(2) > div.tl-footer > div.pull-right > a')
                            ->waitFor('button.btn-blue-fb')
                            ->click('button.btn-blue-fb')
                            ->waitFor('.sweet-alert')->pause(5000)
                            ->click('div.sa-confirm-button-container > button.confirm')
                            ->waitUntilMissing('div.detail-ticket', 20)
                            ->waitForText('Closed!')->pause(5000)
                            ->click('button.confirm')->pause(5000);

                    /* BAGIAN BUKA CSAT EMAIL */
                    $second ->click('div.UI > div > div > div.Cp > div > table > tbody > tr:nth-child(1)')
                                        ->waitForText('Hi')
                                        ->click('div.a3s > div > div > div > div > p:nth-child(2) > a')->pause(5000);

                    // Agar stay di tab / halaman status verifikasi
                    $window = collect($second->driver->getWindowHandles())->last();
                    $second->driver->switchTo()->window($window);
                    $second->waitForText('Thank you! Your respond has been submited.');

                    /* BAGIAN LOGOUT */
                    $first  ->click('div.navr.user-nav > a')->pause(3000)
                            ->clickLink('Logout')
                            ->waitForText('Login', 8)
                            ->assertSee('Login');
        });
    }

    /**
     * Skenario untuk testing csat sociomile twitter
     *
     * @group caseTwitter
     */
     public function testCSATtwitter()
     {
         $this->browse(function ($first, $second) {

             /* LOGIN KE TWITTER */
             $second
                ->visit('http://twitter.com/login?lang=id')
                ->value('.js-username-field', 'alaayaf01')
                ->value('.js-password-field', 'achmadfadly')
                ->click('button.submit')
                ->waitFor('#tweet-box-home-timeline', 30);


             $first
                ->resize(1440, 900  )
                ->visit('http://twitter.com/login?lang=id')
                ->value('.js-username-field', 'alaayaf02')
                ->value('.js-password-field', 'achmadfadly')
                ->click('button.submit')
                ->visit(config('testing.url'))
                ->assertSee('Login')
                ->on(new Login)
                ->type('@smEmail', config('testing.email'))
                ->type('@smPassword', config('testing.password'))
                ->click('@btnLogin')
                ->waitForText('DASHBOARD', 10)

                 /* BAGIAN PENAMBAHAN AKUN CHANNEL TWITTER */
                ->click('#setting > a')// Click navigasi setting
                ->assertSee('User Settings')// Assert yg ada di halaman project
                ->click('#main > div.layout-two-menu.hidden-xs.hidden-sm > div.section-main > ul > li:nth-child(4) > a')// Click tab menu account channel
                ->assertSee('Account Channel Settings')// Assert halaman account channel
                ->waitForText('ADD ACCOUNT')
                ->click('#layout-setting > div > div > div.col-md-6.text-right > button')// Click button tambah akun
                ->waitFor('#layout-setting > div.modal.modal-form')// Wait modal muncul
                ->select('media', 'twitter')->pause(3000)
                ->click('div.modal-footer > div > div > button.btn-fill')->pause(10000)
                ->waitFor('.sweet-alert')
                ->assertSee('Success')
                ->click('button.confirm')->pause(3000);

                /* SETUP CSAT */
                $first  ->click('#layout-setting > div > table > tbody:nth-child(3) > tr:nth-child(2) > td:nth-child(8) > a')
                        ->waitFor('.modal');

                        // Validasi apakah csat sudah di set atau blm, klo udah skip
                        $disabled = $first->script('return $("textarea[name=csat_message]").attr("disabled")');
                        // dd($disabled);
                        // dd($disabled[0]);
                        // die();
                        if($disabled[0] === null) {
                            $first  ->pause(3000)->click('div.modal.modal-form > div > div > form > div.modal-footer > div > div > button');
                        } else {
                            $first  ->click('div.slider.round')->pause(2000)
                                    ->type('csat_message', 'Terima kasih telah menghubungi kami, silahkan klik link untuk mengukur kepuasan anda akan layanan kami.')->pause(3000)
                                    ->click('div.modal.modal-form > div > div > form > div.modal-footer > div > div > button');
                        }
                $first  ->waitUntilMissing('.modal')->pause(2000)
                        ->waitFor('.sweet-alert')
                        ->assertSee('success')
                        ->click('button.confirm')->pause(3000);

                /* BAGIAN MENTION KE TWITTER */
                $second
                    ->type('#tweet-box-home-timeline', '@alaayaf02 Hai ini pesan otomatis yg dibuat dengan laravel dusk '.time())
                    ->click('#timeline > div.timeline-tweet-box > div > form > div.TweetBoxToolbar > div.TweetBoxToolbar-tweetButton.tweet-button > button');

                /* BAGIAN MEMBUKA TIKET */
                $first  ->pause(10000)
                        ->click('#ticket')
                        ->waitForText('view 1 new update tickets', 300)->pause(3000)
                        ->click('div.new-stream')->pause(2000)
                        ->waitFor('.ticket-card', 10)->pause(2000);
                $first  ->element('#cmiddle > stream > div.timelines > div:nth-child(2) > div:nth-child(2) > div.tl-footer > div.pull-right > a')->getLocationOnScreenOnceScrolledIntoView();
                $first  ->click('#cmiddle > stream > div.timelines > div:nth-child(2) > div:nth-child(2) > div.tl-footer > div.pull-right > a')
                        ->waitFor('button.btn-blue-fb')
                        ->click('button.btn-blue-fb')
                        ->waitFor('.sweet-alert')->pause(5000)
                        ->click('div.sa-confirm-button-container > button.confirm')
                        ->waitUntilMissing('div.detail-ticket', 20)
                        ->waitForText('Closed!')->pause(5000)
                        ->click('button.confirm')->pause(5000);
#stream-item-activity-951665594041974785 > div > div.content > div.js-tweet-text-container > p > a.twitter-timeline-link
#stream-item-activity-956081589569536001 > div > div.content > div.js-tweet-text-container > p > a.twitter-timeline-link > span.js-display-url
                $second
                        ->pause(2000)
                        ->click('#global-actions > li.people.notifications > a')
                        ->pause(3000)
                        ->click('#stream-items-id > li:nth-child(1) > div > div > div > p > a > span.js-display-url')->pause(5000);

                // Agar stay di tab / halaman status verifikasi
                $window = collect($second->driver->getWindowHandles())->last();
                $second->driver->switchTo()->window($window);
                $second
                    ->waitForText('Please Connect with your twitter account to continue', 20)
                    ->click('#app > div.csat-container > div.csat-verify > div > div > p:nth-child(2) > i')
                    ->waitForText('How would you rate the support you received?', 10)
                    ->click('#app > div.csat-container > div.csat-verify > div > div > p:nth-child(2) > a') // Click satisfy
                    // ->click('#app > div.csat-container > div.csat-verify > div > div > p:nth-child(3) > a') // Click unsatisfy
                    ->waitFor('div.swal2-modal')
                    ->assertSee('Your response has been submitted')->pause('3000')
                    ->click('button.swal2-confirm')->pause(2000)
                    ->waitForText('Thank you! Your respond has been submited');
                    ;

                 /* BAGIAN LOGOUT */
                 $first  ->click('div.navr.user-nav > a')->pause(3000)
                         ->clickLink('Logout')
                         ->waitForText('Login', 8)
                         ->assertSee('Login');
         });
     }

     /**
      * Tes untuk webchat
      *
      * @group caseWebChat
      */
     public function testWebChat()
     {
         $this->browse(function($first, $second) {
             /* LOGIN KE B2B */
             $second
                ->visit('https://appb2b-sm.s45.in/login')
                ->assertSee('Login')
                ->type('email', 'agent7@r10.co')
                ->type('password', '123456')
                ->click('#btnlogin')
                ->waitForText('DASHBOARD', 10);

            /* CHAT VIA WEB CHAT */
            $first
               ->visit('https://chatdev.sociomile.com')
               ->click('div.sociomile-container')->pause(3000)
               ->assertSee('Selamat datang di live chat kami.')
               ->type('name', 'John Pantau')
               ->type('email', 'johnpantau'.time().'@mail.com')
               ->type('message', 'Halo ini automate pesan oleh laravel dusk')
               ->click('button.sociomile-btn')
               ->waitForText('Mohon tunggu sebentar')->pause(3000)
               ->click('div.sociomile-container.sociomile-survey > div.sociomile-body > div > p:nth-child(2) > a') // Satisfy
               ->click('div.sociomile-container.sociomile-survey > div.sociomile-body > div > p:nth-child(3) > a') // Unsatisfy
               ->waitForText('Submitting your response...')
               ;

            /* PICK CHAT OLEH AGENT */
            $second
                ->waitFor('#cleft > chatlist', 10)
                ->click('div.panel-body > a.chats')->pause(2000)
                ->waitFor('div.win-panel')->pause(3000)
                ->assertSee('Halo ini automate pesan oleh laravel dusk');

            /* BAGIAN LOGOUT */
            $second
                ->click('div.navr.user-nav > a')->pause(3000)
                ->clickLink('Logout')
                ->waitForText('Login', 8)
                ->assertSee('Login');

         });
     }

     /**
      *
      *
      * @group caseRevamp
      */
     public function testRevamp()
     {
         $this->browse(function ($first) {
            $first
                ->visit('https://sm.ngetest.com')->pause(30000);
         });
     }
}
