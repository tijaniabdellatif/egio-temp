<?php


use App\Http\Controllers\scrapping;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\api\adsController;
use App\Http\Controllers\api\HomeController;
use App\Http\Controllers\api\HandlerController;
use App\Http\Controllers\facebookController;
use App\Http\Controllers\api\EmailController;
use App\Http\Controllers\api\LeadsController;
use App\Http\Controllers\api\SettingController;
use App\Http\Controllers\LogRegistryController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\MobileAPI\AdsController as AdsControllerHandler;
use App\Http\Controllers\MobileAPI\AuthController;
use App\Http\Controllers\MobileAPI\CategoryController;
use App\Http\Controllers\api\MultilistFilterFromController;
use App\Http\Controllers\api\TestController;
use App\Http\Controllers\MobileAPI\AdsController as Advertiser;
use App\Http\Controllers\api\MyDashboardController;
use App\Http\Controllers\api\EmailProspectController;
use App\Http\Controllers\api\EstimatorController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


if(config('app.env') === 'production') {
    \URL::forceScheme('https');
}

// comment


Route::get('/transform', [LeadsController::class, 'transformLead']);
Route::get('/emails-pros',[EmailProspectController::class,'EmailProcess']);
Route::get("/sendemail-pros",[EmailProspectController::class,'sendEmails']);
Route::get("/getprospect",[EmailProspectController::class,'getProspect']);
Route::get("/getsingle",[EmailProspectController::class,'getSingleProspect']);
Route::patch("/acceptconditions",[EmailProspectController::class,'acceptCondition']);
Route::get("/getFailedAds",[EmailProspectController::class,'countDataFailed']);
Route::post('/send-estimation',[EstimatorController::class,'sendEmailEstimation']);




Route::post('sendmails', 'App\Http\Controllers\TestController@sendEmailToPromoters');
Route::post('sendmailtest', 'App\Http\Controllers\api\TestController@mail');

Route::prefix('v1')->group(function () {

    Route::post('/updatePassword',[HandlerController::class,'updatePassword']);
    Route::post('/register', 'App\Http\Controllers\users@getById')->name('api.users.getById');

    Route::post('adspage/initPage', 'App\Http\Controllers\adspage@initPage')->name('api.adspage.initPage');
    //uploads
    Route::post('uploadImage', 'App\Http\Controllers\upload@uploadImage')->name('api.upload.uploadImage');
    Route::post('uploadImages', 'App\Http\Controllers\upload@uploadImages')->name('api.upload.uploadImages');
    Route::post('uploadVideo', 'App\Http\Controllers\upload@uploadVideo')->name('api.upload.uploadVideo');
    Route::post('uploadVideos', 'App\Http\Controllers\upload@uploadVideos')->name('api.upload.uploadVideos');
    Route::post('uploadAudio', 'App\Http\Controllers\upload@uploadAudio')->name('api.upload.uploadAudio');
    Route::post('uploadAudios', 'App\Http\Controllers\upload@uploadAudios')->name('api.upload.uploadAudios');
});


