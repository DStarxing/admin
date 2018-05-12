<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\SystemModel;
use app\index\model\PlayedModel as Pl;
use app\index\model\BankModel as Bk;
use app\index\controller\Base; 
class System extends Base {

	public function index (){
		$data = SystemModel::all();
		$webData = array();
		foreach ($data as $key => $value) {
			$webData[$value['key']] = $value['value'];
		}
		return $this->fetch('system-base',['data'=>$webData]);
	}

	public function newsView(){
		return $this->fetch('article-list');
	}

	public function previewNews(){
		return $this->fetch('article-edit');
	}

	/**
	 * [previewBank 收款银行卡信息页面]
	 * @return [type] [description]
	 */
	public function previewBank(){
		$Bk = new Bk();
		$BankInfo = $Bk->where('type',1)->select();
		$BankImg  = $Bk->where('type',2)->select();
		$this->assign('BankInfo',$BankInfo);
		$this->assign('BankImg',$BankImg);

		return $this->fetch('bank-list');
	}


	/*修改银行卡信息*/
	public function bankEdit(){
		$id = input('id');
		$Bk = new Bk();
		$data = $Bk->where('id',$id)->find();
		return $this->fetch('bank-edit',['data'=>$data]);
	}

	/*游戏列表*/
	public function gameList(){
		return $this->fetch('game-list');
	}



	/**
	 * [updateData 更新系统设置]
	 * @return [type] [description]
	 */
	public function updateData(){
		$data = request()->param();
		$webArr = array();
		if($data['webName'] == ''){
			return 'error';
		}
		$num = 1;
		$num1 = 0;
		foreach ($data as $key => $value) {
			$webArr[$num1]['id']=$num;
			$webArr[$num1]['key']=$key;
			$webArr[$num1]['value']=$value;
			$num++;$num1++;
		}
		$S = new SystemModel();
		$msg = $S->saveAll($webArr);
		if($msg){
			return 'yes';
		}else{
			return 'err';
		}
	}

	/**
	 * [updateBank 更新收款银行卡]
	 * @return [type] [description]
	 */
	public function updateBank(){
		$data = request()->post();
		$file = request()->file('numOrImg');
		if($file){
			$info = $file->move('D:\KKFF\Git\public\static\er');
			if($info){
				$path = '/static/er/'.$info->getSaveName();	
				// echo $path;exit;
			$data['numOrImg'] = $path;
			}
		}

		$Bk = new Bk();
		$result = $Bk->update($data);
		if($result){

			return '<h2 style=\'text-align: center;margin-top:50px;\'>更新成功</h2>';
		}else{
			return '<h2 style=\'text-align: center;margin-top:50px;\' >更新失败</h2>';
		}

	}

	/**
	 * [setIsDelete 更改银行的使用状态]
	 */
	public function setIsDelete(){
		$id = request()->post('id');
		$isDelete = request()->post('isDelete');
		$Bk = new Bk();
		if($isDelete){
			$result = $Bk->where('id',$id)->update(['isDelete'=>0]);
		}else{
			$result = $Bk->where('id',$id)->update(['isDelete'=>1]);
		}

		if($result){
			return 'yes';
		}else{
			return 'no';
		}

	}

	/**
	 * [bankDelete 删除收款银行信息]
	 * @return [type] [description]
	 */
	public function bankDelete(){
		$id     = request()->post('id');
		$Bk     = new Bk();
		$result = $Bk->where('id',$id)->delete();
		if($result){
			return 'yes';
		}else{
			return 'no';
		}

	}

	/**
	 * [addBankV 添加银行卡页面]
	 */
	public function addBankV(){
		return $this->fetch('addBank');
	}


	public function addBankImg(){
		$data = request()->post();
		$bank = [];
		$name = explode(',',$data['name']);
		$file = request()->file('numOrImg');
		if($file){
			$info = $file->move('D:\KKFF\Git\public\static\er');
			if($info){
				$path             = '/static/er/'.$info->getSaveName();	
				$bank['numOrImg'] = $path;
			}
		}
		$bank['isDelete'] = 1;
		$bank['type']     = 2;
		$bank['bankName'] = $name[0];
		$bank['bankLogo'] = '/static/images/bank/'.$name[1];
		$Bk = new Bk();
		$result = $Bk->insert($bank);
		if($result){

			return '<h2 style=\'text-align: center;margin-top:50px;\'>添加成功</h2>';
		}else{
			return '<h2 style=\'text-align: center;margin-top:50px;\' >添加失败</h2>';
		}

	}


	public function addBankInfo(){
		$data = request()->post();
		$bankname = array_shift($data);
		$bankname = explode(',',$bankname);
		$data['bankName'] = $bankname[0];
		$data['bankLogo'] = '/static/images/bank/'.$bankname[1];
		$data['isDelete'] = 1;
		$data['type'] = 1;
		$Bk = new Bk();
		$result = $Bk->insert($data);
		if($result){
			return 'yes';
		}else{
			return 'no';
		}
	}



	/**
	 * [showOdds 赔率显示页面]
	 * @return [type] [description]
	 */
	public function showOdds(){
		$Pl = new Pl();
		$played = $Pl->order('id desc')->select();
		return $this->fetch('showOdds',['data'=>$played]);
	}
	
	/**
	 * [setOdds 设置赔率 转换数据格式--批量更新]
	 */
	public function setOdds(){
		$data = request()->param();
		$datas = [];
		$Pl = new Pl();
		// print_r($data);exit;
		$i = 0;
		foreach ($data as $key => $value) {	
			foreach ($value as $v) {
				if($key == 'id'){
					$datas[$i]['id'] = $v;
				}elseif($key == 'odds'){
					$datas[$i]['odds'] = $v;
				}else{
					$datas[$i]['maxBonus'] = $v;
				}
				if($i == 5){
					$i=0;
				}else{
					$i++;
				}
			}
		}	
		$result = $Pl->saveAll($datas);
		if($result){
			return 'yes';
		}else{
			return 'error';
		}
	}

}


?>