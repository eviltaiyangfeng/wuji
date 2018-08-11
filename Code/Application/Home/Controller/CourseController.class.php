<?php
namespace Home\Controller;
use Common\Controller\CommonController;
class CourseController extends CommonController {
    protected $VIDEO_MENU_INDEX_MOBILE = 3;
    protected $VIDEO_MENU_INDEX = 6;
    protected $IMG_MENU_INDEX_MOBILE = 4;
    protected $IMG_MENU_INDEX = 7;

    public function index(){
        $this->display();
    }
    public function video(){
        parent::setMobileMenu($this->VIDEO_MENU_INDEX_MOBILE);
        parent::setMenu($this->VIDEO_MENU_INDEX);
        $this->display();
    }
    public function img(){
        parent::setMobileMenu($this->IMG_MENU_INDEX_MOBILE);
        parent::setMenu($this->IMG_MENU_INDEX);
        $this->display();
    }
}