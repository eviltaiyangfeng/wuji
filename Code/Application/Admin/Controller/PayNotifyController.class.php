<?php
namespace Admin\Controller;
use Think\Controller;



use Addones\Wxpay\WxPayConfig;
use Addones\Wxpay\PayNotifyCallBack;
use Think\Log;


class PayNotifyController extends Controller {

    public function index(){
        Log::write("pay_init:","INFO",'', C('LOG_PATH').'PayInfo_'.date('y_m_d').'.log');
        //初始化日志
        $config = new WxPayConfig();
        Log::write("begin notify","INFO");
        $notify = new PayNotifyCallBack();
        $notify->Handle($config, false);

        $this->display();
    }




    
}
