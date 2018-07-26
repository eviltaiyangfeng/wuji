<?php
namespace Common\Model;
use Think\Model\ViewModel;
class SysMenuViewModel extends ViewModel {

    public $viewFields = array(
        'sys_user'=>array('id'=>'user_id'),
        'sys_user_role_r'=>array( '_on'=>'sys_user.id=sys_user_role_r.fk_user'),
        'sys_role_menu_r'=>array( '_on'=>'sys_user_role_r.fk_role = sys_role_menu_r.fk_role'),
        'sys_menu'=>array('id','url','name','viewable','status','remark','parent_id','open','order_index','type','iconfont', '_on'=>'sys_role_menu_r.fk_menu = sys_menu.id'),
    );

}