Route::group(['prefix' => '/v2', 'middleware' => ['cors', 'json.response']], function () {


    Route::post('/check',[EmailController::class,'getUserEmail']);
    Route::post('/checkbyemail',[EmailController::class,'getUserByEmail']);
    Route::get('testmail', [facebookController::class, 'testMail']);
    Route::get('keywords', [CategoryController::class, 'getKeywords']);
    Route::get("/currentUserActivities", [LogRegistryController::class, "CurrentUserLogActivities"]);
    Route::get("/getLastWeekLogs", [LogRegistryController::class, "lastWeekLogs"]);
    Route::get("/getLastMonthLogs", [LogRegistryController::class, "LastMonthLogs"]);
    Route::get("/logActivitiesByUid/{uid}", [LogRegistryController::class, "logActivitiesByUid"]);
    Route::get("/logActivities", [LogRegistryController::class, "logActivity"]);
    Route::get("/latest", [AdsController::class, "getLatestActivities"]);



    Route::get('/getUnreadNotifications', [NotificationController::class, 'getUnreadNotifications']);
    Route::get('/getNotifications', [NotificationController::class, 'getNotifications']);
    Route::get('/makeNotificationsAsRead', [NotificationController::class, 'markAllNotificationsAsRead']);
    Route::get('/clearAllNotifications', [NotificationController::class, 'clearAll']);
    Route::get('/sendExpiredAdNotif', [App\Helpers\ExpiredAds::class, 'sendExpiredAdNotif']);
    Route::get('/sendAdWillExpiredAdNotif', [App\Helpers\ExpiredAds::class, 'sendAdWillExpiredAdNotif']);
    Route::get('/markNotificationAsRead/{id}', [NotificationController::class, 'markNotif']);


    //auth login and register and logout
    Route::post('login', 'App\Http\Controllers\api\authController@login');
    Route::post('switchUser', 'App\Http\Controllers\api\authController@loginS');
    Route::post('register', 'App\Http\Controllers\api\authController@register');
    Route::post('resetpassword', 'App\Http\Controllers\api\authController@resetPassword');
    Route::post('requestresetpassword', 'App\Http\Controllers\api\authController@requestResetPassword');
    Route::post('verifycode', 'App\Http\Controllers\api\authController@verifyCode');
    Route::post('logintest', 'App\Http\Controllers\api\authController@login');
    Route::post('registerAndLogin', 'App\Http\Controllers\api\authController@registerAndLogin');

    // group of middleware for auth
    Route::group(['middleware' => ['auth']], function () {


        Route::post('logout', 'App\Http\Controllers\api\authController@logout');
        Route::post('/user/filter', 'App\Http\Controllers\api\userController@filter');
        Route::get('user/types', 'App\Http\Controllers\api\userController@getUsersTypes');
        Route::post('user/create', 'App\Http\Controllers\api\userController@createUser');
        Route::post('user/update/{id}', 'App\Http\Controllers\api\userController@updateUser');
        Route::get('user/{id}', 'App\Http\Controllers\api\userController@getUser');
        Route::get('getHeaderUser/{id}', 'App\Http\Controllers\api\userController@getHeaderUser');
        Route::post('user/changepassword/{id}', 'App\Http\Controllers\api\userController@changePassword');
        Route::post('user/updatestate/{id}', 'App\Http\Controllers\api\userController@updateState');
        Route::get('user/status/{id}', 'App\Http\Controllers\api\userController@getUserStatus');
        Route::get('user/contracts/{id}', 'App\Http\Controllers\api\userController@getUserContracts');
        Route::get('user/lastcontract/{id}', 'App\Http\Controllers\api\userController@getUserLastContract');
        Route::post('user/createcontract/{id}', 'App\Http\Controllers\api\userController@createContract');
        Route::post('user/updatecontract/{id}', 'App\Http\Controllers\api\userController@updateContract');
        Route::get('user/coins/{id}', 'App\Http\Controllers\api\userController@getUserCoins');
        Route::post('items/filter', 'App\Http\Controllers\api\adsController@filter');
        Route::post('items/statesByAd', 'App\Http\Controllers\api\adsController@statesByAd');
        Route::post('items/statesByUser', 'App\Http\Controllers\api\adsController@statesByUser');
        Route::post('items/getAdById', 'App\Http\Controllers\api\adsController@getAdById');
        Route::post('items/addAd', 'App\Http\Controllers\api\adsController@addAd');
        Route::post('items/updateAd', 'App\Http\Controllers\api\adsController@updateAd');
        Route::post('items/updateStatus', 'App\Http\Controllers\api\adsController@updateStatus');
        Route::post('items/updateOptions', 'App\Http\Controllers\api\adsController@updateOptions');
        Route::post('items/getAdOption', 'App\Http\Controllers\api\adsController@getAdOption');
        Route::post('items/loadDeptsByCity', [adsController::class, 'loadDeptsByCity'])->name('api.adspage.loadDeptsByCity');
        Route::post('items/SyncAdsKeywordsByCategory', 'App\Http\Controllers\api\adsController@SyncAdsKeywordsByCategory');
        Route::post('items/SyncAdsKeywords', 'App\Http\Controllers\api\adsController@SyncAdsKeywords');

        //options apis //posted
        Route::post('options/getAll', 'App\Http\Controllers\api\options@getAllOptions');

        //User Balance api
        Route::get('UserCoins/getAll', 'App\Http\Controllers\api\UserCoins@getUserCoins');

        //emails apis
        Route::post('emails/filter', 'App\Http\Controllers\api\emails@filter');

        //banners apis
        Route::post('banners/filter', 'App\Http\Controllers\api\bannersController@filter');
        Route::post('banners/edit', 'App\Http\Controllers\api\bannersController@edit');

        //settings apis
        Route::post('actions/filter', 'App\Http\Controllers\api\PrivilagesController@filterActions');
        Route::post('privillege/filter', 'App\Http\Controllers\api\PrivilagesController@filterRoles');
        Route::post('internUsers/filter', 'App\Http\Controllers\api\PrivilagesController@filterUsers');
        Route::post('internuser/create', 'App\Http\Controllers\api\PrivilagesController@createUsers');
        Route::post('internuser/update', 'App\Http\Controllers\api\PrivilagesController@updateUsers');
        Route::post('privillege/GetPermmissionsByRole', 'App\Http\Controllers\api\PrivilagesController@GetPermmissionsByRole');
        Route::post('privillege/addPermissions', 'App\Http\Controllers\api\PrivilagesController@addPermissions');
        Route::post('typeactions/filter', 'App\Http\Controllers\api\PrivilagesController@filterTypeActions');
        Route::get('settings/seo', 'App\Http\Controllers\api\SettingController@getSeo');
        Route::post('settings/seo', 'App\Http\Controllers\api\SettingController@postSeo');

        // plans apis
        Route::post('plans/filter', 'App\Http\Controllers\api\PlanController@filter');
        Route::post('plans/create', 'App\Http\Controllers\api\PlanController@createPlan');
        Route::post('plans/update', 'App\Http\Controllers\api\PlanController@updatePlan');
        Route::post('plans/delete', 'App\Http\Controllers\api\PlanController@deletePlan');

        // cats apis
        Route::post('cats/filter', 'App\Http\Controllers\api\CatsController@filter');
        Route::post('cats/getParents', 'App\Http\Controllers\api\CatsController@getParents');
        Route::post('cats/create', 'App\Http\Controllers\api\CatsController@create');
        Route::post('cats/update', 'App\Http\Controllers\api\CatsController@update');
        Route::post('cats/delete', 'App\Http\Controllers\api\CatsController@destroy');

        // types apis
        Route::post('types/filter', 'App\Http\Controllers\api\TypesController@filter');
        Route::post('types/create', 'App\Http\Controllers\api\TypesController@create');
        Route::post('types/update', 'App\Http\Controllers\api\TypesController@update');

        // standings apis
        Route::post('standings/filter', 'App\Http\Controllers\api\standingsController@filter');
        Route::post('standings/create', 'App\Http\Controllers\api\standingsController@createStanding');
        Route::post('standings/update', 'App\Http\Controllers\api\standingsController@updateStanding');
        Route::post('standings/delete', 'App\Http\Controllers\api\standingsController@deleteStanding');

        // options apis
        Route::post('options/filter', 'App\Http\Controllers\api\optionsController@filter');
        Route::post('options/getTypes', 'App\Http\Controllers\api\optionsController@getTypes');
        Route::post('options/create', 'App\Http\Controllers\api\optionsController@createOption');
        Route::post('options/update', 'App\Http\Controllers\api\optionsController@updateOption');
        Route::post('options/delete', 'App\Http\Controllers\api\optionsController@deleteOption');

        //options type apis
        Route::post('optionstype/filter', 'App\Http\Controllers\api\OptionTypesController@filter');
        Route::post('optionstype/update', 'App\Http\Controllers\api\OptionTypesController@updateOptionTypeDescription');


        // transactions apis
        Route::post('transactions/filter', 'App\Http\Controllers\api\TransactionController@filter');
        Route::post('transactions/create', 'App\Http\Controllers\api\TransactionController@createNewTransaction');

        // emails apis
        Route::post('emails/filter', 'App\Http\Controllers\api\EmailController@filter');
        Route::post('emails/filterByUser', 'App\Http\Controllers\api\EmailController@filterByUser');
        Route::post('email/create', 'App\Http\Controllers\api\EmailController@create');
        Route::post('email/confirm/{id}', 'App\Http\Controllers\api\EmailController@confirmAdsEmail');
        Route::post('email/cancel/{id}', 'App\Http\Controllers\api\EmailController@cancelEmail');

        // messages apis
        Route::post('messages/filter', 'App\Http\Controllers\api\MessageController@filter');

        //life cycle apis
        Route::post('cycle/data', 'App\Http\Controllers\api\lifeCycleController@getData');
        Route::post('cycle/save', 'App\Http\Controllers\api\lifeCycleController@save');

        //regions apis
        Route::post('regions/filter', 'App\Http\Controllers\api\localisationController@regionFilter');
        Route::post('region/update', 'App\Http\Controllers\api\localisationController@updateRegion');
        Route::post('region/create', 'App\Http\Controllers\api\localisationController@createRegion');
        Route::post('region/delete', 'App\Http\Controllers\api\localisationController@deleteRegion');
        Route::post('region/getCountries', 'App\Http\Controllers\api\localisationController@getCountries');
        Route::post('province/getRegions', 'App\Http\Controllers\api\localisationController@getRegions');

        //Route::post('region/createAll', 'App\Http\Controllers\api\regions@create');

        //provinces apis
        Route::post('provinces/filter', 'App\Http\Controllers\api\localisationController@provinceFilter');
        Route::post('province/update', 'App\Http\Controllers\api\localisationController@updateProvince');
        Route::post('province/create', 'App\Http\Controllers\api\localisationController@createProvince');
        Route::post('province/delete', 'App\Http\Controllers\api\localisationController@deleteProvince');
        Route::post('province/getProvinces', 'App\Http\Controllers\api\localisationController@getProvinces');

        //cities apis
        Route::post('cities/filter', 'App\Http\Controllers\api\localisationController@cityFilter');
        Route::post('city/update', 'App\Http\Controllers\api\localisationController@updateCity');
        Route::post('city/create', 'App\Http\Controllers\api\localisationController@createCity');
        Route::post('city/delete', 'App\Http\Controllers\api\localisationController@deleteCity');
        Route::post('city/getCities', 'App\Http\Controllers\api\localisationController@getCities');
        Route::post('city/allCities', 'App\Http\Controllers\api\localisationController@getAllCities');

        //neighborhoods apis
        Route::post('neighborhoods/filter', 'App\Http\Controllers\api\localisationController@neighborhoodFilter');
        Route::post('neighborhood/update', 'App\Http\Controllers\api\localisationController@updateNeighborhood');
        Route::post('neighborhood/create', 'App\Http\Controllers\api\localisationController@createNeighborhood');
        Route::post('neighborhood/delete', 'App\Http\Controllers\api\localisationController@deleteNeighborhood');


        //links apis
        Route::post('links/data', 'App\Http\Controllers\api\linksController@getData');
        Route::post('links/save', 'App\Http\Controllers\api\linksController@save');

        //dashboard apis
        //Counts
        Route::post('dashboard/getTotalAds', 'App\Http\Controllers\api\DashboardController@getTotalAds');
        Route::post('dashboard/getTotalPublishedAds', 'App\Http\Controllers\api\DashboardController@getTotalPublishedAds');
        Route::post('dashboard/getTotalInReviewAds', 'App\Http\Controllers\api\DashboardController@getTotalInReviewAds');
        Route::post('dashboard/getAdsByUnivers', 'App\Http\Controllers\api\DashboardController@getAdsByUnivers');
        Route::post('dashboard/getAdsByUser', 'App\Http\Controllers\api\DashboardController@getAdsByUser');
        Route::post('dashboard/getTotalUsers', 'App\Http\Controllers\api\DashboardController@getTotalUsers');
        //Count Graph
        Route::post('dashboard/getTotalAdsByMonths', 'App\Http\Controllers\api\DashboardController@getTotalAdsByMonths');
        //clients
        Route::post('dashboard/clientsFilter', 'App\Http\Controllers\api\DashboardController@ClientsFilter');

        // developer apis
        // get file
        Route::get('staticfiles', 'App\Http\Controllers\api\EditFilesController@getFile');
        Route::post('staticfiles', 'App\Http\Controllers\api\EditFilesController@postFile');
        Route::post('staticfiles/rollback', 'App\Http\Controllers\api\EditFilesController@rollbackFile');

        // role management start
        Route::get('/roles', [RolesController::class, 'getRoles'])->name('roles');
        Route::get('/role/{id}', [RolesController::class, 'getRoleById'])->name('getRole');

        // role management end

        // permission management start
        Route::get('/permissions', [PermissionsController::class, 'getPermissions'])->name('permissions');
        Route::get('/user/{id}/permissions', [PermissionsController::class, 'getPermissions'])->name('userPermissions');
        Route::delete('/deletePermission/{id}', [PermissionsController::class, 'destroy'])->name('deletePermission');
        Route::get('/permission/{id}', [PermissionsController::class, 'getPermissionById'])->name('getPermission');
        Route::put('/permission/update/{id}', [PermissionsController::class, 'update']);
        Route::post('/createPermission', [PermissionsController::class, 'createPermission']);


        // givePermission
        Route::post('/givePermission/{id}', [PermissionsController::class, 'givePermission']);

        Route::post('/givePermission/{id}', [PermissionsController::class, 'givePermission']);

        // permission management end

        // logs

        Route::post("/logs/filter", [LogRegistryController::class, "filter"]);
        Route::get("/currentUserActivities", [LogRegistryController::class, "CurrentUserLogActivities"]);
        Route::get("/getLastWeekLogs", [LogRegistryController::class, "lastWeekLogs"]);
        Route::get("/getLastMonthLogs", [LogRegistryController::class, "LastMonthLogs"]);
        Route::get("/logActivitiesByUid/{uid}", [LogRegistryController::class, "logActivitiesByUid"]);
        Route::get("/logActivities", [LogRegistryController::class, "logActivity"]);

        //  Announce Notifications

        Route::post('notifications/filter', [NotificationController::class, 'filter']);
        Route::get('/send-test-notification', [NotificationController::class, 'sendAnnounceNotification']);
        Route::get('/send-test-notification2', [NotificationController::class, 'sendTransactionNotification']);
        Route::get('/getUnreadNotifications', [NotificationController::class, 'getUnreadNotifications']);
        Route::get('/getNotifications', [NotificationController::class, 'getNotifications']);
        Route::get('/getAllNotifications', [NotificationController::class, 'getAllNotifications']);
        Route::post('/makeNotificationsAsRead', [NotificationController::class, 'makeAllAsRead']);
        Route::get('/clearAllNotifications', [NotificationController::class, 'clearAll']);
        Route::get('/sendExpiredAdNotif', [App\Helpers\ExpiredAds::class, 'sendExpiredAdNotif']);
        Route::get('/sendAdWillExpiredAdNotif', [App\Helpers\ExpiredAds::class, 'sendAdWillExpiredAdNotif']);
    });


    // Listing page apis
    // Listing Ads
    Route::get("/spotlightAds", 'App\Http\Controllers\api\ListingController@getSpotlightAds');
    Route::get("/listingAds", 'App\Http\Controllers\api\ListingController@getListingAds');
    Route::get('city/getCityById', 'App\Http\Controllers\api\localisationController@getCityById');
    Route::get('neighborhood/getNeighborhoodById', 'App\Http\Controllers\api\localisationController@getNeighborhoodById');
    Route::get('city/getCityCoordinatesById', 'App\Http\Controllers\api\localisationController@getCityCoordinatesById');
    Route::get('/getCategoryById', 'App\Http\Controllers\api\ListingController@getCategoryById');
    Route::get('/mapPopupAdById', 'App\Http\Controllers\api\ListingController@mapPopupAdById');
    Route::get('/listingProjects', 'App\Http\Controllers\api\ListingController@listingProjects');
    Route::get('/mapPointsProjects', 'App\Http\Controllers\api\ListingController@mapPointsProjects');
    Route::get('/mapPointsAds', 'App\Http\Controllers\api\ListingController@mapPointsAds');

    // Favoris Ads
    Route::get('/getFavorisAds', 'App\Http\Controllers\api\favorisController@getFavorisAds');

    // mydashboard page
    Route::get("/dashboardInfos", 'App\Http\Controllers\api\MyDashboardController@getGeneralInfos');
    Route::get("/dashboardStatistics", 'App\Http\Controllers\api\MyDashboardController@getStatistics');
    Route::post("/dashboardViews", 'App\Http\Controllers\api\MyDashboardController@getViews');
    Route::post("/dashboardWtsps", 'App\Http\Controllers\api\MyDashboardController@getWtsps');
    Route::post("/dashboardPhones", 'App\Http\Controllers\api\MyDashboardController@getPhones');
    Route::post("/dashboardEmails", 'App\Http\Controllers\api\MyDashboardController@getEmails');
    Route::get("/dashboardLatestEmails", 'App\Http\Controllers\api\MyDashboardController@getLatestEmails');
    Route::post('email/createAdsEmail', 'App\Http\Controllers\api\EmailController@createAdsEmail');
    Route::post("email/reportAbus", [EmailController::class, "reportAbus"]);
    Route::get('/profileUser/{id}',[MyDashboardController::class,'getProfileInfos']);


    // myads page
    Route::get("/myAds", 'App\Http\Controllers\api\MyAdsController@myAds');
    Route::post("/boostAd", 'App\Http\Controllers\api\MyAdsController@boostAd');
    Route::post("/updateAd", 'App\Http\Controllers\api\MyAdsController@updateAd');
    Route::post("/deleteAd", 'App\Http\Controllers\api\MyAdsController@deleteAd');

    // myemails page
    Route::post("/myemails/filter", 'App\Http\Controllers\api\MyEmailsController@filter');

    // mybookings page
    Route::post("/booking/filter", 'App\Http\Controllers\api\ReservationController@filter');
    Route::post("/changeBookingStatus", 'App\Http\Controllers\api\ReservationController@changeBookingStatus');

    // profile page
    Route::get("/profileAds", 'App\Http\Controllers\api\profileController@profileAds');
    Route::get("/profileProjects", 'App\Http\Controllers\api\profileController@profileProjects');
    Route::get("/profileProjectDispos", 'App\Http\Controllers\api\profileController@profileProjectDispos');
    Route::get("/profileData", 'App\Http\Controllers\api\profileController@profileData');
    Route::get("/editProfileData", 'App\Http\Controllers\api\profileController@editProfileData');
    Route::post("/editProfile", 'App\Http\Controllers\api\profileController@editProfile');
    Route::post("/updatePassword", 'App\Http\Controllers\api\profileController@updatePassword');
    //item page apis
    Route::get("/getItem", 'App\Http\Controllers\api\ItemController@getItem');
    Route::post("/addClick", 'App\Http\Controllers\api\ItemController@addClick');
    Route::post("/similarItems", 'App\Http\Controllers\api\ItemController@similarItems');
    Route::get("/disposItems", 'App\Http\Controllers\api\ItemController@disposItems');
    Route::get("/tags", 'App\Http\Controllers\api\ItemController@tags');
    Route::get("/getAdCalendar", 'App\Http\Controllers\api\ReservationController@getAdCalendar');
    Route::post("/addBooking", 'App\Http\Controllers\api\ReservationController@addBooking');

    //Add ad page apis
    Route::get("/getUserContacts", 'App\Http\Controllers\api\AddAdController@getUserContacts');
    Route::post("/addAd", 'App\Http\Controllers\api\AddAdController@addAd');
    Route::post('/loadDeptsByCity', 'App\Http\Controllers\api\AddAdController@loadDeptsByCity')->name('api.loadDeptsByCity');
    Route::post('/loadDeptCoordinates', 'App\Http\Controllers\api\AddAdController@loadDeptCoordinates')->name('api.loadDeptCoordinates');

    //home page apis
    Route::get("/getStoriesAds", 'App\Http\Controllers\api\HomeController@getStoriesAds'); //univer(optional) - count
    Route::get("/getProjectsAds", 'App\Http\Controllers\api\HomeController@getProjectsAds');
    Route::get("/getRegions", 'App\Http\Controllers\api\HomeController@getRegions');  //univer(optional) - count

    Route::post("/changelang", 'App\Http\Controllers\api\langController@changelang');

    // multilist filter form
    Route::get('/multilistfilterfrom', [MultilistFilterFromController::class, 'get']);
    Route::get('/multilistfilterfrom/neighborhoodsbycity', [MultilistFilterFromController::class, 'neighborhoodsbycity']);


    // multilist filter form
    Route::get('/multilistfilterfrom', [MultilistFilterFromController::class, 'get']);
    Route::get('/multilistfilterfrom/neighborhoodsbycity', [MultilistFilterFromController::class, 'neighborhoodsbycity']);


    // multilist filter form
    Route::get('/multilistfilterfrom', [MultilistFilterFromController::class, 'get']);
    Route::get('/multilistfilterfrom/neighborhoodsbycity', [MultilistFilterFromController::class, 'neighborhoodsbycity']);


    // estimation
    Route::get('city/getCities', 'App\Http\Controllers\api\localisationController@getCities');
    Route::get('city/allCities', 'App\Http\Controllers\api\localisationController@getAllCities');
    Route::get('regions', 'App\Http\Controllers\api\localisationController@dataRegion');
});

