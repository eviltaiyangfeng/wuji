<?php
namespace Home\Controller;

use Common\Controller\CommonController;

class GoodController extends CommonController{
    protected $MENU_INDEX_MOBILE = 2;
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        parent::setMobileMenu($this->MENU_INDEX_MOBILE);

    }

    public function index(){
        $this->display();
    }


}