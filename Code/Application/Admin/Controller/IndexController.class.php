<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Log;


class IndexController extends Controller {

    public function index(){
        if($this->is_login()){
            $this->display();
        }
    }

    /**
     * 后台用户登录
     * @author
     */
    public function login(){
        if(IS_AJAX){
            $model = D('SysUser');
            $user = $model->where(array('account'=>$_REQUEST['account']))->find();
            if(empty($user)){
                $return['status'] = 0;
                $return['msg'] = '用户名不正确';
            }else if ($user['password'] == $_REQUEST['password']){
                $return['status'] = 1;
                $return['msg'] = '登录成功';
                $user['menu'] = $this->getMenu($user);
                session('user_auth',$user);
            }else{
                $return['status'] = 0;
                $return['msg'] = '用户名或密码不正确';
            }
            $this->ajaxReturn($return);
        }else{
            $this->display();
        }
    }

    //退出
    public function login_out(){
        session('user_auth',null);
        session('role',null);
        $this->is_login();
    }

    //检测是否登录
    public function is_login(){
        $user_auth = session('user_auth');
        if (!empty($user_auth)) {
            $this->display("index");
        } else {
            $num = rand(100000000000000, 999999999999999);
            $this->assign("key", $num);
            $this->display("login");
        }
    }

    public function getMenu($user_auth){
        return   D('SysMenu')->getMenu($user_auth['id']);
    }
}