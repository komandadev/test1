<?php

namespace App\Btc;

use Carbon\Carbon;

class BtcPush
{
   public $data = [];

   /**
    * url send Webhook
    * @var string
    */
   public $url = 'https://webhook.site/5cf0c6f1-cdd8-4794-8686-48841b8b0c84';

   public function __construct(array $data = [])
   {
       $this->data = $data;
   }

    public function push()
    {
       
        $data = $this->modifyData();

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                "content-type: application/json",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return(json_decode($response));
        }


    }

    /**
     *  modify Data
     * 
     *  @return array
     */

    public function modifyData()
    {
        $data = $this->getData();
    
        $curses = [];
        $date  = !empty($data['time']['updated']) ? $data['time']['updated'] : '0';

        $dt = new Carbon($date);
        $dt = $dt->addDays(10)->format('Y-m-d\TH:i:s.000');

        if( !empty($data['bpi']) ){
            foreach ( $data['bpi'] as $item){
            
                    $rate = !empty($item['rate_float']) ? $item['rate_float'] : 0 ;
                    $curses [] = [
                        'code' => !empty($item['code']) ? $item['code'] : '',
                        'rate' => $rate,
                        'predicted_rate' => $rate * 10,
                        'predicted_date' => $dt
                    ];
            }
        }

        return $curses;
    }

    public function getData(){

        return $this->data;
    }
}