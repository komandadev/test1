<?php
namespace App\Btc;

class BitcointParse
{
    public $url = 'https://api.coindesk.com/v1/bpi/currentprice.json';

    public function run()
    {
        try {

            $request = $this->callApi();
            return $request;

        } catch (\Throwable $th) {

            dd($th->getMessage());

        }
    
    }

    public function callApi()
    {   
        $url = $this->url;

        $request = file_get_contents($url);

        return $request;
        
    }
}

