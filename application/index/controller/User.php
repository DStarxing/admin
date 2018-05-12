<?php
namespace app\index\controller;
use think\Controller;
use think\Request;
use app\index\model\UserModel;
use app\index\model\WithdrawmoneyModel as Wm;
use app\index\model\UserRechargeModel as Ur;
use app\index\model\UserLogModel as Log;
use app\index\model\BetsModel as Bet;
use app\index\model\UserBankModel as Ub;
use app\index\controller\Base; 
class User extends Base {
	
	//添加用户
	public function addUserView(){
		return $this->fetch('member-add');
	}


	// 显示用户银行卡信息
	public function showBank(Request $request){
		$id = input('id');
		$Wm = new Wm();
		$data = $Wm->getfindData($id);
		$data = json_decode($data,true);
		// print_r($data);exit;
		return $this->fetch('userBank',['data'=>$data]);
	}

	// 提现请求列表
	public function requestCash(){
		$where = [];
		$where['state'] =['=','1'];
		$Wm = new Wm();
		$data = $Wm->getReqyestCoin($where);
		
		return $this->fetch('requestCash',['data'=>$data]);
	}

	/**
	 * [coinArrive 用户提现资金到帐 更改提现订单的状态]
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function coinArrive(Request $request){
		$id = $request->post('id');
		$Wm  = new Wm();
		$us  = new UserModel();
		$requestinfo = $Wm->where('id',$id)->find();
		// 减去该用户的冻结金额
		$us->where('id',$requestinfo['uid'])->setDec('fcoin',$requestinfo['amount']);
		$result = $Wm->where('id',$id)->update(['state'=>1]);
		if($result){
			echo json_encode('yes');
		}else{
			echo json_encode('no');
		}

	}


	// 显示充值请求
	public function requestRecharge(){
		$where = [];
		$where['state'] =['=','1'];
		if(request()->has('minTime') && request()->has('maxTime')){
			$minTime = request()->get('minTime');
			$minTimes = strtotime($minTime.' 00:00:00');
			$maxTime = request()->get('maxTime');
			$maxTimes = strtotime($maxTime.' 23:59:59');
			// echo $minTime .'---'. $maxTime;exit;
			// echo $minTime;exit;
			$this->assign('minTime',$minTime);
			$this->assign('maxTime',$maxTime);
			$where ['time']= ['between',[$minTimes,$maxTimes]];
		}

		$Ur = new Ur();
		$data = $Ur->getRecharge($where);
		$this->assign('num',count($data));
		return $this->fetch('requestRecharge',['data'=>$data]);
	}

	//显示提现记录 
	public function cashList(){
		$where = [];
		if(request()->has('minTime') && request()->has('maxTime')){
			$minTime = request()->get('minTime');
			$minTimes = strtotime($minTime.' 00:00:00');
			$maxTime = request()->get('maxTime');
			$maxTimes = strtotime($maxTime.' 23:59:59');
			// echo $minTime .'---'. $maxTime;exit;
			// echo $minTime;exit;
			$this->assign('minTime',$minTime);
			$this->assign('maxTime',$maxTime);
			$where ['time']= ['between',[$minTimes,$maxTimes]];
			// print_r(request()->get());exit;
		}
		$Wm = new Wm();
		$data = $Wm->getReqyestCoin($where);
		$this->assign('num',count($data));


		return $this->fetch('Cash-list',['data'=>$data]);
	}




	/**
	 * [setUserCoin 确认订单到帐]
	 */
	public function setUserCoin(Request $request){
		$id = $request->post('id');
		$logArr = [];
		// 获取该张订单 和 修改订单状态
		$Ur = new Ur();
		$orderArr = $Ur->where('id',$id)->find();
		$Ur->where('id',$id)->update(['state'=>2]);
		$Us = new UserModel();
		// 给用户的余额加上订单的金额
		$result = $Us->where('id',$orderArr['uid'])->setInc('coin',$orderArr['amount']);
		$user = $Us->where('id',$orderArr['uid'])->find();
		// 添加用户资金流动日志
		if($result){
			$log = new Log();
			$logArr['uid'] = $orderArr['uid'];
			$logArr['coin'] = $orderArr['amount'];
			$logArr['userCoin'] = $user['coin'];
			$logArr['actionTime'] =time();
			// 1 充值 2 提现 3 投注盈利 4投注亏损 5投注
			$logArr['Type'] = 1;
			$log->insert($logArr);
			echo json_encode('yes');
		}else{
			echo json_encode('no');
		}

	}


	
	// 显示用户列表
	public function showUser(){
		$where = [];
		if(request()->has('userName')){
			$username = request()->get('userName');
			if($username !== ''){
				$this->assign('userName',$username);
				$where ['username']= ['like','%'.$username.'%'];
			}
		}

		if(request()->has('minTime') && request()->has('maxTime')){
			$minTime = request()->get('minTime');
			// var_dump($minTime );exit;
			if($minTime !== ''){
			$minTimes = strtotime($minTime.' 00:00:00');
			$maxTime = request()->get('maxTime');
			$maxTimes = strtotime($maxTime.' 23:59:59');
			// echo $minTime .'---'. $maxTime;exit;
			// echo $minTime;exit;
			$this->assign('minTime',$minTime);
			$this->assign('maxTime',$maxTime);
			$where ['registerTime']= ['between',[$minTimes,$maxTimes]];
			// print_r(request()->get());exit;
			}
		}
		$list = UserModel::where($where)->paginate(12);
		$page = $list->render();
		// print_r($page);exit;
		$this->assign('list', $list);
		$this->assign('page', $page);
		$this->assign('num',count($list));
		return $this->fetch('User-list');
	}


