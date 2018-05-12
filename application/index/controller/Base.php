<?php
namespace app\index\controller;
use think\Controller;
use think\Request;
use think\Session;
class Base extends Controller {
	
		public function _initialize() {
		if(Session::has('username') && Session::has('password')) {
			return ;
		} else {
			// return $this->error('请先登录！',url('/login'),30);
			return $this->redirect('/login');
		}
	}
}
