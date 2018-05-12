<?php
namespace app\index\controller;
use think\Controller;
use think\Request;
use app\index\model\BetsModel as Bet;
use app\index\model\UserModel as User;
use app\index\model\DataModel as Da;
use app\index\controller\Base; 

	/**
	 * 注单类 彩票类
	 */
	class Lottery extends Base{

	
	/**
	 * [showBet 显示所有的注单]
	 * @return [type] [description]
	 */
	public function showBet(){
		$where = [];
		if(request()->has('uid')){
			$uid = request()->get('uid');
			if($uid !== ''){
				$this->assign('uid',$uid);
				$where ['uid']= $uid;
			}
		}
		if(request()->has('date')){
			$date = request()->get('date');
			if($date !== ''){
				$this->assign('date',$date);
				$where ['actionNo']= ['=',$date];
			}
		}
		if(request()->has('orderId')){
			$orderId = request()->get('orderId');
			if($orderId !== ''){
			$this->assign('orderId',$orderId);
			$where ['orderId']= ['=',$orderId];
			}
		}
		if(request()->has('minTime') && request()->has('maxTime')){

			$minTime = request()->get('minTime');
			if($minTime !== ''){				
			$minTimes = strtotime($minTime.' 00:00:00');
			$maxTime = request()->get('maxTime');
			$maxTimes = strtotime($maxTime.' 23:59:59');
			$this->assign('minTime',$minTime);
			$this->assign('maxTime',$maxTime);
			$where ['actionTime']= ['between',[$minTimes,$maxTimes]];
			}
			// print_r(request()->get());exit;
		}
		$list = Bet::where($where)->order('id desc')->paginate(12);

		foreach ($list as $key => $value) {
			$user = User::where('id',$value['uid'])->find();
			$list[$key]['username'] = $user['username'];
			 $played = '';
			 if($value['playedId'] < 4){
              $played .= '两骰';
              if($value['playedId'] ==1){
                $played .= '单压';
              }else if($value['playedId'] ==2){
                $played .= '二中二' ;
              }else{
                $played .= '对子';
              }
             
            }else{
              $played .= '三骰';
              if($value['playedId'] ==4){
                $played .= '单压';
              }else if($value['playedId'] ==5){
                $played .= '二中二' ;
              }else{
                $played .= '豹子';
              }
             }
             $list[$key]['playedId'] = $played;

             if($value['state'] == 0){
             	$list[$key]['state'] = '未开奖';
             }elseif($value['state'] == 1){
             	$list[$key]['state'] = '中奖';
             }else{
             	$list[$key]['state'] = '未中奖';
             }	
		}

		$num = count($list);
		return $this->fetch('bet-list',['data'=>$list,'num'=>$num]);
	}


	public function deleteBet(Request $request){
		$id = $request->post('id');
		$Bet = new Bet();
		$result = $Bet->where('id',$id)->delete();
		if($result){
			return  'yes';
		}else{
			return  'no';
		}

	}

	/**
	 * [show 显示所有开奖数据]
	 * @return [type] [description]
	 */
	public function showAllData(){
		$where = [];
		if(request()->has('number')){
			$number = request()->get('number');
			if($number !== ''){
				$this->assign('number',$number);
				$where ['number']= ['like','%'.$number.'%'];
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
			$where ['time']= ['between',[$minTimes,$maxTimes]];
			// print_r(request()->get());exit;
			}
		}
		$fa = '';
		$data = new Da();
		if($where){
			$fa = 0;
			$dataArr = $data->where($where)->select();
		}else{
			$fa = 1;
			$dataArr = $data->paginate(20);
		}
		$this->assign('fa',$fa);
		$this->assign('num',count($dataArr));
		return $this->fetch('data',['data'=>$dataArr]);
	}





}


?>