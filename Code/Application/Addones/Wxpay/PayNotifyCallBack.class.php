<?php
/**
 * Created by PhpStorm.
 * User: administer
 * Date: 2018/8/28
 * Time: 0:39
 */

namespace Addones\Wxpay;
use Addones\Wxpay\Lib\WxPayNotify;
use Addones\Wxpay\Lib\WxPayOrderQuery;
use Addones\Wxpay\Lib\WxPayApi;
use Think\Exception;
use Think\Log;
class PayNotifyCallBack extends WxPayNotify
{
    //查询订单
    public function Queryorder($transaction_id)
    {
        $input = new WxPayOrderQuery();
        $input->SetTransaction_id($transaction_id);

        $config = new WxPayConfig();
        $result = WxPayApi::orderQuery($config, $input);
        Log::write("query:" . json_encode($result),'', C('LOG_PATH').'PayInfo_'.date('y_m_d').'.log');
        if(array_key_exists("return_code", $result)
            && array_key_exists("result_code", $result)
            && $result["return_code"] == "SUCCESS"
            && $result["result_code"] == "SUCCESS")
        {
            return true;
        }
        return false;
    }
    /**
     *
     * 回包前的回调方法
     * 业务可以继承该方法，打印日志方便定位
     * @param string $xmlData 返回的xml参数
     *
     **/
    public function LogAfterProcess($xmlData)
    {
        Log::write("call back， return xml:" . $xmlData,'', C('LOG_PATH').'PayInfo_'.date('y_m_d').'.log');
        return;
    }
    //重写回调处理函数
    /**
     * @param WxPayNotifyResults $data 回调解释出的参数
     * @param WxPayConfigInterface $config
     * @param string $msg 如果回调处理失败，可以将错误信息输出到该方法
     * @return true回调出来完成不需要继续回调，false回调处理未完成需要继续回调
     */
    public function NotifyProcess($objData, $config, &$msg)
    {
        $data = $objData->GetValues();
        //TODO 1、进行参数校验
        if(!array_key_exists("return_code", $data)
            ||(array_key_exists("return_code", $data) && $data['return_code'] != "SUCCESS")) {
            //TODO失败,不是支付成功的通知
            //如果有需要可以做失败时候的一些清理处理，并且做一些监控
            $msg = "异常异常";
            return false;
        }
        if(!array_key_exists("transaction_id", $data)){
            $msg = "输入参数不正确";
            return false;
        }

        //TODO 2、进行签名验证
        try {
            $checkResult = $objData->CheckSign($config);
            if($checkResult == false){
                //签名错误
                Log::ERROR("签名错误...");
                return false;
            }
        } catch(Exception $e) {
            Log::ERROR(json_encode($e));
        }

        //TODO 3、处理业务逻辑
        Log::write("call back:" . json_encode($data));
        $notfiyOutput = array();


        //查询订单，判断订单真实性
        if(!$this->Queryorder($data["transaction_id"])){
            $msg = "订单查询失败";
            return false;
        }else{
            //TODO 4、处理业务逻辑
            $where['order_id'] = $data['out_trade_no'];
            $save['openid'] = $data['openid'];
            $save['total_fee'] = $data['total_fee'];
            $save['result_code'] = $data['result_code'];
            $save['transaction_id'] = $data['transaction_id'];
            $save['status'] = 1;
            $save['notify_data'] = json_encode($data);
            D('WujiUserOrder')->where($where)->save($save);
        }
        return true;
    }
}