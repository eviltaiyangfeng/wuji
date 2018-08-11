<?php
namespace Common\Controller;
use Think\Controller;

class CommonController extends Controller {

    public function _initialize()
    {
        if (ismobile()) {
            //设置默认默认主题为 Mobile
            C('DEFAULT_V_LAYER','Mobile');
        }
        $this->getMenu();
        $this->setMenu(I('menu'));
    }

    public function getMenu(){
        $where['type'] = 'front';
        $where['status'] = 1;
        $menu = D('SysMenu')->where($where)->select();
        $this->assign('menu',$menu);
    }
    public function setMenu($id){
        $this->assign('active_menu',$id);
    }
}