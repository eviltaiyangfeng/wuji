<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Log;


class UserController extends Controller {

    protected $IS_PAY_DEBUG = 1;

    public function welcome(){
        $this->display();
    }

    public function order(){
        $this->display();
    }
    public function recharge(){
        $this->display();
    }
    public function chargelist(){
        $this->display();
    }
    public function changepwd(){
        $this->display();
    }
    public function login_out(){
        $this->display();
    }

    public function recharge_order(){

        $user = session('user_auth');
        if(empty($user)){
            $return['status'] = 0;
            $return['msg'] = '请先登录';
        }else{
            $model = D('WujiUserOrder');
            $amount = I('amount');
            if($this->IS_PAY_DEBUG){
                $amount = 0.01;
            }
            $data['fk_user'] = $user['id'];
            $data['money'] = $amount;
            $data['amount'] = $amount * 100;
            $data['type'] = 'RECHARGE';
            $data['create_time'] = date("Y-m-d H:i:s");
            $data['order_id'] =  "RC".date("YmdHis");
            $ret = $model->add($data);
            if ($ret){
                $param['body'] = "无极充值";
                $param['attach'] = "";
                $param['out_trade_no'] = $data['order_id'];
                $param['fee'] = $data['money'] * 100;
                $param['notify_url'] = C('WEIXIN_PAY_CONFIG.NOTIFY_URL');
                if(ismobile()){
                    $param['is_mobile'] = true;
                }else{
                    $param['is_mobile'] = false;
                }
                $order_info = A('Pay')->create_weixin_order($param);
                if($order_info['status']){
                    $return['status'] = 1;
                    $return['msg'] = '下单成功';
                    $return['price'] = $amount;
                    $return['out_trade_no'] = $param['out_trade_no'];
                    $return['order'] = $order_info['order'];
                }else{
                    $return['status'] = 0;
                    $return['msg'] =  '下单失败';
                }
            }else{
                $return['status'] = 0;
                $return['msg'] = $model->getError();
            }
        }
        $this->ajaxReturn($return);
    }
}