<?php

namespace app\index\model;
use think\Model;

class WithdrawmoneyModel extends model{
     protected $table = 'sea_withdrawmoney';
	

	/**
	 * [getReqyestCoin 查出所有申请提现的订单]
	 * @return [type] [description]
	 */
	public function getReqyestCoin($where){
		$user     = new \app\index\model\UserModel();
		$userbank = new \app\index\model\UserBankModel();
		
			$data    = $this->where($where)->order('id desc')->paginate(10);
		

		foreach ($data as $key => $value) {
			$userArr                = $user->where('id',$value['uid'])->find();
			$data[$key]['username'] = $userArr['username'];
			$bankArr                = $userbank->where('id',$value['userBank'])->find();
			$data[$key]['bankName'] = $bankArr['bankName'];
			$data[$key]['name']     = $bankArr['name'];
			$data[$key]['account']  = $bankArr['account'];
			$data[$key]['counName'] = $bankArr['counName'];
		}
		return $data;
	}



	
	/**
	 * [getfindData 根据订单查处用户银行卡]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function getfindData($id){
		$userbank               = new \app\index\model\UserBankModel();
		$data                   = $this->where('id',$id)->find();
		$bankArr                = $userbank->where('id',$data['userBank'])->find();
		$data['bankName'] = $bankArr['bankName'];
		$data['name']     = $bankArr['name'];
		$data['account']  = $bankArr['account'];
		$data['counName'] = $bankArr['counName'];
		return json_encode($data);
	}
}


?>