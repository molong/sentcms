<?php
namespace app\index\controller;
use app\common\controller\Front;

class Index extends Front
{
    public function index()
    {
        return $this->fetch();
    }
}
