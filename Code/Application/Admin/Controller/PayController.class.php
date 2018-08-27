<?php
namespace Admin\Controller;
use Think\Controller;

/**
 *
 * example目录下为简单的支付样例，仅能用于搭建快速体验微信支付使用
 * 样例的作用仅限于指导如何使用sdk，在安全上面仅做了简单处理， 复制使用样例代码时请慎重
 * 请勿直接直接使用样例对外提供服务
 *
 **/

use Addones\Wxpay\Lib\WxPayApi;
use Addones\Wxpay\JsApiPay;
use Addones\Wxpay\WxPayConfig;
use Think\Log;
use Addones\Wxpay\Lib\WxPayUnifiedOrder;
use Think\Exception;

class PayController extends Controller {

    public function index(){
        Log::write("pay_init:","INFO",'', C('LOG_PATH').'PayInfo_'.date('y_m_d').'.log');
        //初始化日志
        //①、获取用户openid
        try{

            $tools = new JsApiPay();
            $openId = $tools->GetOpenid();

            //②、统一下单
            $input = new WxPayUnifiedOrder();
            $input->SetBody("test");
            $input->SetAttach("test");
            $input->SetOut_trade_no("sdkphp".date("YmdHis"));
            $input->SetTotal_fee("1");
            $input->SetTime_start(date("YmdHis"));
            $input->SetTime_expire(date("YmdHis", time() + 600));
            $input->SetGoods_tag("test");
            $input->SetNotify_url("http://paysdk.weixin.qq.com/notify.php");
            $input->SetTrade_type("JSAPI");
            $input->SetOpenid($openId);
            $config = new WxPayConfig();
            $order = WxPayApi::unifiedOrder($config, $input);
            echo '<font color="#f00"><b>统一下单支付单信息</b></font><br/>';
            $this->printf_info($order);
            $jsApiParameters = $tools->GetJsApiParameters($order);
            $this->assign('jsApiParameters',$jsApiParameters);
            //获取共享收货地址js函数参数
            $editAddress = $tools->GetEditAddressParameters();
            $this->assign('editAddress',$editAddress);
        } catch(Exception $e) {
            Log::write(json_encode($e),'ERR');
        }
        //③、在支持成功回调通知中处理成功之后的事宜，见 notify.php
        $this->display();
    }



    //打印输出数组信息
    function printf_info($data)
    {
        foreach($data as $key=>$value){
            echo "<font color='#00ff55;'>$key</font> :  ".htmlspecialchars($value, ENT_QUOTES)." <br/>";
        }
    }


/**
 * 注意：
 * 1、当你的回调地址不可访问的时候，回调通知会失败，可以通过查询订单来确认支付是否成功
 * 2、jsapi支付时需要填入用户openid，WxPay.JsApiPay.php中有获取openid流程 （文档可以参考微信公众平台“网页授权接口”，
 * 参考http://mp.weixin.qq.com/wiki/17/c0f37d5704f0b64713d5d2c37b468d75.html）
 */

    function notice(){

    }

}
