<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Lib\DataManager;

class scrapping extends Controller
{
    use DataManager;


    public function getAds(){


        return $this->adsSeeder();


    }

    public function refSeeder(){


        return $this->getRefs();


    }

    public function mailSeeder(){


        return $this->sendMailsToAgencies();


    }

    public function coinsSeeder(){


        return $this->getCoins();


    }



    public function getUsers(){


        return $this->usersSeeder();


    }

    public function getContracts(){


        return $this->contractsSeeder();


    }
}
