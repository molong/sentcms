<?php
namespace app\common\controller;
use app\admin\model\AuthRule;

class Admin extends Front
{

    public $meta_title;

    protected function _initialize()
    {

		// 获取当前用户ID
		$rule  = strtolower(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME);
		define('UID',is_login());
		if( !UID && $rule != 'admin/index/login'){
			// 还没登录 跳转到登录页面
			$this->redirect('Index/login');
		}

		// 是否是超级管理员
		define('IS_ROOT',   is_administrator());

		// 检测系统权限
		if(!IS_ROOT){
			$access =   $this->accessControl();
			if ( false === $access ) {
				$this->error('403:禁止访问');
			}elseif(null === $access ){
				//检测访问权限
				$rule  = strtolower(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME);
				if ( !$this->checkRule($rule,array('in','1,2')) ){
					$this->error('未授权访问!');
				}else{
					// 检测分类及内容有关的各项动态权限
					$dynamic    =   $this->checkDynamic();
					if( false === $dynamic ){
						$this->error('未授权访问!');
					}
				}
			}
		}

        $this->assign('Menu',$this->getMenu());
    }

    protected function getMenu(){
    	$data = array();
		// 获取主菜单
		$where['hide']  =   0;
		$where['type']  =   'admin';
		if(!C('devlop_mode')){ // 是否开发者模式
			$where['is_dev']    =   0;
		}
		$menus  =   M('Menu')->index('id')->where($where)->order('sort asc')->field('id,pid,title,url,icon,group,"" as style')->select();

		foreach ($menus as $key => $item) {
			if ($item['url']) {
				// 判断主菜单权限
				if ( !IS_ROOT && !$this->checkRule(strtolower(MODULE_NAME.'/'.$item['url']),AuthRule::RULE_MAIN,null) ) {
					unset($menus[$key]);
					continue;//继续循环
				}
				if(strtolower(CONTROLLER_NAME.'/'.ACTION_NAME)  == strtolower($item['url'])){
					if ($item['pid']) {
						$menus[$item['pid']]['style'] = 'active';
					}
					$menus[$key]['style'] = 'active';
				}
			}
		}
		$menus = list_to_tree($menus);
		foreach ($menus as $key => $value) {
			if (!empty($value['_child']) || $value['url']) {
				$data[$value['group']][] = $value;
			}
		}
		return $data;
    }

	/**
	 * 检测用户是否被删除或者假登录
	 * @author colin <colin@tensent.cn>
	 */
	protected function is_del(){
		$find = D('Member')->where(array('uid'=>session('user_auth.uid')))->find();
		if(!$find){
			session('user_auth',null);
			$this->error('您的账户存在异常！请重新登录！',U('Public/login'));
		}
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
			$Auth       =   new \com\Auth();
		}
		if(!$Auth->check($rule,UID,$type,$mode)){
			return false;
		}
		return true;
	}

	/**
	 * 检测是否是需要动态判断的权限
	 * @return boolean|null
	 *      返回true则表示当前访问有权限
	 *      返回false则表示当前访问无权限
	 *      返回null，则表示权限不明
	 *
	 * @author 朱亚杰  <xcoolcc@gmail.com>
	 */
	protected function checkDynamic(){}

	/**
	 * action访问控制,在 **登陆成功** 后执行的第一项权限检测任务
	 *
	 * @return boolean|null  返回值必须使用 `===` 进行判断
	 *
	 *   返回 **false**, 不允许任何人访问(超管除外)
	 *   返回 **true**, 允许任何管理员访问,无需执行节点权限检测
	 *   返回 **null**, 需要继续执行节点权限检测决定是否允许访问
	 * @author 朱亚杰  <xcoolcc@gmail.com>
	 */
	final protected function accessControl(){
		$allow = C('allow_visit');
		$deny  = C('deny_visit');
		$check = strtolower(CONTROLLER_NAME.'/'.ACTION_NAME);
		if ( !empty($deny)  && in_array_case($check,$deny) ) {
			return false;//非超管禁止访问deny中的方法
		}
		if ( !empty($allow) && in_array_case($check,$allow) ) {
			return true;
		}
		return null;//需要检测节点权限
	}

    protected function setMeta($meta_title){
    	$this->assign('meta_title',$meta_title);
    }
}
