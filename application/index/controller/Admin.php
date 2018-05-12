<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\AdminModel as Ad;
use app\index\controller\Base; 
class Admin extends Base {

	
	/**
	 * [adminList 显示管理员列表]
	 * @return [type] [description]
	 */
	public function adminList(){
		$data = Ad::all();

		return $this->fetch('admin-list',['data'=>$data]);
	}

	/**
	 * [isDelete 停用]
	 * @return boolean [description]
	 */
	public function isDelete(){
		$id     = request()->post('id');
		if($id == 1){
			return 'no';
		}
		$start  = request()->post('start');
		$Ad     = new Ad();
		if($start == 1){
			$result = $Ad->where('id',$id)->update(['start'=>0]);
		}else{
			$result = $Ad->where('id',$id)->update(['start'=>1]);
		}

		if($result){
			return 'yes';
		}else{
			return 'no';
		}
	}

	/**
	 * [setAdminV 打开编辑管理员信息视图]
	 */
	public function updateAdminV(){
		$id   = input('id');
		$Ad   = new Ad();
		$user = $Ad->where('id',$id)->find();
		return $this->fetch('AdminEdit',['data'=>$user]);
	}

	/**
	 * [updateAdmin 修改管理员信息]
	 * @return [type] [description]
	 */
	public function updateAdmin(){
		$data          = request()->post();
		if($data['password'] == ''){
			unset($data['password']);
		}else{
			$data['password'] = md5($data['password']);
		}
		$Ad            = new Ad();
		$result        = $Ad->update($data);
		if($result){	
			return 'yes';
		}else{
			return 'no';
		}
	}


	/**
	 * [addAdminV 打开添加管理员视图]
	 */
	public function addAdminV(){
		return $this->fetch('addAdmin');
	}

	/**
	 * [addAdmin 添加管理员]
	 */
	public function addAdmin(){
		$data             = request()->post();
		if($this->checkAdmin($data['username'])){
			// throw new Exception('userName');
			die(json_encode('userName'));
		}
		$data['password'] = md5($data['password']);
		$Ad               = new Ad();
		$data['start']    = 1;
		$result = $Ad->insert($data);
		if($result){
			return 'yes';
		}else{
			return 'no';
		}

	}

	/**
		判断用户名是否被占用
	*/
	public function checkAdmin($name){
		$result = Ad::where('username','=',$name)->select();
		if($result){
			return true;
		}else{
			return false;
		}
	}


	public function adminDelete(){
		$id     = request()->post('id');
		if($id == 1){
			return 'no';
		}
			$Ad     = new Ad();
			$result = $Ad->where('id',$id)->delete();
		if($result){
			return 'yes';
		}else{
			return 'no';
		}
	}





}
