<?php
namespace app\index\controller;
use think\Controller;
use app\index\controller\Base; 
use app\index\model\UserModel as Us;
class Index extends Base {
	public function index() {
		// print_r($_SESSION);exit;

		return $this->fetch();
	}

	public function welcome(){
		$us = new Us();
		$userArr = $us->all();
		$daili = $us->where('agency',1)->select();
		$user  = $us->where('agency',0)->select();
		$coin = $us->count('coin');
		$this->assign('userNum',count($userArr));
		$this->assign('daili',count($daili));
		$this->assign('user',count($user));
		$this->assign('coin',$coin);
		return $this->fetch();
	}

	public function close(){
		return $this->fetch('die');
	}

}
