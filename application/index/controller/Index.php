<?php
namespace app\index\controller;
use app\common\controller\Front;

class Index extends Front
{
    public function index()
    {
        echo '<img src="'.U('Index/index/verify').'">';
    }

    public function verify(){
    	$verify = new \org\Verify(array('length'=>4,'useCurve'=>false));
    	return $verify->entry();
    }
}