// get all properties type
Route::get("/propertiesType", [HomeController::class, 'getPropertiesType']);
Route::get("/allStanding", [HomeController::class, 'getAllStanding']);
Route::get("/allAds", [HomeController::class, 'getAllAds']);
Route::get("/expiredAds", [HomeController::class, 'getExpiredAds']);
Route::get("/categories", [HomeController::class, 'getCategories']);
Route::get("/categoriesType", [HomeController::class, 'getCategoriesType']);
Route::get("/categoriesByType/{type}", [HomeController::class, 'getCategoriesByType']);
Route::post("/categories", [HomeController::class, 'categories']);
Route::get("/spotlightAds", [ListingController::class, 'getSpotlightAds']);
Route::get("/listingAds", [ListingController::class, 'getAdsListingPagination']);
// getMediaOrder by order
Route::get("/getMediaByOrder", [HomeController::class, 'getMediaByOrder']);

//Route::get("/getAds", [scrapping::class, 'getAds']);
//Route::get("/refSeeder", [scrapping::class, 'refSeeder']);
//Route::get("/coinsSeeder", [scrapping::class, 'coinsSeeder']);
//Route::get("/mailSeeder", [scrapping::class, 'mailSeeder']);
//Route::get("/getUsers", [scrapping::class, 'getUsers']);
//Route::get("/getContracts", [scrapping::class, 'getContracts']);

