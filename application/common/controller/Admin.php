<?php
namespace app\common\controller;

class Admin extends Front
{

    public $meta_title;

    protected function _initialize()
    {
        $this->assign('Menu',$this->getMenu());

		// 获取当前用户ID
		$rule  = strtolower(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME);
		define('UID',is_login());
		if( !UID && !in_array($rule, \think\Config::get(''))){
			// 还没登录 跳转到登录页面
			$this->redirect('Index/login');
		}

		// 是否是超级管理员
		define('IS_ROOT',   is_administrator());
    }

    protected function getMenu(){

    }

	/**
	* 权限检测
	* @param string  $rule    检测的规则
	* @param string  $mode    check模式
	* @return boolean
	* @author 朱亚杰  <xcoolcc@gmail.com>
	*/
	final protected function checkRule($rule, $type=AuthRuleModel::RULE_URL, $mode='url'){
		static $Auth    =   null;
		if (!$Auth) {
			$Auth       =   new \Think\Auth();
		}
		if(!$Auth->check($rule,UID,$type,$mode)){
			return false;
		}
		return true;
	}

    protected function setMeta($meta_title){
    	$this->assign('meta_title',$meta_title);
    }
}