	/*
	  批量删除用户 根据 余额 和 登录时间
	*/
	public function deleteUserAll(){
		$data = request()->post();
		$data = json_decode($data,1);
		$time = $data['time'] * 60 * 60 * 24;
		$time = time()-$time;
		$result = UserModel::where('coin','<',$data['coin'])->where('upTime','<',$time)->delete();
		if($result){
			return 'yes';
		}else{
			return 'no';
		}
	}

	
	/**
	 * [userLog 查询用户的资金流水 -- ]
	 * @return [type] [description]
	 */
	public function userLog(){	
		$where = [];
		if(request()->has('uid')){
				$uid = request()->get('uid');
			if($uid !== ''){
				$this->assign('uid',$uid);
				$where ['uid']= $uid;
			}
		}
		if(request()->has('minTime') && request()->has('maxTime')){
				$minTime = request()->get('minTime');
			if($minTime !== ''){
				$minTimes = strtotime($minTime.' 00:00:00');
				$maxTime = request()->get('maxTime');
				$maxTimes = strtotime($maxTime.' 23:59:59');
				// echo $minTime .'---'. $maxTime;exit;
				// echo $minTime;exit;
				$this->assign('minTime',$minTime);
				$this->assign('maxTime',$maxTime);
				$where ['actionTime']= ['between',[$minTimes,$maxTimes]];
			}
		}
		$log = Log::where($where)->paginate(12);;
		$user = new UserModel();
		foreach ($log as $key => $value) {
			$userArr = $user->where('id',$value['uid'])->find();
			$log[$key]['username'] = $userArr['username'];
			switch ($value['Type']) {
				case 1:
					$log[$key]['Type'] = '充值';
					break;
				case 2:
					$log[$key]['Type'] = '提现';
					break;
				case 3:
					$log[$key]['Type'] = '下注盈利';
					break;
				case 4:
					$log[$key]['Type'] = '下注亏损';
					break;
				case 5:
					$log[$key]['Type'] = '投注';
					break;	
			}
		}	
		$num = count($log);
		return $this->fetch('user-log',['data'=>$log,'num'=>$num]);
	}

	/**
	 * [userEdit 用户编辑弹窗]
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function userEdit(Request $request){
		$id = input('id');
		$us = new UserModel();
		$userinfo = $us->where('id',$id)->find();
		// print_r($id);exit;
		return $this->fetch('userEdit',['data'=>$userinfo]);
	}

	/**
	 * [userUpdate 更新用户信息]
	 * @return [type] [description]
	 */
	public function userUpdate(){
		$data = request()->post();
		if($data['password'] == ''){
			unset($data['password']);
		}
		if($data['coinPassword'] == ''){
			unset($data['coinPassword']);
		}
		// print_r($data);exit;
		$us = new UserModel();

		$result = $us->where('id',$data['id'])->update($data);
		if($result){
			return 'yes';
		}else{
			return 'no';
		}

	}

	/**
	 * [userDelete 根据Id 删除用户表 投注表 log表 充值表 提现表]
	 * @return [type] [description]
	 */
	public function userDelete(){
		$uid = request()->post('id');

	$log = new Log();
	$Bet = new bet();
	$wm  = new Wm();
	$Ur  = new Ur();
	$us  = new UserModel();
	$ub  = new Ub();
	
	$result = $log->where('uid',$uid)->delete();
	$result1 = $Bet->where('uid',$uid)->delete();
	$result2 = $wm->where('uid',$uid)->delete();
	$result3 = $Ur->where('uid',$uid)->delete();
	$result4 = $ub->where('id',$uid)->delete();
	$result5 = $us->where('id',$uid)->delete();
	// echo $uid;exit;
	if($result5){
		return 'yes';
	}else{

	}

	}









	/**
	 * [userAdd 添加用户]
	 * @return [type] [description]
	 */
	public function userAdd(){
		// 助手函数获取POST数据
		$data = request()->post();
		// 判断用户名是否重复
		if($this->repeatName($data['username'])){
			// throw new Exception('userName');
			die(json_encode('userName'));
		}
		// MD5加密
		$data['password'] = md5($data['password']);
		$data['coinPassword'] = md5($data['coinPassword']);
		$data['registerTime'] = time();
		$user = new UserModel($data);
		$result = $user->allowField(true)->save();
		if($result){
			echo json_encode('yes');
		}else{
			echo json_encode('error');
		}
		
	}

	/**
		判断用户名是否被占用
	*/
	public function repeatName($name){
		$result = UserModel::where('username','=',$name)->select();
		if($result){
			return true;
		}else{
			return false;
		}
	}

	/**
		检查密码过于简单
	*/


}
