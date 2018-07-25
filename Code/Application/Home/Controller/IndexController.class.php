<?php
namespace Home\Controller;

use Common\Controller\CommonController;

class IndexController extends CommonController{
    public function index(){
        $this->display();
    }

    public function register(){
        if(IS_AJAX){
            $model = D('SysUser');
            $user = $model->where(array('account'=>$_REQUEST['account']))->find();
            if(empty($user)){
               $result =  $model->register($_REQUEST);
            }
            if(is_numeric($result['id'])){
                $return['status'] = 1;
                $return['msg'] = '注册成功';
                $model->autoLogin($result);
            }else{
                $return['status'] = 0;
                $return['msg'] = $result;
            }
            $this->ajaxReturn($return);
        }else{
            $this->display();
        }

    }
}