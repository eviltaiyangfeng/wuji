<?php
namespace Common\Model;
use Think\Model;
class WujiOrderModel extends Model {

    /* 用户模型自动完成 */
    protected $_auto = array(
        array('create_time',date,self::MODEL_INSERT,'function',array('Y-m-d H:i:s')),
        array('create_ip', 'get_client_ip', self::MODEL_INSERT, 'function', 1),
        array('status', 0, self::MODEL_INSERT),
    );

}