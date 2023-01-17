<?php

namespace App\Console\Commands;

use App\Models\ads;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TeteDeListe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'testdeliste:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tête de liste';

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
        $options = DB::select("SELECT a.id , o.option_id , TIMESTAMPADD(HOUR,24 ,a.published_at) as timee , CURRENT_TIMESTAMP as nowtime FROM `options` o inner join `options_catalogue` oc on o.option_id = oc.id inner join `ads` a on a.id = o.ad_id where oc.type_id = 3 and o.status = '10' and TIMESTAMPADD(DAY,oc.duration ,o.timestamp) > CURRENT_TIMESTAMP and TIMESTAMPADD(HOUR,24 ,a.published_at) <= CURRENT_TIMESTAMP order by o.id desc");
        foreach ($options as $val) {
            ads::where('id','=',$val->id)->update([
                "published_at"=>Carbon::now(),
            ]);
        }
    }
}
