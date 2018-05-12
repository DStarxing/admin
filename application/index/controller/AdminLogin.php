<?php
namespace app\index\controller;
use think\Controller;
use think\Request;
use think\Session;
use app\index\model\AdminModel as Ad;

class AdminLogin extends Controller {
	

	/**
	 * [viewLogin 展示登陆界面]
	 * @return [type] [description]
	 */
	public function login() {
		if(Session::has('username') && Session::has('password')) {
			// print_r($_SESSION);exit;
			return $this->fetch('index/index');
		}else{
			// print_r($_SESSION);exit;
			return $this->fetch('login');
		}
	}

	/**
	 * [logined 检查登录]
	 * @return [type] [description]
	 */
	public function logined(){
		$data = request()->post();
		$um = new Ad();
		$username = $data['username'];
		$password = md5($data['password']);
		$captcha = $data['captcha'];
		if(!captcha_check($captcha)){
			return '验证码错误';
		}
		if($username == '' || $password == '') {
			return '用户名或密码不能为空！';
		}
		$status = $um->where('username',$username)->where('password',$password)->find();
		if($status['start'] == 0){
			return '该管理员账号已被冻结！';
		}
		if($status) {
			$userid =  $status['id'];
			$username = $status['username'];
			$status['loginTime'] = time();
			Session::set('username',$username);
			Session::set('password',$password);
			$msg =  ['code'=>1,'msg'=>'success'];
			return 'yes';
		} else {
			return json_encode('用户名或密码错误i！');
		}
	}

	/**
	 * [loginOut 管理换退出]
	 * @return [type] [description]
	 */
	public function logOut(){
		session::delete('username');
		session::delete('password');
		return $this->redirect('/login');
	}
	

}
