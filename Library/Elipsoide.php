<?php

namespace AtlasLib;

class Elipsoide {
    
    public $WGS84;
    public $ED50;
    public $INTL_1924;
    public $BESSEL_1841;
    
    // Semi-mayor Axis [double]    
    private $a;
    
    // Flattening [double]
    private $f;
    
    // Semi-minor Axis [double]
    private $b;
    
    // First Excentricity [double]
    private $pe;
    
    // First Excentricity ^2 [double]
    private $pe2;
    
    // Second Excentricity [double]
    private $se;
    
    // Second Excentricity ^2 [double]
    private $se2;
    
    // Polar radius [double]
    private $c;
    
    /**
     * Constructor
     * double $a : Semimayor Axis elipsoide
     * double $b : Flattening elipsoide
     */
    function __construct($a, $f){
        
        $this->WGS84 = new Elipsoide(6378137.0, 1 / 298.257223563);
        $this->ED50 = new Elipsoide(6378388.0, 1 / 297.0);
        $this->INTL_1924 = new Elipsoide(6378388.0, 1.0 / 297.0);
        $this->BESSEL_1841 = new Elipsoide(6377397.155, 1.0 / 299.1528128);
        
        
        $this->a = $a;
        $this->f = $f;
        
        $this->b = $this->a * (1 - $this->f);
        $this->pe = sqrt(((pow($a, 2) - (pow($b, 2))) / (pow($a, 2))));
        $this->se = sqrt(((pow($a, 2) - (pow($b, 2))) / (pow($b, 2))));
        $this->pe2 = pow($this->pe, 2);
        $this->se2 = pow($this->se, 2);
        $this->c = (pow($this->a, 2) / $this->b);
    }
    
    // Get the semimayor axis
    public function getA(){
        return $this->a;
    }
    
    // Get the semiminor axis
    public function getB(){
        return $this->b;
    }
    
    // Get Flattening
    public function getF(){
        return $this->f;
    } 
    
    // Get the first excentricity
    public function getPe(){
        return $this->pe;
    }
    
    // Get the first excentricity ^2
    public function getPe2(){
        return $this->pe2;
    }
    
    // Get the second excentricity
    public function getSe(){
        return $this->se;
    }
    
    // Get the second excentricity ^2
    public function getSe2(){
        return $this->se2;
    }
    
    // Get the radius
    public function getC(){
        return $this->c;
    }
    
    public static function getED50() {        
        return new Elipsoide(6378388.0, 1 / 297.0);
    }
    
    public static function getWGS84() {
        return new Elipsoide(6378137.0, 1 / 298.257223563);
    }
}