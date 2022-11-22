<?php

namespace App\Console\Commands;

use App\Btc\BtcPush;
use App\Btc\BitcointParse;
use Illuminate\Console\Command;


class bitcoinPrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'btc:price';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Получать данные о курсе bitcoin, с публичного API, например https://api.coindesk.com/v1/bpi/currentprice.json';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {   

        $parse = new BitcointParse(); 
        $data = $parse->run();
        
        if($this->isJSON($data)){
            $data = json_decode($data, true);
            $send = new BtcPush($data);
            $send->push();
        }
       
        return Command::SUCCESS;
    }

    function isJSON($string){
        return is_string($string) && is_array(json_decode($string, true)) ? true : false;
    }
}
