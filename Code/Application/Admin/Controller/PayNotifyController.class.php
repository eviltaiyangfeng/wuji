<?php
namespace Admin\Controller;
use Think\Controller;
use Addones\Wxpay\WxPayConfig;
use Addones\Wxpay\PayNotifyCallBack;
use Think\Log;


class PayNotifyController extends Controller {


    public function index(){
        Log::write("begin notify:","INFO",'', C('LOG_PATH').'PayInfo_'.date('y_m_d').'.log');
        Log::write( "notify data:".file_get_contents('php://input'),"INFO",'', C('LOG_PATH').'PayInfo_'.date('y_m_d').'.log');
        //初始化日志
        $config = new WxPayConfig();
        $notify = new PayNotifyCallBack();
        $flag = $notify->Handle($config, false);
        if($notify->GetReturn_code() == "SUCCESS"){
            Log::write( "pay: success:".$flag,"INFO",'', C('LOG_PATH').'PayInfo_'.date('y_m_d').'.log');

        }else{
            Log::write( "pay: false:".$flag,"INFO",'', C('LOG_PATH').'PayInfo_'.date('y_m_d').'.log');
        }
    }
}
