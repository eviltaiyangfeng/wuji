<?php
namespace Admin\Controller;
use Think\Controller;
use Addones\Wxpay\Lib\WxPayApi;
use Addones\Wxpay\JsApiPay;
use Addones\Wxpay\WxPayConfig;
use Addones\Wxpay\Lib\WxPayUnifiedOrder;
use Addones\Wxpay\NativePay;

use Think\Exception;
use Think\Log;
require_once ADDON_PATH . '/phpqrcode/phpqrcode.php';
/**
 *
 * example目录下为简单的支付样例，仅能用于搭建快速体验微信支付使用
 * 样例的作用仅限于指导如何使用sdk，在安全上面仅做了简单处理， 复制使用样例代码时请慎重
 * 请勿直接直接使用样例对外提供服务
 *
 **/
class PayController extends Controller {

    public function index($param){

        $this->display();
    }



    //打印输出数组信息
    function printf_info($data)
    {
        echo '<font color="#f00"><b>统一下单支付单信息</b></font><br/>';
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

    public function create_weixin_order($param){
        //初始化日志
        Log::write("pay_init:".json_encode($param),"INFO",'', C('LOG_PATH').'PayInfo_'.date('y_m_d').'.log');


        $param['goods_tag'] = '';
        //②、统一下单
        $input = new WxPayUnifiedOrder();
        $input->SetBody($param['body']);
        $input->SetAttach($param['attach']);
        $input->SetOut_trade_no($param['out_trade_no']);
        $input->SetTotal_fee($param['fee']);
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetGoods_tag($param['goods_tag']);
        $input->SetNotify_url( $param['notify_url']);
        if($param['is_mobile']){
            //①、获取用户openid
            $tools = new JsApiPay();
            $return = $tools->GetOpenid();
            if($return['status']){
                $param['openId'] = $return['openId'];
            }else{
                $result['status'] = 0;
                $result['msg'] = '下单失败,请在微信内部打开';
                return $result;
                exit();
            }
            try{
                $input->SetTrade_type("JSAPI");
                $input->SetOpenid($param['openId']);
                $config = new WxPayConfig();
                $order = WxPayApi::unifiedOrder($config, $input);
                Log::write("pay_order:".json_encode($order),"INFO",'', C('LOG_PATH').'PayInfo_'.date('y_m_d').'.log');
//                $this->printf_info($order);
                $jsApiParameters = $tools->GetJsApiParameters($order);
                $this->assign('jsApiParameters',$jsApiParameters);
                if($order){
                    $data['appid'] = $input->GetAppid();
                    $data['mch_id'] = $input->GetMch_id();
                    $data['nonce_str'] = $input->GetNonce_str();
                    $data['prepay_id'] = $order['prepay_id'];
                    $data['result_code'] = $order['result_code'];
                    $data['return_code'] = $order['return_code'];
                    $data['return_msg'] = $order['return_msg'];
                    $data['sign'] = $input->GetSign();
                    $data['trade_type'] = $input->GetTrade_type();
                    $data['jsApiParameters'] = $jsApiParameters;
                    $ret = D('WxpayOrder')->add($data);
                }
                //获取共享收货地址js函数参数
//            $editAddress = $tools->GetEditAddressParameters();
//            $this->assign('editAddress',$editAddress);
            } catch(Exception $e) {
                Log::write(json_encode($e),'ERR');
            }
            //③、在支持成功回调通知中处理成功之后的事宜，见 notify.php

        }else{
            $notify = new NativePay();
            $input->SetTrade_type("NATIVE");
            $input->SetProduct_id("123456789");

            $order = $notify->GetPayUrl($input);
            if($order){
                $data['appid'] = $input->GetAppid();
                $data['mch_id'] = $input->GetMch_id();
                $data['nonce_str'] = $input->GetNonce_str();
                $data['prepay_id'] = $order['prepay_id'];
                $data['result_code'] = $order['result_code'];
                $data['return_code'] = $order['return_code'];
                $data['return_msg'] = $order['return_msg'];
                $data['sign'] = $input->GetSign();
                $data['trade_type'] = $input->GetTrade_type();
                $data['code_url'] = $order['code_url'];
                $ret = D('WxpayOrder')->add($data);
            }
        }


        if($ret){
            $result['status'] = 1;
            $result['msg'] = '下单成功';
            $result['order'] = $data;
        }else{
            $result['status'] = 0;
            $result['msg'] = '下单失败';
            $result['order'] = $data;
        }

        return $result;
    }



    public function  qrcode(){
        $url = urldecode($_GET["data"]);
        if(substr($url, 0, 6) == "weixin"){
             QRcode::png($url);
        }else{
            header('HTTP/1.1 404 Not Found');
        }
    }
}
