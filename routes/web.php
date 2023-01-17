<?php

use App\Http\Controllers\TestController;
use App\Http\Controllers\CronJobs;
use App\Http\Controllers\TestController as Testing;
use App\Http\Controllers\v2\frontOfficeController;
use App\Models\User;

use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\facebookController;
use App\Http\Controllers\InstagramController;
use App\Http\Controllers\EmailProspectViews;
use App\Http\Controllers\api\EmailProspectController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

if(config('app.env') === 'production') {
    \URL::forceScheme('https');
}
Route::get('/tested', [Testing::class, 'tested']);

Route::get('/sitemap-index.xml', 'App\Http\Controllers\sitemap\SiteMapController@index');
Route::get("/conditions",[EmailProspectViews::class,'index']);

Route::group(['prefix' => '/sitemap'], function () {
    Route::get('/annonces.xml', 'App\Http\Controllers\sitemap\SiteMapController@ads');

    Route::get('/Listes_immobilier.xml', 'App\Http\Controllers\sitemap\SiteMapController@listAll');
    Route::get('/Listes_type.xml', 'App\Http\Controllers\sitemap\SiteMapController@listByTypes');
    Route::get('/Listes_ville.xml', 'App\Http\Controllers\sitemap\SiteMapController@listByCities');
    Route::get('/Listes_type_ville.xml', 'App\Http\Controllers\sitemap\SiteMapController@listByTypesCities');
    Route::get('/Listes_ville_quartier.xml', 'App\Http\Controllers\sitemap\SiteMapController@listByCitiesNeighborhoods');
    Route::get('/Listes_type_ville_quartier.xml', 'App\Http\Controllers\sitemap\SiteMapController@listByTypesCitiesNeighborhoods');
    Route::get('/Listes_categorie.xml', 'App\Http\Controllers\sitemap\SiteMapController@listByCats');
    Route::get('/Listes_categorie_ville.xml', 'App\Http\Controllers\sitemap\SiteMapController@listByCatsCities');
    Route::get('/Listes_categorie_ville_quartier.xml', 'App\Http\Controllers\sitemap\SiteMapController@listByCatsCitiesNeighborhood');

    Route::get('/Listes_map_immobilier.xml', 'App\Http\Controllers\sitemap\SiteMapController@mapAll');
    Route::get('/Listes_map_type.xml', 'App\Http\Controllers\sitemap\SiteMapController@mapByTypes');
    Route::get('/Listes_map_ville.xml', 'App\Http\Controllers\sitemap\SiteMapController@mapByCities');
    Route::get('/Listes_map_type_ville.xml', 'App\Http\Controllers\sitemap\SiteMapController@mapByTypesCities');
    Route::get('/Listes_map_ville_quartier.xml', 'App\Http\Controllers\sitemap\SiteMapController@mapByCitiesNeighborhoods');
    Route::get('/Listes_map_type_ville_quartier.xml', 'App\Http\Controllers\sitemap\SiteMapController@mapByTypesCitiesNeighborhoods');
    Route::get('/Listes_map_categorie.xml', 'App\Http\Controllers\sitemap\SiteMapController@mapByCats');
    Route::get('/Listes_map_categorie_ville.xml', 'App\Http\Controllers\sitemap\SiteMapController@mapByCatsCities');
    Route::get('/Listes_map_categorie_ville_quartier.xml', 'App\Http\Controllers\sitemap\SiteMapController@mapByCatsCitiesNeighborhood');
});

Route::group(['prefix' => '/v1'], function () {

    //functions
    Route::post('login', 'App\Http\Controllers\authController@loginAdmin');
    Route::post('logout', 'App\Http\Controllers\authController@logoutAdmin');

    //pages
    Route::get('/test', function () {
        return view('welcome');
    });

    Route::get('/login', ['as' => 'login', 'uses' =>  function () {
        if (Auth::user()) {
            return redirect()->intended('/');
        } else {
            return view('login');
        }
    }])->middleware('auth');

    Route::get('/', function () {
        return view('home', ["user" => Auth::user()]);
    })->middleware('auth');

    Route::get('/annonces', function () {
        return view('annonces-table');
    })->middleware('auth');

    Route::get('/addannonces', function () {
        return view('add-annonce');
    })->middleware('auth');

    Route::get('/editannonces/{id}', 'App\Http\Controllers\ads@editPage')->middleware('auth');

    Route::get('/users', function () {
        return view('users');
    })->middleware('auth');

    Route::post('AddAd', 'App\Http\Controllers\ads@AddAd')->middleware('auth');
    Route::post('Edit_Ad', 'App\Http\Controllers\ads@Edit_Ad')->middleware('auth');
});

