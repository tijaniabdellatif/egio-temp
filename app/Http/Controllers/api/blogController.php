<?php

namespace App\Http\Controllers\api;

use App\Models\Post;
use GuzzleHttp\Client;
use Corcel\Model\Taxonomy;
use Illuminate\Http\Request;
use Corcel\Model\Post as Corcel;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\ApiController;

class blogController extends Controller
{
   
   /*  // First method using corcel
    public function getPostId(Request $request){

      $id=$request->id;
      $post = Post::find($id);
      
      //return $post->post_title;
      return $post->post_content; 
       

} */

    // Second method using HTTP Client

    //Fetch all posts ordered by date
    //You can order by id, date, author, title or order=asc#desc
    public function fetchAllPosts(Request $request){

        $link = 'http://localhost/wordpress/index.php'; 
        $response = Http::get($link.'/wp-json/wp/v2/posts?orderby=date&_embed=author,wp:term');
        $posts = json_decode($response,true);
        return $posts;

      } 
    

    // Fetch post with author and category
    public function fetchPostById(Request $request, $id){

        $link = 'http://localhost/wordpress/index.php'; 
        $response = Http::get($link.'/wp-json/wp/v2/posts/'.$id.'?_embed=author,wp:term');
        $posts = json_decode($response,true);
        return $posts;

      } 

    public function fetchCatsByPostId(Request $request, $id){

        // Wordpress link on server
        $link = 'http://localhost/wordpress/index.php';
        $response = Http::get($link.'/wp-json/wp/v2/categories?post='.$id);
         $cats = json_decode($response->body(),true);
         return $cats;

      }

    public function fetchCommentsByPost(Request $request, $postId) {

        // Wordpress link on server
        $link = 'http://localhost/wordpress/index.php';
        $response = Http::get($link.'/wp-json/wp/v2/comments?post='.$postId);  
        $comments = json_decode($response->body());
         foreach ($comments as $element) {

         }
       
        return dd($element->content);
        
      }

    public function fetchTagsByPost(Request $request, $postId){

        // Wordpress link on server
        $link = 'http://localhost/wordpress/index.php';
        $response = Http::get($link.'/wp-json/wp/v2/tags?post='.$postId);  
        $tags = json_decode($response->body());
       
        return dd($tags);
    }

}
