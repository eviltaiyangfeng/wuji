<?php
namespace Common\Controller;
use Think\Controller;

class CommonController extends Controller {

    public function _initialize()
    {
        $this->getMenu();
        $this->setMenu(I('menu'));
    }

    public function getMenu(){
        $menu = D('SysMenu')->select();
        $this->assign('menu',$menu);
    }
    public function setMenu($id){
        $this->assign('active_menu',$id);
    }
}