//router group start with /dashboard and auth middleware
Route::group(['prefix' => '','as'=>'v2.' ,'middleware'=>'lang' ], function () {

    // get settings->seo
    $seo = App\Http\Controllers\api\SettingController::_getSeo();

    // pass settings->seo to response
    view()->share('seo', $seo);

    Route::group(['middleware' => 'guest'], function () {

        //login page
        Route::get('/login', 'App\Http\Controllers\v2\AuthController@getLogin')->name('login');

        //register page
        Route::get('/register', 'App\Http\Controllers\v2\AuthController@getRegister')->name('register');

        //reset password page
        Route::get('/resetpassword', 'App\Http\Controllers\v2\AuthController@getResetPassword')->name('resetPassword');
    });


    //logout pages
    multilistGroup(function () {
        //home page
        Route::get('/', 'App\Http\Controllers\v2\HomeController@getHome')->name('home');

        //listing page
        Route::get('/list', 'App\Http\Controllers\v2\ListController@getList')->name('listing');
        Route::get('/map', 'App\Http\Controllers\v2\ListController@getMap')->name('map');
        Route::get('/immobiliers-neufs', 'App\Http\Controllers\v2\ListController@getProjectsList')->name('projectsList');

        //favoris page
        Route::get('/favoris', 'App\Http\Controllers\v2\HomeController@getFavoris')->name('favoris');

        // detailed ads page
        Route::get('/item/{id}/{titre}', 'App\Http\Controllers\v2\AnnoncesController@getItem')->name('item');

        // detailed ads page
        Route::get('/annonce/{category}/{localisation}/{id}', 'App\Http\Controllers\v2\AnnoncesController@getItemFinal')->name('item');

        // route detailed ads page with just the id /item/{id}, redirect to /item/{id}/{titre}
        Route::get('/item/{id}', 'App\Http\Controllers\v2\AnnoncesController@getItemJustByID')->name('item.id');

        //new ad page
        Route::get('/deposer-annonce', 'App\Http\Controllers\v2\AddController@getAdd')->name('newItem');

        Route::get('/new-project', 'App\Http\Controllers\v2\AddController@getAddProject')->name('newItem');

        //estimate page
        Route::get('/estimation', 'App\Http\Controllers\v2\EstimateController@getEstimate2')->name('estimate');
    });

    Route::get('/map/immobilier', 'App\Http\Controllers\v2\ListController@getCostumMap')->name('listing');
    Route::get('/map/immobilier/{type}-type', 'App\Http\Controllers\v2\ListController@getCostumMap')->name('listing');
    Route::get('/map/immobilier-{city}', 'App\Http\Controllers\v2\ListController@getCostumMap')->name('listing');
    Route::get('/map/immobilier-{city}/{type}-type', 'App\Http\Controllers\v2\ListController@getCostumMap')->name('listing');
    Route::get('/map/immobilier-{city}/{neighborhood}', 'App\Http\Controllers\v2\ListController@getCostumMap')->name('listing');
    Route::get('/map/immobilier-{city}/{neighborhood}/{type}-type', 'App\Http\Controllers\v2\ListController@getCostumMap')->name('listing');
    Route::get('/map/immobilier/{category}', 'App\Http\Controllers\v2\ListController@getCostumMap')->name('listing');
    Route::get('/map/immobilier/{category}/{city}', 'App\Http\Controllers\v2\ListController@getCostumMap')->name('listing');
    Route::get('/map/immobilier/{category}/{neighborhood}/{city}', 'App\Http\Controllers\v2\ListController@getCostumMap')->name('listing');

    Route::get('/immobilier', 'App\Http\Controllers\v2\ListController@getCostumList')->name('listing');
    Route::get('/immobilier/{type}-type', 'App\Http\Controllers\v2\ListController@getCostumList')->name('listing');
    Route::get('/immobilier-{city}', 'App\Http\Controllers\v2\ListController@getCostumList')->name('listing');
    Route::get('/immobilier-{city}/{type}-type', 'App\Http\Controllers\v2\ListController@getCostumList')->name('listing');
    Route::get('/immobilier-{city}/{neighborhood}', 'App\Http\Controllers\v2\ListController@getCostumList')->name('listing');
    Route::get('/immobilier-{city}/{neighborhood}/{type}-type', 'App\Http\Controllers\v2\ListController@getCostumList')->name('listing');
    Route::get('/immobilier/{category}', 'App\Http\Controllers\v2\ListController@getCostumList')->name('listing');
    Route::get('/immobilier/{category}/{city}', 'App\Http\Controllers\v2\ListController@getCostumList')->name('listing');
    Route::get('/immobilier/{category}/{neighborhood}/{city}', 'App\Http\Controllers\v2\ListController@getCostumList')->name('listing');

    Route::get('/immobiliers-neufs-{city}', 'App\Http\Controllers\v2\ListController@getCostumProjects')->name('projectsList');
    Route::get('/immobiliers-neufs-{city}/{neighborhood}', 'App\Http\Controllers\v2\ListController@getCostumProjects')->name('projectsList');
    Route::get('/immobiliers-neufs/{category}', 'App\Http\Controllers\v2\ListController@getCostumProjects')->name('listing');
    Route::get('/immobiliers-neufs/{category}/{city}', 'App\Http\Controllers\v2\ListController@getCostumProjects')->name('listing');
    Route::get('/immobiliers-neufs/{category}/{neighborhood}/{city}', 'App\Http\Controllers\v2\ListController@getCostumProjects')->name('listing');

    Route::group(['middleware' => 'auth'], function () {
        Route::get('/dashboard', 'App\Http\Controllers\v2\frontOfficeController@getDashboard')->name('front.dashboard');
        Route::get('/myprofile', 'App\Http\Controllers\v2\frontOfficeController@getMyProfile')->name('front.myprofile');
        Route::get('/editprofile', 'App\Http\Controllers\v2\frontOfficeController@getEditProfile')->name('front.editprofile');
        Route::get('/myitems', 'App\Http\Controllers\v2\frontOfficeController@getMyItems')->name('front.myitems');
        Route::get('/myemails', 'App\Http\Controllers\v2\frontOfficeController@getMyEmails')->name('front.myemails');
        Route::get('/bookings', 'App\Http\Controllers\v2\frontOfficeController@getBookings')->name('front.bookings');
        Route::get('/mytransactions', 'App\Http\Controllers\v2\frontOfficeController@getMyTransactions')->name('front.mytransactions');
        Route::get('/reset-password','App\Http\Controllers\v2\frontOfficeController@getPasswordPage')->name('front.mpd');
    });

    // Static pages
    Route::get('/aboutus', [frontOfficeController::class, 'getAbout'])->name('aboutus');
    Route::get('/info', [frontOfficeController::class, 'getInfo'])->name('info');
    Route::get('/cookies', [frontOfficeController::class, 'getCookies'])->name('cookies');
    Route::get('/privacy', [frontOfficeController::class, 'getPrivacy'])->name('privacy');
    Route::get('/roles', [frontOfficeController::class, 'getRoles'])->name('roles');

    // profile
    Route::get('/profile/{id}', 'App\Http\Controllers\v2\ProfileController@getProfile')->name('profile');


    //dashboard page
    Route::group(['prefix' => '/admin', 'as' => 'admin.', 'middleware' => ['auth', 'administration']], function () {
        Route::get('/', 'App\Http\Controllers\v2\DashboardController@getHome')->name('dashboard');

        //profile
        Route::get('/profile',[App\Http\Controllers\v2\DashboardController::class,'getProfileUser'])->name('dashboard.profile');
        //ads
        Route::get('/items', 'App\Http\Controllers\v2\AnnoncesController@index')->name('ads')->middleware('role_or_permission:Show-ads');
        Route::get('/new-ad', 'App\Http\Controllers\v2\AnnoncesController@getNewAdPage')->name('new_ad')->middleware('role_or_permission:Add-ads');

        // emails
        Route::get('/emails', 'App\Http\Controllers\v2\EmailController@emailsPage')->name('emails')->middleware('role_or_permission:Show-emails');

        // messages
        Route::get('/messages', 'App\Http\Controllers\v2\MessageController@messagesPage')->name('messages');
        Route::get('/conversation', 'App\Http\Controllers\v2\MessageController@MessageController')->name('conversation');

        // utilisateurs
        Route::get('/users', 'App\Http\Controllers\v2\UserController@getManage')->name('manageusers')->middleware('role_or_permission:Show-users');
        Route::get('/user/add', 'App\Http\Controllers\v2\UserController@addUser')->name('adduser')->middleware('role_or_permission:Add-user');

        //settings pages group
        Route::group(['prefix' => '/settings', 'as' => 'settings.'], function () {

            //settings pages
            Route::get('/banners', 'App\Http\Controllers\v2\SettingsController@getBanners')->name('banners')->middleware('role_or_permission:Show-banners');
            Route::get('/options', 'App\Http\Controllers\v2\SettingsController@getOptions')->name('options')->middleware('role_or_permission:Show-options');
            Route::get('/emails', 'App\Http\Controllers\v2\SettingsController@getEmails')->name('emails')->middleware('role_or_permission:Show-emails-catalogue');
            Route::get('/pages', 'App\Http\Controllers\v2\SettingsController@getPages')->name('pages')->middleware('role_or_permission:Show-pages');
            Route::get('/links', 'App\Http\Controllers\v2\SettingsController@getLinks')->name('links')->middleware('role_or_permission:Show-links');
            Route::get('/categories', 'App\Http\Controllers\v2\SettingsController@getCats')->name('cats')->middleware('role_or_permission:Show-categories');
            Route::get('/option-types', 'App\Http\Controllers\v2\SettingsController@getTypes')->name('types')->middleware('role_or_permission:Show-property-types');
            Route::get('/standings', 'App\Http\Controllers\v2\SettingsController@getStandings')->name('standings')->middleware('role_or_permission:Show-standings');
            Route::get('/seo', 'App\Http\Controllers\v2\SettingsController@getSeo')->name('seo')->middleware('role_or_permission:Show-seo');
            Route::get('/cycle-de-vie', 'App\Http\Controllers\v2\SettingsController@getCycle')->name('cycle')->middleware('role_or_permission:Show-cycle');
            Route::get('/privileges', 'App\Http\Controllers\v2\SettingsController@getPrivileges')->name('privileges')->middleware('role_or_permission:Show-privileges');
            Route::get('/plans', 'App\Http\Controllers\v2\SettingsController@getPlans')->name('plans')->middleware('role_or_permission:Show-plans');
            Route::get('/localisations', 'App\Http\Controllers\v2\SettingsController@getLocalisation')->name('locs')->middleware('role_or_permission:Show-localisations');
            Route::get('/logs', 'App\Http\Controllers\v2\SettingsController@getLgs')->name('logs')->middleware('role_or_permission:Show-logs');
            Route::get('/notifications', 'App\Http\Controllers\v2\SettingsController@getNotifications')->name('logs')->middleware('role_or_permission:Show-logs');
        });

        //users pages
        Route::get('/test', 'App\Http\Controllers\v2\DashboardController@getTest')->name('test');
    });
});

