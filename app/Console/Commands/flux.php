<?php

namespace App\Console\Commands;

use App\Models\FluxAds;
use Illuminate\Console\Command;

class flux extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flux:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'flux ads';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        ini_set('memory_limit', '44M');
        $flux = FluxAds::where('active','=',1)->get();
        foreach($flux as $val){
            try{
                $xmlString = file_get_contents($val->link);
                $xmlObject = simplexml_load_string($xmlString);
                $json = json_encode($xmlObject);
                $json = str_replace('<![CDATA[','',$json);
                $json = str_replace(']]>','',$json);
                $data = json_decode($json, true);
                if(is_array($data['annonce'])) $data = $data['annonce'];
                foreach($data as $ad){
                    dd($ad);

                    if(isset($ad->photos['photo'])&&is_array($ad->photos['photo'])){

                        foreach($ad->photos['photo'] as $imgkey => $img){
                          $imgname=trim($img);
                        }
                    }
                    else if(is_array($ad->photos)){
                        foreach($ad->photos as $imgkey => $img){
                            if(isset($img['photo'])){-
                              $imgname=trim($img['photo']);
                            }
                        }
                    }
                }
            }
            catch(\Throwable $th){
                error_log($th->getMessage());
            }
        }
    }
}
