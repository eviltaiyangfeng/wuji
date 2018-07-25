<?php
namespace Common\Model;
use Think\Model;
class SysMenuModel extends Model {




    public function getMenu($user_id,$type = 'admin',$status = 1,$viewable = 1){
        $where['user_id'] = $user_id;
        $where['type'] = $type;
        $where['status'] = $status;
        $where['viewable'] = $viewable;
        $result =  D('SysMenuView')->where($where)->select();
        $menu = array();
        foreach ($result as &$v){
            if($v['parent_id']){
                $menu[$v['parent_id']]['sub'] = $v;
            }else{
                $menu[$v['id']] = $v;
            }
        }
        return $menu;
    }

    /**
     * 注销当前用户
     * @return void
     */
    public function logout(){
        session('user_auth', null);
        session('user_auth_sign', null);
    }


}