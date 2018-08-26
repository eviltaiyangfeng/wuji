<?php
namespace Common\Model;
use Think\Model;
class WujiOrderModel extends Model {
    /* 用户模型自动验证 */
    protected $_validate = array(
        /* 验证用户名 */
        array('account', '6,20', '账号长度在6-15个字符', self::EXISTS_VALIDATE, 'length'), //用户名长度不合法
        array('account','','账号已经存在！',0,'unique',1),
//        /* 验证密码 */
        array('password', '6,15', "密码长度不正确", self::EXISTS_VALIDATE, 'length'), //密码长度不合法


        /* 验证qq号码 */
        array('qq', '', 'qq格式不正确', self::EXISTS_VALIDATE,'length'), //手机格式不正确
    );

    /* 用户模型自动完成 */
    protected $_auto = array(
        array('status', '1', self::MODEL_INSERT),
        array('createtime',date,self::MODEL_INSERT,'function',array('Y-m-d H:i:s')),
        array('create_ip', 'get_client_ip', self::MODEL_INSERT, 'function', 1),
    );






}