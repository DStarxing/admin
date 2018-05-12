<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;
// index
Route::get('/', 'index/index/index');
Route::get('index', 'index/index/index');
Route::get('welcome','index/index/welcome');
Route::get('close','index/index/close');

// 用户类
Route::get('User/showBank/:id','index/User/showBank');
Route::post('User/list','index/User/showUser');
Route::get('User/list','index/User/showUser');
Route::get('addUser','index/User/addUserView');
Route::get('request','index/User/requestCash');
Route::get('requestR','index/User/requestRecharge');
Route::get('cashlist','index/User/cashList');
Route::post('/User/userAdd','index/User/userAdd');
Route::post('CoinArrive','index/User/CoinArrive');
Route::post('setUserCoin','index/User/setUserCoin');
Route::get('userlog','index/User/userLog');
Route::get('userEdit/:id','index/User/userEdit');
Route::post('userUpdate','index/User/userUpdate');
Route::post('userDel','index/User/userDelete');

// 注单类 
Route::get('betlist','index/lottery/showBet');
Route::post('deleteBet','index/lottery/deleteBet');
Route::get('data','index/lottery/showAllData');

// 银行卡类 包括平台收款卡 和用户卡
Route::get('showUserBank','index/Bank/showUserBank');
Route::get('bankEdit/:id','index/Bank/BankEdit');
Route::post('BankUpdate','index/Bank/BankUpdate');

//设置类
Route::get('System/index','index/System/index');
Route::get('System/news','index/System/newsView');
Route::get('System/previewNews','index/System/previewNews');
Route::get('System/bank','index/System/previewBank');
Route::get('System/bankEdit/:id','index/System/bankEdit');
Route::get('System/gameList','index/System/gameList');
Route::post('System/updateData','index/System/updateData');
Route::get('System/odds','index/System/showOdds');
Route::post('setOdds','index/System/setOdds');
Route::post('updateBank','index/System/updateBank');
Route::post('bankisDelete','index/System/setIsDelete');
Route::post('bankDelete','index/System/bankDelete');
Route::get('addBank','index/System/addBankV');
Route::post('addBankImg','index/System/addBankImg');
Route::post('addBankInfo','index/System/addBankInfo');

// 管理员类
Route::get('admin-list','index/Admin/adminList');
Route::post('isDelete','index/Admin/isDelete');
Route::get('addAdminV','index/Admin/addAdminV');
Route::post('addAdmin','index/Admin/addAdmin');
Route::get('updateAdminV/:id','index/Admin/updateAdminV');
Route::post('updateAdmin','index/Admin/updateAdmin');
Route::post('adminDelete','index/Admin/adminDelete');



// 登录类,
Route::get('login','index/AdminLogin/login');
Route::post('logined','index/AdminLogin/logined');
Route::get('logout','index/AdminLogin/logOut');




