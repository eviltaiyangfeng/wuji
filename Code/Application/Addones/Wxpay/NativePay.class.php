<?php
/**
 * Created by PhpStorm.
 * User: zhouz
 * Date: 2018/8/28
 * Time: 15:53
 */

namespace Addones\Wxpay;

/**
 *
 * example目录下为简单的支付样例，仅能用于搭建快速体验微信支付使用
 * 样例的作用仅限于指导如何使用sdk，在安全上面仅做了简单处理， 复制使用样例代码时请慎重
 * 请勿直接直接使用样例对外提供服务
 *
 **/

use Addones\Wxpay\Lib\WxPayApi;
use Think\Exception;
use Think\Log;
/**
 *
 * 刷卡支付实现类
 * @author widyhu
 *
 */
class NativePay{

    /**
     * 参数数组转换为url参数
     * @param array $urlObj
     * @return string
     */
    private function ToUrlParams($urlObj)
    {
        $buff = "";
        foreach ($urlObj as $k => $v)
        {
            $buff .= $k . "=" . $v . "&";
        }

        $buff = trim($buff, "&");
        return $buff;
    }

    /**
     * 生成直接支付url，支付url有效期为2小时,模式二
     * @param UnifiedOrderInput $input
     * @param $input
     * @return bool
     */
    public function GetPayUrl($input)
    {
        if($input->GetTrade_type() == "NATIVE")
        {
            try{
                $config = new WxPayConfig();
                $result = WxPayApi::unifiedOrder($config, $input);
                return $result;
            } catch(Exception $e) {
                Log::write(json_encode($e),'ERR','',C('LOG_PATH').'PayInfo_'.date('y_m_d').'.log');
            }
        }
        return false;
    }
}