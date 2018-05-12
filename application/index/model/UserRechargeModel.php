<?php

namespace app\index\model;

use think\Model;

class UserRechargeModel extends Model{
 	protected $table = 'sea_UserRecharge';


 	public function getRecharge($where){
 		$data = $this->where($where)->order('id desc')->paginate(10);	
 		// $list = $data->render();
 		// var_dump($list);exit;
 		$user = new \app\index\model\UserModel();
 		$userBank = new \app\index\model\BankModel();
 		foreach ($data as $key => $value) {
 			$userArr = $user->where('id',$value['uid'])->find();
 			$userBank = $userBank->where('id',$value['bankId'])->find();
 			$data[$key]['username'] = $userArr['username']; 
 			$data[$key]['oldCoin'] = $userArr['coin'];
 			$data[$key]['bankName'] = $userBank['bankName'];
 		}
 		return $data;
 	}
}