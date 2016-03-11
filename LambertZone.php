<?php

namespace Lambert;

class Zone extends SplEnum {
    const __default = self::LambertIIExtended;
    
    const LambertI = 0;
    const LambertII = 1;
    const LambertIII = 2;
    const LambertIV = 3;
    const LambertIIExtended = 4;
    const Lambert93 = 5;
}

class LambertZone{
    
    private $LAMBERT_N = array(0.7604059656, 0.7289686274, 0.6959127966, 0.6712679322, 0.7289686274, 0.7256077650);
    private $LAMBERT_C = array(11603796.98, 11745793.39, 11947992.52, 12136281.99, 11745793.39, 11754255.426);
    private $LAMBERT_XS = array(600000.0, 600000.0, 600000.0, 234.358, 600000.0, 700000.0);
    private $LAMBERT_YS = array(5657616.674, 6199695.768, 6791905.085, 7239161.542, 8199695.768, 12655612.050);
    
    public $DEFAULT_EPS = null;
    public $E_CLARK_IGN = 0.08248325676;
    public $E_WGS84 = 0.08181919106;
    public $A_CLARK_IGN = 6378249.2;
    public $A_WGS84 =  6378137.0;
    public $LON_MERID_PARIS = 0;
    public $LON_MERID_GREENWICH =0.04079234433;
    public $LON_MERID_IERS = null;
    
    public $lambertZone;
    
    public function __construct($zone){
        $this->DEFAULT_EPS = 1 * 10 * pow(-10);
        $this->LON_MERID_IERS = 3.0 * M_PI / 180.0;
        
        $this->lambertZone = $zone;
    }
    
    public function n(){
        $this->LAMBERT_N[intval($this->lambertZone)];
    }
    
    public function c(){
        $this->LAMBERT_C[intval($this->lambertZone)];
    }
    
    public function xs(){
        $this->LAMBERT_XS[intval($this->lambertZone)];
    }
    
    public function ys(){
        $this->LAMBERT_YS[intval($this->lambertZone)];
    }
}