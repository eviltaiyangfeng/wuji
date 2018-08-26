<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Log;


class GoodController extends Controller {

    protected $GJB_RAND = 4;
    protected $GJB_RAND_PRICE_TYPE = array(
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

    public function api(){
        $order = I('order');

        switch ($order['goodtype']){
            case $this->GJB_RAND:
                $order['price'] = $this->GJB_RAND_PRICE_TYPE[$order['timetype']] * $order['num'];
                $ret = $this->create_order($order);
                break;
            default:
                break;
        }
        if($ret){
            $return['status'] = 1;
            $return['msg'] = '下单成功';
            $return['price'] = $order['price'];
        }else{
            $return['status'] = 0;
            $return['msg'] = '下单失败';
        }

        $this->ajaxReturn($return);
    }

    public function create_order($order){
        $model = D('WujiOrder');
        if($data = $model->create($order)){
            $ret = $model->add($data);
        }else{
            $ret = $model->getError();
        }
        return $ret;
    }
}