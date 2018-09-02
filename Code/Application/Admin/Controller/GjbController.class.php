<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Log;


class GjbController extends Controller {

    private $ACCESS_TOKEN =  "http://vippadmin.54nb.com/api/auth/access_token";
    private $CHECK_USER =  "http://vippadmin.54nb.com/api/user/check_user";
    private $USER_ADD_RAND =  "http://vippadmin.54nb.com/api/user/user_add_rand";
    private $APP_LIST =  "http://vippadmin.54nb.com/api/application/app_list";
    private $REGION_LIST =  "http://vippadmin.54nb.com/api/application/region_list";
    private $USER_ADD_STATIC =  "http://vippadmin.54nb.com/api/user/user_add_static";
    private $USER_ADD_HARD =  "http://vippadmin.54nb.com/api/user/user_add_hard";
    private $GET_USER_INFO =  "http://vippadmin.54nb.com/api/user/get_user_info";
    private $USER_RENEW =  "http://vippadmin.54nb.com/api/user/user_renew";
    private $USER_REPASS =  "http://vippadmin.54nb.com/api/user/user_repass";
    private $TEST_TOKEN = "18bcf015bd8f63836d3658710158e3d4017e8ff2d533c7ee7c76d78ce09c91bc";

    private $ERROR_CODE = array(
        "10001" => "app_id不能为空",
        "10002" => "app_key不能为空",
        "10003" => "app_key错误",
        "10004" => "access_token不能为空",
        "10005" => "access_token不正确",
        "10006" => "用户名不正确",
        "10007" => "用户账号已存在",
        "10008" => "密码不能为空",
        "10009" => "QQ不能为空",
        "10010" => "开卡天数不能为空",
        "10011" => "授权窗口数不能为空",
        "10012" => "账号类型不能为空",
        "10013" => "IP类型不能为空",
        "10014" => "开卡账号不能含空格",
        "10015" => "密码不能含有空格",
        "10016" => "QQ号码填写有误",
        "10017" => "开卡天数有误",
        "10018" => "授权窗口有误",
        "10019" => "账号类型有误",
        "10020" => "IP类型有误",
        "10021" => "拥有的授权数量不足开卡",
        "10022" => "累计充值未满5000元",
        "10023" => "累计充值未满3000元",
        "10024" => "游戏应用ID不能为空",
        "10025" => "游戏应用ID有错误",
        "10026" => "游戏应用最低窗口数未达到",
        "10027" => "游戏应用暂时不允许开卡",
        "10028" => "区域信息不能为空",
        "10029" => "区域数量选择太多",
        "10030" => "区域选择有误",
        "10031" => "选择的区域IP不足",
        "10032" => "区域选择计算错误",
        "10033" => "QQ应用必须填写名字和身份证",
        "10034" => "用户类型不正确",
        "10035" => "用户不存在或无权限获取",
        "10036" => "没有任何更改操作(续费)",
        "10037" => "减少操作动作必须续费",
        "10038" => "减少窗口太多不满足最低窗口要求",
        "10039" => "过期的账号续费必须month",
        "10040" => "提交新密码有错误"
    );

    public function index(){

    }

    public function check_user($username = null){
        if(!empty($username)){
            $post['username'] = $username;
            $post['access_token'] = $this->TEST_TOKEN;
            $data = curl_request($this->CHECK_USER,$post);
            $return['data'] = curl_request($this->CHECK_USER,$post);
        }else{
            $return['status'] = 0;
            $return['msg'] = '必须传递username';
        }
        return $return;
//        {
//                    "ret": 10005,
//            "msg": "access_token不正确",
//            "data": ""
//        }
    }

    public function app_list(){
        $post['access_token'] = $this->TEST_TOKEN;
        $return = json_decode(curl_request($this->APP_LIST,$post),true);
        if($return['ret'] == 0){
            return $return['data'];
        }else{
            return false;
        }
    }

    public function region_list($branchID = null,$usernum = null){
        $branchID = I('branchID');
        $usernum = I('usernum');
        if(empty($branchID) || empty($usernum)){
            $return['status'] = 0;
            $return['msg'] = 'branchID or usernum is null';
        }else{
            $post['branchID'] = $branchID;
            $post['usernum'] = $usernum;
            $post['access_token'] = $this->TEST_TOKEN;
            $return = curl_request($this->REGION_LIST,$post);
        }
        if(IS_AJAX){
            $this->ajaxReturn($return);
        }else{
            if($return['ret'] == 0){
                return $return['data'];
            }else{
                return false;
            }
        }

    }
}