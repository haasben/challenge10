<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
/**
*PHP是世界上最好的语言
*@param 公共控制器
**/
class Todaytask extends Controller
{
	
  public function index(){
  		
    Db::name('assets')->where('user_id','>',0)->update(['today_money'=>0]);
  	
  }





    


}
