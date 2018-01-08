<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Browser\RegisterTest;
use Tests\Browser\PricingTest;

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
}
