<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\BankModel as Bk;
use app\index\model\UserBankModel as Ub;
use app\index\controller\Base; 
class Bank extends Base {
	

	/**
	 * [showUserBank 显示银行卡列表]
	 * @return [type] [description]
	 */
	public function showUserBank(){
		$where = [];
		if(request()->has('uid')){
				$uid = request()->get('uid');
			if($uid !== ''){
				$this->assign('uid',$uid);
				$where ['uid']= $uid;
			}
		}
		$Ub = new Ub();
		$data = $Ub::where($where)->paginate(12);
		$this->assign('num',count($data));
		$this->assign('data',$data);
		return $this->fetch('Bank-list');
	}

	/**
	 * [bankEdit 银行卡编辑]
	 * @return [type] [description]
	 */
	public function bankEdit(){
		$id = input('id');
		$ub = new Ub();
		$data = $ub->where('id',$id)->find();

		return $this->fetch('BankEdit',['data'=>$data]);
	}	

	/**
	 * [BankUpdate 更新用户银行卡信息]
	 */
	public function BankUpdate(){
		$data = request()->post();
		$Ub = new Ub();
		$result = $Ub->where('id',$data['id'])->update($data);
		if($result){
			return 'yes';
		}else{
			return 'no';
		}

	}	


}
