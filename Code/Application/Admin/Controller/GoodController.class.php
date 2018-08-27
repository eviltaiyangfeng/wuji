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

    public function create_order(){
        $order = I('order');

        switch ($order['goodtype']){
            case $this->HARD_SOFT:
                $order['price'] = $this->HARD_SOFT_PRICE_TYPE[$order['timetype']] * $order['num'];
                $ret = $this->save_order($order);
                break;
            case $this->GJB_RAND:
                $order['price'] = $this->GJB_RAND_PRICE_TYPE[$order['timetype']] * $order['num'];
                $ret = $this->save_order($order);
                break;
            case $this->GJB_FIXED:
                $order['price'] = $this->GJB_FIXED_PRICE_TYPE[$order['timetype']] * $order['num'];
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
            $return['msg'] = $ret['msg'];
        }

        $this->ajaxReturn($return);
    }

    public function save_order($order){
        $model = D('WujiOrder');
        if($data = $model->create($order)){
            $ret = $model->add($data);
            if($ret){
                $return['status'] = 1;
                $return['msg'] = $ret;
            }else{
                $ret = $model->getError();
                $return['status'] = 0;
                $return['msg'] = $ret;
            }
        }else{
            $ret = $model->getError();
            $return['status'] = 0;
            $return['msg'] = $ret;
        }
        return $return;
    }

    public function pay(){

    }
}