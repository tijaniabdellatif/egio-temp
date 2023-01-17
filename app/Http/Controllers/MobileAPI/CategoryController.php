<?php

namespace App\Http\Controllers\MobileAPI;


use Illuminate\Http\Request;
use App\Repository\Interfaces\CategoryInterface;

class CategoryController extends MobileApiController
{


    private $handler;



    public function __construct(CategoryInterface $handler)
    {

        $this->handler = $handler;

    }


    public function index(){

          return $this->showAll($this->handler->parentCategories(),200);
    }

    public function getKeywords(){


          return $this->showAll($this->handler->getKeywords(),200);
    }


}
