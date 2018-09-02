<?php
namespace Common\Model;
use Think\Model;
class SysUserModel extends Model {
    /* 用户模型自动验证 */
    protected $_validate = array(
        /* 验证用户名 */
        array('account', '6,20', '账号长度在6-15个字符', self::EXISTS_VALIDATE, 'length'), //用户名长度不合法
        array('account','','账号已经存在！',0,'unique',1),
//        /* 验证密码 */
        array('password', '6,15', "密码长度不正确", self::EXISTS_VALIDATE, 'length'), //密码长度不合法


        /* 验证手机号码 */
        array('mobile', '', '手机格式不正确', self::EXISTS_VALIDATE,'length'), //手机格式不正确
        array('mobile','','手机号码已经存在！',0,'unique',1),//有逻辑删除,判断是否存在用程序判断
    );

    /* 用户模型自动完成 */
    protected $_auto = array(
        array('status', '1', self::MODEL_INSERT),
        array('create_time',date,self::MODEL_INSERT,'function',array('Y-m-d H:i:s')),
        array('last_login_ip', 'get_client_ip', self::MODEL_INSERT, 'function', 1),
    );


    /**
     * 检测邮箱是不是被禁止注册
     * @param  string $email 邮箱
     * @return boolean       ture - 未禁用，false - 禁止注册
     */
    protected function checkDenyEmail($email){
        return true; //TODO: 暂不限制，下一个版本完善
    }


    /**
     * @param $data
     * @return mixed|string
     */
    public function register($data){

        $redata = $this->create($data);
        if($redata){
            $id = $this->add();
            if($id>0){
                $redata['id']=$id;
                return $id;
            }else{
                return '未知错误';
            }
        } else {
            return $this->getError();
        }
    }

    /**自动登录
     * @param $user
     * @return bool
     * 前台根据TRUE FALSE来进行登录
     */
    public function autoLogin($user){
        $data = array(
            'id'             => $user['id'],
            'last_login_ip'   => get_client_ip(1),
            'last_login_time'   => date('Y-m-d H:i:s')
        );
        $this->save($data);
        $auth = array(
            'account' => $user['account'],
            'id' => $user['id'],
            'user_type' => $user['user_type'],
            'nike_name' => $user['nike_name']
        );
        session('user_auth', $auth);

        return true;
    }
    /**
     * 注销当前用户
     * @return void
     */
    public function logout(){
        session('user_auth', null);
        session('user_auth_sign', null);
    }

    /**
     * 给用户分配角色
     * @param $userid
     * @param $roles 数组
     * @return bool
     */
    public function addUserRole($userid, $roles){
        $data=array();
        foreach($roles as $v){
            $data[]=array(
                'fk_user'=>$userid,
                'fk_role'=>$v
            );
        }
        $re = M('user_authority_r')->addAll($data);
        if($re>0){
            return true;
        }else{
            return false;
        }
    }
}