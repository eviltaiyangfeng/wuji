<?php
namespace Common\Model;
use Think\Model;
class SysMenuModel extends Model {




    public function getMenu($user_id = null,$type = 'admin',$status = 1,$viewable = 1){
        if(!empty($user_id)){
            $where['user_id'] = $user_id;
        }
        $where['type'] = $type;
        $where['status'] = $status;
        $where['viewable'] = $viewable;
        $result =  D('SysMenuView')->where($where)->select();
        return $this->formatMenu($result);
    }

    public function formatMenu($menu_list){
        $menu = array();
        foreach ($menu_list as &$v){
            if($v['parent_id']){
                $menu[$v['parent_id']]['sub'][] = $v;
            }else{
                $menu[$v['id']] = $v;
            }
        }
        return $menu;
    }

    public function getAllMenu(){
        $admin_list = $this->formatMenu($this->where(array('type'=>'admin'))->select());
        $front_list = $this->formatMenu($this->where(array('type'=>'front'))->select());
        return  array('admin'=>$admin_list,'front'=>$front_list);
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