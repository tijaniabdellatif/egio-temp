<?php


namespace App\Repository;

 use App\Models\cats;
 use App\Models\User;
use App\Repository\Interfaces\CategoryInterface;
use Illuminate\Http\Request;

class CategoryRepository implements CategoryInterface {


      public function parentCategories(){

          $categories = cats::where('parent_cat',NULL)->get(["id",'title']);
          $dataLayer = [];

          foreach($categories as $category){



               if($category['title'] === 'Homelist'){

                  $category['color'] = '#F64D4B';
               }

               if($category['title'] === 'Officelist'){

                $category['color'] = '#00537D';
               }

               if($category['title'] === 'landlist'){

                $category['color'] = '#54C21B';
               }


               if($category['title'] === 'primelist'){

                $category['color'] = '#F3BE2E';
               }

               if($category['title'] === 'booklist'){

                $category['color'] = '#B52483';
               }



               array_push($dataLayer,$category);
          }




          return $dataLayer;



      }

      public function childCategories(){


        return ['hello world'];

      }


      public function getKeywords()
      {

          $categories = cats::where('parent_cat','!=',null)->get()->pluck('fields');

          return $categories;




      }



}
