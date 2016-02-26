<?php
namespace app\admin\controller;
use app\common\controller\Admin;

class Index extends Admin
{
    public function index()
    {
    	$this->setMeta('欢迎页面');
        return $this->fetch();
    }



	public function clear(){
		$dirs	=	array(RUNTIME_PATH);
		mkdir(RUNTIME_PATH,0777,true);
		foreach($dirs as $value) {
			$this->rmdirr($value);
		}
		return $this->success('已经被删除!缓存清理完毕。',U('Index/index'));
	}

	protected function rmdirr($dirname) {
		if (!file_exists($dirname)) {
			return false;
		}
		if (is_file($dirname) || is_link($dirname)) {
			return unlink($dirname);
		}
		$dir = dir($dirname);
		if($dir){
			while (false !== $entry = $dir->read()) {
				if ($entry == '.' || $entry == '..') {
					continue;
				}
				$this->rmdirr($dirname . DIRECTORY_SEPARATOR . $entry);
			}
		}
		$dir->close();
		return rmdir($dirname);
	}

    public function login($username = '', $password = ''){
		if(IS_POST){
			// /* 检测验证码 TODO: */
			// if(!check_verify($verify)){
			// 	$this->error('验证码输入错误！');
			// }

			/* 调用UC登录接口登录 */
			$User = new \app\common\api\UserApi;
			$uid = $User->login($username, $password);
			if(0 < $uid){ //UC登录成功
				/* 登录用户 */
				return $this->success('登录成功！', U('Index/index'));
			} else { //登录失败
				switch($uid) {
					case -1: $error = '用户不存在或被禁用！'; break; //系统级别禁用
					case -2: $error = '密码错误！'; break;
					default: $error = '未知错误！'; break; // 0-接口参数错误（调试阶段使用）
				}
				return $this->error($error);
			}
		} else {
			if(is_login()){
				$this->redirect('Index/index');
			}else{
				return $this->fetch();
			}
		}
    }
}