// Multilist .me api

// // allAnonces
// Route::get('/MobileAds', [MobileController::class, 'getAnnonceStories']);

// // get BookList annonces
// Route::get('/getBooklistAds', [MobileController::class, 'getBookList']);

// // get HomeList annonces
// Route::get('/getHomelistAds', [MobileController::class, 'getHomeList']);

// // get Office annonces
// Route::get('/getOfficelistAds', [MobileController::class, 'getOfficeList']);

// // get BookList annonces
// Route::get('/getLandlistAds', [MobileController::class, 'getLandList']);

// // get PrimeList annonces
// Route::get('/getPrimelistAds', [MobileController::class, 'getPrimeList']);

// // get project annonces
// Route::get('/getProjects', [MobileController::class, 'getProjects']);

// // create new annonce
// Route::post('/createAnnonce', [MobileController::class, 'createAnnonce']);

Route::get("/links", [SettingController::class, 'getLinks']);

Route::group(['prefix' => "v3", 'middleware' => ['cors', 'json.response']], function () {

    Route::get('/register', [AuthController::class, 'userRegister']);
    Route::post('/ads/category', [AdsControllerHandler::class, 'getAdsByCategory']);
    // Route::get('categories',[CategoryController::class,'index']);
});



Route::get('/ads', [adsController::class, 'getData']);


Route::get('/flux/importRssFeed', 'App\Http\Controllers\flux\fluxController@importRssFeed');


// Route::group(['middleware' => ['auth']], function () {
//     Route::post('/loginSwitcher',[TestController::class,'loginSwitcher']);

// });

Route::post('promo-test', 'App\Http\Controllers\api\TestController@promoMail');
