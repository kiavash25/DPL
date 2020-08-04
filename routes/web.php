<?php

/*
|--------------------------------------------------------------------------
| Web Routesh
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Symfony\Component\HttpFoundation\Request;

Route::get('locale/{locale}', function ($locale){
    Session::put('locale', $locale);
    return redirect()->back();
});

Auth::routes();

Route::middleware(['web'])->group(function () {
    Route::get('/', 'MainController@mainPage');

    Route::post('/subscribe/store', 'MainController@subscribeStore')->name('subscribe.store');

    Route::get('welcome/{local?}', 'MainController@mainPage');

    Route::post('beforeList', 'MainController@beforeList')->name('beforeList');

    Route::get('list/{kind}/{value1}', 'MainController@list')->name('show.list');

    Route::post('getListElems', 'MainController@getListElems')->name('getListElems');

    Route::get('activity/{slug}', 'MainController@showActivity')->name('show.activity');

    Route::get('destination/{slug}', 'MainController@showDestination')->name('show.destination');

    Route::get('package/{slug}', 'MainController@showPackage')->name('show.package');

    Route::get('category/{slug}', 'MainController@showCategory')->name('show.category');

    Route::post('findDestination', 'AjaxController@findDestination')->name('findDestination');

    Route::post('search', 'AjaxController@search')->name('search');

    Route::get('journal', 'JournalController@mainPageJournal')->name('journal.index');

    Route::get('journal/show/{id}/{slug?}', 'JournalController@showJournalContent')->name('journal.show');

    Route::get('journal/list/{kind}/{value?}', 'JournalController@listJournal')->name('journal.list');

    Route::post('journal/getListElemes', 'JournalController@getElems')->name('journal.getElems');

    Route::middleware(['web', 'forumPages'])->group(function(){
        Route::get('topicalDiscussion', 'ForumController@forumIndex')->name('forum.index');
        Route::get('topicalDiscussion/category/{categoryId}', 'ForumController@forumCategoryList')->name('forum.category.list');
        Route::get('topicalDiscussion/topic/{topicId}', 'ForumController@forumTopic')->name('forum.topic.show');

        Route::middleware(['auth'])->group(function(){
            Route::get('topicalDiscussion/newTopic', 'ForumController@forumNewTopic')->name('forum.newTopic');
            Route::post('topicalDiscussion/store', 'ForumController@storeNewTopic')->name('forum.storeTopic');

            Route::post('topicalDiscussion/storeTopicAns', 'ForumController@storeTopicAns')->name('forum.storeTopicAns');
            Route::post('topicalDiscussion/setBestAnswerTopic', 'ForumController@setBestAnswerTopic')->name('forum.setBestAnswerTopic');
            Route::post('topicalDiscussion/likeForum', 'ForumController@likeForum')->name('forum.likeForum');

            Route::post('topicalDiscussion/deleteTopic', 'ForumController@deleteTopic')->name('forum.deleteTopic');
            Route::post('topicalDiscussion/deleteReply', 'ForumController@deleteReply')->name('forum.deleteReply');
        });
    });


    Route::middleware(['guest'])->group(function(){
        Route::get('loginPage', 'UserController@loginRegisterPage')->name('loginPage');

        Route::post('forgetPassword', 'UserController@forgetPassword')->name('forgetPassword');
    });

    Route::middleware(['auth'])->group(function (){
        Route::get('admin/locale/{locale}', function ($locale){
            $user = auth()->user();
            $acl = \App\models\Acl::where('userId', $user->id)->where('role', 'language')->where('value', $locale)->first();
            if($user->level == 'superAdmin' || $acl != null || $user->level == 'user')
                \Session::put('locale', $locale);
            return redirect()->back();
        });

        Route::get('profile', 'UserController@profile')->name('profile');

        Route::get('profile/setting', 'UserController@profileSetting')->name('profile.setting');

        Route::post('profile/setting/uploadProfilePic', 'UserController@uploadProfilePic')->name('profile.setting.uploadProfilePic');

        Route::post('profile/setting/updateProfileInfo', 'UserController@updateProfileInfo')->name('profile.setting.updateProfileInfo');

        Route::post('profile/setting/updateProfilePassword', 'UserController@updateProfilePassword')->name('profile.setting.updateProfilePassword');

        Route::middleware(['acl:admin'])->group(function (){

            Route::get('/admin', 'AdminController@adminIndex')->name('admin.index');

            Route::middleware(['auth', 'web', 'acl:activity'])->group(function () {
                Route::get('/admin/activity/list', 'ActivityController@listActivity')->name('admin.activity.list');
                Route::get('/admin/activity/new', 'ActivityController@newActivity')->name('admin.activity.new');
                Route::get('/admin/activity/edit/{id}', 'ActivityController@editActivity')->name('admin.activity.edit');
                Route::get('/admin/activity/description/{id}', 'ActivityController@descriptionActivity')->name('admin.activity.description');
                Route::post('/admin/activity/store', 'ActivityController@storeActivity')->name('admin.activity.store');
                Route::post('/admin/activity/storeImg', 'ActivityController@storeImgActivity')->name('admin.activity.storeImg');
                Route::post('/admin/activity/storeTitle', 'ActivityController@storeTitleActivity')->name('admin.activity.storeTitle');
                Route::post('/admin/activity/storeTitleTextImg', 'ActivityController@storeTitleTextImgActivity')->name('admin.activity.storeTitleTextImg');
                Route::post('/admin/activity/storeTitleText', 'ActivityController@storeTitleTextActivity')->name('admin.activity.storeTitleText');
                Route::post('/admin/activity/storeVideoAudio', 'ActivityController@storeVideoAudioActivity')->name('admin.activity.storeVideoAudio');
                Route::post('/admin/activity/deleteImg', 'ActivityController@deleteImgActivity')->name('admin.activity.deleteImg');
                Route::post('/admin/activity/deleteTitle', 'ActivityController@deleteTitleActivity')->name('admin.activity.deleteTitle');
                Route::post('/admin/activity/delete', 'ActivityController@deleteActivity')->name('admin.activity.delete');
                Route::post('/admin/activity/check', 'ActivityController@checkActivity')->name('admin.activity.check');
            });

            Route::middleware(['auth', 'web', 'acl:destination'])->group(function () {
                Route::get('/admin/destination/category/list', 'DestCategoryController@listCategory')->name('admin.destination.category.index');
                Route::get('/admin/destination/category/new', 'DestCategoryController@newCategory')->name('admin.destination.category.new');
                Route::get('/admin/destination/category/edit/{id}', 'DestCategoryController@editCategory')->name('admin.destination.category.edit');
                Route::post('/admin/destination/category/store', 'DestCategoryController@storeCategory')->name('admin.destination.category.store');
                Route::post('/admin/destination/category/storeImg', 'DestCategoryController@storeImgCategory')->name('admin.destination.category.storeImg');
                Route::post('/admin/destination/category/deleteImg', 'DestCategoryController@deleteImgCategory')->name('admin.destination.category.deleteImg');
                Route::post('/admin/destination/category/storeVideoAudio', 'DestCategoryController@storeVideoAudioCategory')->name('admin.destination.category.storeVideoAudio');
                Route::post('/admin/destination/category/delete', 'DestCategoryController@deleteCategory')->name('admin.destination.category.delete');
                Route::post('/admin/destination/category/check', 'DestCategoryController@checkCategoryDestination')->name('admin.destination.category.check');
                Route::post('/admin/category/title/store', 'DestCategoryController@storeCategoryTitle')->name('admin.destination.category.title.store');
                Route::post('/admin/category/title/delete', 'DestCategoryController@deleteCategoryTitle')->name('admin.destination.category.title.delete');

                Route::get('/admin/destination/list', 'DestinationController@listDestination')->name('admin.destination.list');
                Route::get('/admin/destination/new', 'DestinationController@newDestination')->name('admin.destination.new');
                Route::get('/admin/destination/edit/{id}', 'DestinationController@editDestination')->name('admin.destination.edit');
                Route::get('/admin/destination/description/{id}', 'DestinationController@descriptionDestination')->name('admin.destination.description');
                Route::post('/admin/destination/store', 'DestinationController@storeDestination')->name('admin.destination.store');
                Route::post('/admin/destination/storeImg', 'DestinationController@storeImgDestination')->name('admin.destination.storeImg');
                Route::post('/admin/destination/storeDescriptionImg', 'DestinationController@storeDescriptionImgDestination')->name('admin.destination.storeDescriptionImg');
                Route::post('/admin/destination/storeDescription', 'DestinationController@storeDescriptionDestination')->name('admin.destination.storeDescription');
                Route::post('/admin/destination/storeVideoAudio', 'DestinationController@storeVideoAudioDestination')->name('admin.destination.storeVideoAudio');
                Route::post('/admin/destination/deleteImg', 'DestinationController@deleteImgDestination')->name('admin.destination.deleteImg');
                Route::post('/admin/destination/delete', 'DestinationController@deleteDestination')->name('admin.destination.delete');
                Route::post('/admin/destination/check', 'DestinationController@checkDestination')->name('admin.destination.check');
            });

            Route::middleware(['auth', 'web', 'acl:package'])->group(function () {
                Route::get('/admin/package/list', 'PackageController@listPackage')->name('admin.package.list');
                Route::get('/admin/package/moreInfoTitle', 'PackageController@moreInfoTitlePackage')->name('admin.package.moreInfoTitle');
                Route::get('/admin/package/new', 'PackageController@newPackage')->name('admin.package.new');
                Route::get('/admin/package/edit/{id}', 'PackageController@editPackage')->name('admin.package.edit');
                Route::get('/admin/package/moreInfo/text/{id}', 'PackageController@moreInfoText')->name('admin.package.moreInfoText');
                Route::post('/admin/package/moreInfoText/store', 'PackageController@storeMoreInfoTextPackage')->name('admin.package.moreInfoText.store');
                Route::post('/admin/package/moreInfoText/storeImg', 'PackageController@storeImgMoreInfoTextPackage')->name('admin.package.moreInfoText.storeImg');
                Route::post('/admin/package/moreInfoTitle/store', 'PackageController@storeMoreInfoTitlePackage')->name('admin.package.moreInfoTitle.store');
                Route::post('/admin/package/moreInfoTitle/delete', 'PackageController@deleteMoreInfoTitlePackage')->name('admin.package.moreInfoTitle.delete');
                Route::post('/admin/package/store', 'PackageController@storePackage')->name('admin.package.store');
                Route::post('/admin/package/storeImg', 'PackageController@storeImgPackage')->name('admin.package.storeImg');
                Route::post('/admin/package/storeSideInfo', 'PackageController@storeSideInfo')->name('admin.package.storeSideInfo');
                Route::post('/admin/package/storeVideoAudio', 'PackageController@storeVideoAudioPackage')->name('admin.package.storeVideoAudio');
                Route::post('/admin/package/findTag', 'PackageController@findTagPackage')->name('admin.package.findTag');
                Route::post('/admin/package/deleteSideInfo', 'PackageController@deleteSideInfo')->name('admin.package.deleteSideInfo');
                Route::post('/admin/package/deleteImg', 'PackageController@deleteImgPackage')->name('admin.package.deleteImg');
                Route::post('/admin/package/delete', 'PackageController@deletePackage')->name('admin.package.delete');

                Route::post('/admin/package/popular/store', 'PackageController@popularPackageStore')->name('admin.package.popularStore');
            });

            Route::middleware(['auth', 'web', 'acl:journal'])->group(function () {
                Route::get('/admin/journal/category/list', 'JournalAdminController@indexCategory')->name('admin.journal.category.index');
                Route::post('/admin/journal/category/store', 'JournalAdminController@storeCategory')->name('admin.journal.category.store');
                Route::post('/admin/journal/category/delete', 'JournalAdminController@deleteCategory')->name('admin.journal.category.delete');
                Route::get('/admin/journal/list', 'JournalAdminController@indexJournal')->name('admin.journal.list');
                Route::get('/admin/journal/new', 'JournalAdminController@newJournal')->name('admin.journal.new');
                Route::get('/admin/journal/edit/{id}', 'JournalAdminController@editJournal')->name('admin.journal.edit');
                Route::post('/admin/journal/store/', 'JournalAdminController@storeJournal')->name('admin.journal.store');
                Route::post('/admin/journal/delete', 'JournalAdminController@deleteJournal')->name('admin.journal.delete');
                Route::post('/admin/journal/storeDescriptionImg', 'JournalAdminController@storeDescriptionImgJournal')->name('admin.journal.storeDescriptionImg');
                Route::post('/admin/journal/checkSeo', 'JournalAdminController@checkSeo')->name('admin.journal.checkSeo');
            });

            Route::middleware(['auth', 'web', 'acl:setting'])->group(function () {
                Route::get('/admin/setting/mainPage', 'SettingController@mainPage')->name('admin.setting.mainPage');
                Route::post('/admin/setting/mainPage/storeAboutUsPic', 'SettingController@storeAboutUsPic')->name('admin.setting.storeAboutUsPic');
                Route::post('/admin/setting/mainPage/storeAboutUs', 'SettingController@storeAboutUs')->name('admin.setting.storeAboutUs');
                Route::post('/admin/setting/mainPage/storeCenterHeaderPic', 'SettingController@storeCenterHeaderPic')->name('admin.setting.storeCenterHeaderPic');
                Route::post('/admin/setting/mainPage/deleteCenterHeaderPic', 'SettingController@deleteCenterHeaderPic')->name('admin.setting.deleteCenterHeaderPic');

                Route::post('/admin/setting/mainPage/supportUs/store', 'SettingController@storeSupportUs')->name('admin.setting.supportUs.store');
                Route::post('/admin/setting/mainPage/supportUs/delete', 'SettingController@deleteSupportUs')->name('admin.setting.supportUs.delete');

                Route::get('/admin/setting/mainPageSlider', 'SettingController@mainPageSlider')->name('admin.setting.mainPageSlider');
                Route::post('/admin/setting/mainPageSliderStore', 'SettingController@mainPageSliderStore')->name('admin.setting.mainPageSliderStore');
                Route::post('/admin/setting/mainPageSliderChangeNumber', 'SettingController@mainPageSliderChangeNumber')->name('admin.setting.mainPageSliderChangeNumber');
                Route::post('/admin/setting/mainPageSlider/delete', 'SettingController@mainPageSliderDelete')->name('admin.setting.mainPageSlider.delete');

                Route::get('/admin/setting/award', 'SettingController@awardSetting')->name('admin.setting.awards');
                Route::post('/admin/setting/awardStore', 'SettingController@awardStore')->name('admin.setting.awardStore');
                Route::post('/admin/setting/award/delete', 'SettingController@awardDelete')->name('admin.setting.award.delete');

                Route::get('/admin/setting/lang/index', 'SettingController@languagePage')->name('admin.setting.lang');
                Route::post('/admin/setting/lang/store', 'SettingController@storeLanguage')->name('admin.setting.lang.store');
                Route::post('/admin/setting/lang/delete', 'SettingController@deleteLanguage')->name('admin.setting.lang.delete');
            });

            Route::middleware(['auth', 'web', 'acl:userAccess'])->group(function () {
                Route::get('/admin/forum/categories', 'ForumController@adminForumCategory')->name('admin.forum.category');
                Route::post('/admin/forum/category/store', 'ForumController@storeForumCategory')->name('admin.forum.category.store');
                Route::post('/admin/forum/category/delete', 'ForumController@deleteForumCategory')->name('admin.forum.category.delete');

                Route::get('/admin/userAccess/list', 'UserAccessController@list')->name('admin.userAccess.list');
                Route::post('/admin/userAccess/acl/store', 'UserAccessController@aclStore')->name('admin.userAccess.acl.store');
                Route::post('/admin/userAccess/language/store', 'UserAccessController@languageStore')->name('admin.userAccess.language.store');
                Route::post('/admin/userAccess/acl/disableAccess', 'UserAccessController@disableAccess')->name('admin.userAccess.acl.disableAccess');
            });

            Route::post('/admin/addCity', 'AdminController@addCity')->name('admin.addCity');
        });

    });
});


Route::post('findCity', 'AjaxController@findCity')->name('findCity');
Route::post('findTag', 'AjaxController@findTag')->name('findTag');

