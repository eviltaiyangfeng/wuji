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

    public function add(){
        if(IS_AJAX){
            $model = D('SysMenu');
            if($model->create()){
                $ret = $model->add();
                if($ret){
                    $return['status'] = 1;
                    $return['msg'] = '添加成功';
                }else{
                    $return['status'] = 0;
                    $return['msg'] = '添加失败';
                }
            }
            $this->ajaxReturn($return);
        }else{
            $parent_id = I('parent_id');
            if(!empty($parent_id)){
                $this->assign('parent_id',$parent_id);
            }
            $this->assign('menu',D('SysMenu')->getAllMenu());
            $this->display();
        }
    }

    public function edit(){
        if(IS_AJAX){
            $model = D('SysMenu');
            if($model->create()){
                $ret = $model->add();
                if($ret){
                    $return['status'] = 1;
                    $return['msg'] = '添加成功';
                }else{
                    $return['status'] = 0;
                    $return['msg'] = '添加失败';
                }
            }
            $this->ajaxReturn($return);
        }else{
            $id = I('id');
            if(!empty($id)){
                $this->assign('menu',D('SysMenu')->find($id));
            }
            $this->assign('menu',D('SysMenu')->getAllMenu());
            $this->display();
        }
    }
}