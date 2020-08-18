<?php

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
Auth::routes();

Route::get('/articles/{post}/{slug?}', 'PostController@single')->name('post.single');

Route::view('/profil', 'user.edit')->name('user.edit');
Route::patch('/profil', 'UserController@update')->name('user.update');
Route::get('/user/steamid', 'UserController@getSteamId');
Route::get('/user/discordid', 'UserController@getDiscordId');

Route::get('/admin/{any}', 'AdminController@index')->where('any', '.*');

Route::get('/', 'HomeController@index')->name('home');
Route::get('/nepeta', function () {
  return view('misc.nepeta');
})->name('misc.nepeta');

Route::get('/blog', 'PostController@all')->name('blog.index');

Route::get('/{post}/comments', 'CommentController@index');
Route::post('/{post}/comments', 'CommentController@store');

Route::get('/topics', 'TopicController@index');
Route::get('/topics/show/{topic}', 'TopicController@show')->name('topics.show');
Route::get('/topics/{category}/create', 'TopicController@create')->name('topics.create');
Route::post('/topics/{category}', 'TopicController@store')->name('topics.store');
Route::get('/topics/edit/{topic}', 'TopicController@edit')->name('topics.edit');
Route::delete('/topics/{topic}', 'TopicController@destroy')->name('topics.destroy');
Route::patch('/topics/{topic}', 'TopicController@update')->name('topics.update');

Route::post('/replies/{topic}', 'ReplyController@store')->name('replies.store');
Route::post('/commentReply/{reply}', 'ReplyController@storeCommentReply')->name('replies.storeReply');

Route::get('/showFromNotification/{topic}/{notification}', 'TopicController@showFromNotification')->name('topics.showFromNotification');

Route::get('/forum', 'TagController@list')->name('forum.index');
Route::get('/forum/{category}', 'CategoryController@list')->name('forum.category');

Route::get('/steam', 'Auth\SteamController@redirectToSteam')->name('auth.steam');
Route::get('/steam/handle', 'Auth\SteamController@handle')->name('auth.steam.handle');
Route::get('/discord', 'Auth\DiscordController@redirectToDiscord')->name('auth.discord');
Route::get('/discord/handle', 'Auth\DiscordController@handle')->name('auth.discord.handle');
Route::get('/discord/token', 'Auth\DiscordController@token')->name('auth.discord.token');

Route::get('/mentions-legales', function () {
  return view('misc.legal');
})->name('misc.legal');

Route::get('/videos', function () {
  return view('misc.videos');
})->name('misc.videos');

Route::group(['middleware' => ['App\Http\Middleware\IsAdmin']], function () {
  Route::get('/bank/overdraft', 'BankController@overdraft')->name('bank.overdraft');
  Route::post('/bank/transfer/getAccounts', 'BankController@getAccounts')->name('bank.getAccounts');
  Route::post('/bank/groups/getUsers', 'BankGroupController@getUsers')->name('bank.getUsers');
  Route::get('/bank/transfer', 'BankController@transfer')->name('bank.transfer');
  Route::post('/bank/transfer', 'BankController@exchange')->name('bank.exchange');
  Route::get('/bank/credits', 'CreditController@index')->name('bank.credits');
  Route::post('/bank/credits', 'CreditController@add')->name('bank.addCredit');
  Route::post('/bank/credits/pay', 'CreditController@pay')->name('bank.payCredit');
  Route::get('/bank/groups', 'BankGroupController@index')->name('bank.groupIndex');
  Route::post('/bank/groups', 'BankGroupController@store')->name('bank.groupStore');
  Route::get('/bank/groups/{group}', 'BankGroupController@show')->name('bank.groupShow');
  Route::delete('/bank/groups/{group}', 'BankGroupController@destroy')->name('bank.groupDestroy');
  Route::patch('/bank/groups/{group}', 'BankGroupController@update')->name('bank.groupUpdate');
  Route::post('/bank/groups/{group}', 'BankGroupController@quit')->name('bank.groupQuit');
  Route::post('/bank/groups/{group}/add', 'BankGroupController@add')->name('bank.groupAdd');
  Route::post('/bank/groups/{group}/manage', 'BankGroupController@manage')->name('bank.groupManage');
  Route::post('/bank/liquidation', 'BankController@liquidation')->name('bank.liquidation');
  Route::get('/bank/nova', 'BankController@nova')->name('bank.nova');
  Route::get('/bank/mails', 'BankController@mails')->name('bank.mails');
  Route::post('/bank/mails/read', 'BankController@readMail')->name('bank.readMail');
  Route::post('/bank/mails/store', 'BankController@storeMail')->name('bank.storeMail');
  Route::post('/bank/mails/refuse/{mail}', 'BankController@refuseMail')->name('bank.refuseMail');
  Route::post('/bank/mails/accept/{mail}', 'BankController@acceptMail')->name('bank.acceptMail');
  Route::get('/bank/mybank', function () {
    return view('bank.mybank');
  })->name('bank.myBank');
  Route::post('/bank/mybank/{bank}', 'BankController@changeBank')->name('bank.changeBank');
  Route::get('/bank/administration', 'BankController@administration')->name('bank.administration');
  Route::resource('bank', 'BankController');

  Route::get('/immobilier', 'ImmoController@index')->name('immo.index');
  Route::get('/immobilier/carte', 'ImmoController@map')->name('immo.map');
  Route::get('/immobilier/achat/{id}', 'ImmoController@achat')->middleware('auth', 'App\Http\Middleware\SteamId')->name('immo.achat');
  Route::post('/immobilier/store/{id}', 'ImmoController@store')->middleware('auth', 'App\Http\Middleware\SteamId')->name('immo.store');
  Route::post('/immobilier/sell/{id}', 'ImmoController@sell')->middleware('auth', 'App\Http\Middleware\SteamId')->name('immo.sell');

  Route::get('/livemap', function () {
    return view('livemap.show');
  })->name('livemap.show');

  Route::get('/accueil', 'HomeController@welcome')->name('misc.accueil');
});
Route::get('/livemap/server', 'LivemapController@server');
Route::get('/livemap/refresh', 'LivemapController@refresh');
Route::post('/livemap/store', 'LivemapController@store');
