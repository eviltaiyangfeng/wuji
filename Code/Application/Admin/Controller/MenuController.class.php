<?php
namespace Admin\Controller;
use Think\Controller;


class MenuController extends Controller {

    public function index(){
        $this->display();
    }
    public function admin(){
        $this->assign('menu',D('SysMenu')->getAllMenu());
        $this->display();
    }
    public function front(){
        $this->display();
    }
}