// function to dynamicly switch between multilist different types
// arrow function as parameter
function multilistGroup($function)
{

    // get request url without domain name
    $url = request()->url();
    $url = str_replace(request()->root(), '', $url);
    // get first part of url
    $multilistType = explode('/', $url);
    if (isset($multilistType[1])) {
        $multilistType = $multilistType[1];
    } else {
        $multilistType = '';
    }

    // $as = $multilistType . '.';

    // if ($multilistType == '') {
    //     $as = '';
    // }

    // check if $multilistType is in array of allowed types ['booklist','homelist','primelist','landlist',officelist']
    if (!in_array($multilistType, ['multilist', 'booklist', 'homelist', 'primelist', 'landlist', 'officelist'])) {
        $multilistType = '';
    }

    // pass multilistType to response
    view()->share('multilistType', $multilistType);

    // route group based on $multilistType
    Route::group(['prefix' => '/' . $multilistType], function () use ($function) {

        // call function
        $function();
    });
}

// route to clear cache
Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    return "Cache is cleared";
});

// get auth user
/**
 * Fetchinf data
 */
Route::get('/auth-user', function (Request $request) {
    dd(Auth::user());
});

Route::get('/test', function (Request $request) {

    $user = User::find($request->id);

    // check if password is correct
    dd(Hash::check($request->password, $user->password));
});


Route::get('/cron', [CronJobs::class, 'getCron']);

Route::get('/bmci', 'App\Http\Controllers\Bmci@index');
Route::get('/bmci-expert-credit-immobilier','App\Http\Controllers\Bmci@showPage');

// google Auth
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle']);
Route::get('/auth/callback/google', [GoogleController::class, 'handleGoogleCallback']);

// facebook Auth
Route::get("/redirect/facebook", [facebookController::class, 'redirect']);
Route::get("/auth/callback/facebook", [facebookController::class, 'callback']);

// instagram test Auth
Route::get("/redirect/instagram", [InstagramController::class, 'redirect']);
Route::get("/auth/callback/instagram", [InstagramController::class, 'callback']);

Route::get('/EmailProspectCheck/{key?}', [EmailProspectController::class,'checkProccess']);

