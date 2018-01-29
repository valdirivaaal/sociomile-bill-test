<?php

namespace Tests;

use Laravel\Dusk\TestCase as BaseTestCase;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;

abstract class DuskTestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Prepare for Dusk test execution.
     *
     * @beforeClass
     * @return void
     */
    public static function prepare()
    {
        // static::startChromeDriver();
    }

    /**
     * Create the RemoteWebDriver instance.
     *
     * @return \Facebook\WebDriver\Remote\RemoteWebDriver
     */
    protected function driver()
    {
        $options = (new ChromeOptions)->addArguments([
            '--disable-gpu',
            '--disable-web-security',
            '--window-size=1366, 768'
        ]);

        $driver = RemoteWebDriver::create(
            'http://localhost:9515', DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY, $options
            )
        );

        // $size = new WebDriverDimension(1366, 768);
        // $driver->manage()->window()->setSize($size);
        return $driver;
    }

    protected function drivers()
    {
        // iPhone 6, for instance
        // $ua = 'Mozilla/5.0 (iPhone; CPU iPhone OS 9_1 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13B143 Safari/601.1';
        // $ua = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_6_8) AppleWebKit/537.13+ (KHTML, like Gecko) Version/5.1.7 Safari/534.57.2';
        // $ua = "Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.132 Mobile Safari/537.36";

        // iPhone 6, for instance
        $ua = 'Mozilla/5.0 (iPhone; CPU iPhone OS 9_1 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13B143 Safari/601.1';
        // Device name
        $dn = 'iPhone 7';
        $dm = ['width' => 375, 'height' => 667, 'pixelRatio' => 3];
        $capabilities = DesiredCapabilities::chrome();
        $options = new ChromeOptions;
        // $options->setExperimentalOption('mobileEmulation', ['userAgent' => $ua]);
        $options->setExperimentalOption('mobileEmulation', ['deviceName' => $dn]);
        $options->setExperimentalOption('mobileEmulation', ['deviceMetrics' => $dm]);

        return RemoteWebDriver::create(
            'http://localhost:9515', $options->toCapabilities()
        );
    }
}
