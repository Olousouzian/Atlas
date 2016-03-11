<?php

namespace Lambert;

class Unit{
    
    public $Degree = null;
    public $Grad = null;
    public $Radian = null;
    public $Meter = null;
}

class Point{
    private $radianTodegree = 180.0/M_PI;
    public $x;
    public $y;
    public $z;
    
    public function __construct($x, $y, $z){
        $this->x = $x;
        $this->y = $y;
        $this->z = $z;
    }
    
    public function translate($x, $y, $z){
        $this->x += $x;
        $this->y += $y;
        $this->z += $z;
    }
    
    private function scale($scale){
        $this->x *= $scale;
        $this->y *= $scale;
        $this->z *= $scale;
    }
    
    public function toDegree(){
        $this->scale($this->radianTodegree);
    }    
} 