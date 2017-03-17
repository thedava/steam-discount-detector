<?php

use GuzzleHttp\Client;

class SteamApp
{
    /**
     * The url of the steamApp
     *
     * @var string
     */
    protected $url;

    /**
     * The name of the app
     *
     * @var string
     */
    protected $name;

    /**
     * @var DiscountCheck
     */
    protected $check;

    /**
     * @var int|Price[]
     */
    protected $prices = -1;

    /**
     * SteamApp constructor.
     *
     * @param string        $name
     * @param string        $url
     * @param DiscountCheck $check
     */
    public function __construct($name, $url, DiscountCheck $check)
    {
        $this->name = $name;
        $this->url = $url;
        $this->check = $check;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Returns the ageCheck url
     *
     * @return string
     */
    public function getAgeCheckUrl()
    {
        return str_replace('.com/app/', '.com/agecheck/app/', $this->getUrl());
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Price[]
     */
    public function getPrices()
    {
        if ($this->prices === -1) {
            $this->prices = $this->checkApp();
        }

        return $this->prices;
    }

    /**
     * @return string
     */
    public function getPricesAsString()
    {
        $result = [];
        foreach ($this->getPrices() as $price) {
            $text = ($price->hasDiscount()) ? '[DISCOUNT]' : '[No Discount]';
            $text .= ' ' . $price->getName() . ' => ' . $price->getCurrentPrice();

            if ($price->hasDiscount()) {
                $text .= ' (Original: ' . $price->getPrice() . ')';
            }

            $result[] = $text;
        }

        return implode(PHP_EOL, $result);
    }

    /**
     * Checks if the app has a discount
     *
     * @return Price[]
     */
    public function checkApp()
    {
        $client = new Client([
            'cookies' => true,
        ]);

        return $this->check->invoke($this, $client);
    }

    /**
     * Perform an age check for this app
     *
     * @param Client $client
     *
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function performAgeCheck(Client $client)
    {
        $months = ['January', 'February', 'March', 'April', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $month = $months[array_rand($months)];
        $day = mt_rand(1, ($month == 'February') ? 28 : 30);
        $year = mt_rand(date('Y') - 40, date('Y') - 22);

        // POST age
        return $client->request('POST', $this->getAgeCheckUrl(), [
            'form_params'     => [
                'ageDay'   => $day,
                'ageMonth' => $month,
                'ageYear'  => $year,
            ],
            'allow_redirects' => true,
        ]);
    }

    /**
     * @param array $appFiles
     *
     * @return array
     */
    public static function getAppOutput(array $appFiles)
    {
        // Collect outputs
        $outputs = [];
        foreach ($appFiles as $file) {
            $outputs[$file] = [
                'basename' => basename($file),
            ];

            if (!file_exists($file)) {
                $outputs[$file]['error'] = 'App file not found!';
                continue;
            }

            /** @var SteamApp $app */
            $app = include $file;

            if (!$app instanceof SteamApp) {
                $outputs[$file]['error'] = 'Not a valid app file!';
                continue;
            }

            $outputs[$file]['app'] = $app;
            $outputs[$file]['output'] = $app->getPricesAsString();
        }

        return $outputs;
    }

    /**
     * @param array $appFiles
     */
    public static function printAppOutput(array $appFiles)
    {
        $outputs = static::getAppOutput($appFiles);

        foreach ($outputs as $file => $output) {
            $headline = 'App: ' . $output['basename'];
            echo $headline, PHP_EOL, str_repeat('=', strlen($headline)), PHP_EOL, PHP_EOL;

            if (isset($output['error'])) {
                echo $output['error'], PHP_EOL;
                echo 'File: ', $file, PHP_EOL;
            } else {
                /** @var SteamApp $app */
                $app = $output['app'];
                echo 'Name: ', $app->getName(), PHP_EOL;
                echo 'Url: ', $app->getUrl(), PHP_EOL;

                echo PHP_EOL, 'Output:', PHP_EOL, str_repeat('-', 8), PHP_EOL;
                echo trim($output['output']), PHP_EOL;
                echo str_repeat('-', 8), PHP_EOL;
            }

            echo PHP_EOL, PHP_EOL, PHP_EOL;
        }
    }
    
    /**
     * @return string
     */
    public static function getAppsPath()
    {
        return realpath(__DIR__ . '/../apps');
    }
}
