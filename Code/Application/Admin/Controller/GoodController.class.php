<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Log;


class GoodController extends Controller {
    protected $HARD_SOFT = 1;
    protected $HARD_SOFT_PRICE_TYPE = array(
        30 => 2800
    );
    protected $GJB_RAND = 4;
    protected $GJB_RAND_PRICE_TYPE = array(
        1 => 200,
        10 => 600,
        30 => 1500
    );
    protected $GJB_FIXED = 5;
    protected $GJB_FIXED_PRICE_TYPE = array(
        1 => 200,
        10 => 600,
        30 => 1500
    );
    /**
     * @var int 变机宝
     */
    protected $BJB_NO_IP = 6;
    protected $BJB_NO_IP_PRICE_TYPE = array(
        1 => 300,
        7 => 2100,
        30 => 6000
    );

    protected $BJB_HAVE_IP = 7;
    protected $BJB_HAVE_IP_PRICE_TYPE = array(
        1 => 800,
        7 => 4100,
        30 => 12000
    );
    protected $good_list = array(
    );

    public function index(){
        $this->display();
    }
    public function create_hardsoft(){
        $this->display();
    }
    public function create_wuji(){
        $this->display();
    }
    public function create_wuji_single(){
        $this->display();
    }
    public function create_gjb_fixed(){
        $data  = A('Gjb')->app_list();
        $this->assign('app_list',$data['list']);
        $this->display();
    }
    public function create_gjb_rand(){
        $this->display();
    }
    public function create_bjb(){
        $this->display();
    }

    /**
     * 检查订单可用性
     */
    public function check_order(){
        $order = I('order');
        $point = null;
        switch ($order['goodtype']){
            case $this->HARD_SOFT:
                $point = $this->HARD_SOFT_PRICE_TYPE[$order['timetype']] * $order['num'];
                break;
            case $this->GJB_RAND:
                $point = $this->GJB_RAND_PRICE_TYPE[$order['timetype']] * $order['num'];
                break;
            case $this->GJB_FIXED:
                $point = $this->GJB_FIXED_PRICE_TYPE[$order['timetype']] * $order['num'];
                break;
            case $this->BJB_NO_IP:
                $point = $this->BJB_NO_IP_PRICE_TYPE[$order['timetype']] * $order['num'];
                $check_user = A('Bjb')->check_user($order['account']);
                break;
            case $this->BJB_HAVE_IP:
                $point = $this->BJB_HAVE_IP_PRICE_TYPE[$order['timetype']] * $order['num'];
                break;
            default:
                break;
        }
        if(empty($point)){
            $return['status'] = 0;
            $return['msg'] = '请求错误';
        }elseif ($check_user['status']==0){
            $return['status'] = 0;
            $return['msg'] = $check_user['msg'];
        }else{

            $point_info = $this->check_point($point);
            if($point_info['status']){
                $return['status'] = 1;
                $return['msg'] = '允许下单';
                $return['before'] = $point_info['before'];
                $return['need'] = $point_info['need'];;
                $return['after'] = $point_info['after'];
            }else{
                $return['status'] = 0;
                $return['msg'] = $point_info['msg'];
            }
        }
        $this->ajaxReturn($return);
    }

    /**
     * 检查余额
     * @param $good_point
     * @return mixed
     */
    public function check_point($good_point){
        $usermodel = D('SysUser');
        $user = session('user_auth');
        if($user){
            $user = $usermodel->find($user['id']);
        }else{
            $return['status'] = 0;
            $return['msg'] = '请重新登录!';
            return $return;
        }

        if($user['point'] >= intval($good_point)){
            $return['status'] = 1;
            $return['user_id'] = $user['id'];
            $return['before'] = intval($user['point']);
            $return['after'] = intval($user['point']) - intval($good_point);
            $return['need'] = intval($good_point);
            return $return;
        }else{
            $return['status'] = 0;
            $return['msg'] = '点数不足，请先充值!';
            return $return;
        }
    }

    /**
     * 创建订单
     */
    public function create_order(){
        $order = I('order');

        switch ($order['goodtype']){
            case $this->HARD_SOFT:
                $order['point'] = $this->HARD_SOFT_PRICE_TYPE[$order['timetype']] * $order['num'];
                $ret = $this->save_order($order);
                break;
            case $this->GJB_RAND:
                $order['point'] = $this->GJB_RAND_PRICE_TYPE[$order['timetype']] * $order['num'];
                $ret = $this->save_order($order);
                break;
            case $this->GJB_FIXED:
                $order['point'] = $this->GJB_FIXED_PRICE_TYPE[$order['timetype']] * $order['num'];
                $ret = $this->save_order($order);
                break;
            case $this->BJB_NO_IP:
                $order['point'] = $this->BJB_NO_IP_PRICE_TYPE[$order['timetype']] * $order['num'];
                $ret = $this->save_order($order);
                break;
            case $this->BJB_HAVE_IP:
                $order['point'] = $this->BJB_HAVE_IP_PRICE_TYPE[$order['timetype']] * $order['num'];
                $ret = $this->save_order($order);
                break;
            default:
                $ret['status'] = 0;
                $ret['msg'] = '请求错误';
                break;
        }
        if($ret['status']){
            $return['status'] = 1;
            $return['msg'] = '下单成功';
            $return['price'] = $order['price'];
        }else{
            $return['status'] = 0;
            $return['msg'] = $et['msg'];
        }

        $this->ajaxReturn($return);
    }

    /**
     * 保存订单
     * @param $order
     * @return mixed
     */
    public function save_order($order){

        $model = D('WujiOrder');
        $usermodel = D('SysUser');
        $point_info = $this->check_point($order['point']);

        if($point_info['status']){
            M('')->startTrans();
            $order['user_id'] = $point_info['user_id'];
            $order['order_id']  =  "GD".date("YmdHis");
            if($data = $model->create($order)){
                $ret = $model->add($data);
                if($ret){
                    $return['status'] = 1;
                    $return['msg'] = $ret;
                    $is_update = $usermodel->where(array('id'=>$point_info['user_id']))->setDec('point',$point_info['need']);
                    if($is_update){
                        M('')->commit();
                        $return['status'] = 1;
                        $return['msg'] = '下单成功';
                    }else{
                        M('')->rollback();
                        $return['status'] = 0;
                        $return['msg'] =  $usermodel->getError();
                    }
                }else{
                    M('')->rollback();
                    $ret = $model->getError();
                    $return['status'] = 0;
                    $return['msg'] = $ret;
                }
            }else{
                $ret = $model->getError();
                $return['status'] = 0;
                $return['msg'] = $ret;
            }
        }else{
            $return['status'] = 0;
            $return['msg'] = $point_info['msg'];
        }

        return $return;
    }

    public function pay(){

    }
}