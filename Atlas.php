<?php

namespace AtlasLib;

require_once("Elipsoide.php");

/**
 * Main class for this bundle
 */
class Atlas{
    
    // Default threshold for the Bessel algorithm of the Inverse Geodesy Problem
    const DEFAULT_THRESHOLD = 0.0000000001; // pow(10,(-10))
    
    // Maximum distance between to geodetic point to use get the UTM distance
    const MAX_SPHER_DIST = 0;
    const DEGREES_PER_RADIAN = 180.0 / M_PI;
    
    // North hemisphere = 1
    // South hemisphere = -1    
    const HEMISPHERE = 1;
        
    /**
	 * Converts WGS84 coordinates RGF93 (Lambert 93)
	 * @param lon longitude
	 * @param lat latitude
	 * @return coordinates in RGF93 (Lambert 93)
	 */
    public static function WSG84toLambert93($longitude, $latitude){
        
        // System WGS84
        $A = 6378137; 
        $e = 0.08181919106;
        
        // Projections parameters
        
        $lc = deg2rad(3);
        $phi0 = deg2rad(46.5);
        $phi1 = deg2rad(44);
        $phi2 = deg2rad(49);
        
        $x0 = 700000; // origin
        $y0 = 6600000; // origin
                
        $phi = deg2rad($latitude);
        $l = deg2rad($longitude);
        
        $gN1 = $A / sqrt(1 - $e * $e * sin($phi1) * sin($phi1));
        $gN2 = $A / sqrt(1 - $e * $e * sin($phi2) * sin($phi2));
        
        $gl1 = log(tan(M_PI / 4 + $phi1 / 2) * pow( (1 - $e * sin($phi1)) / (1 + $e * sin($phi1)), $e / 2));
        $gl2 = log(tan(M_PI / 4 + $phi2 / 2) * pow( (1 - $e * sin($phi2)) / (1 + $e * sin($phi2)), $e / 2));
        $gl0 = log(tan(M_PI / 4 + $phi0 / 2) * pow( (1 - $e * sin($phi0)) / (1 + $e * sin($phi0)), $e / 2));
        $gl = log(tan(M_PI / 4 + $phi / 2) * pow( (1 - $e * sin($phi)) / (1 + $e * sin($phi)), $e / 2));
        
        $n = (log(($gN2 * cos($phi2)) / ($gN1 * cos($phi1)))) / ($gl1 - $gl2);
        
        $c = (($gN1 * cos($phi1)) / $n) * exp($n * $gl1);
        
        $ys = $y0 + $c * exp(-1 * $n * $gl0);

        $x93 = $x0 + $c * exp(-1 * $n * $gl) * sin($n * ($l - $lc));        
        $y93 = $ys - $c * exp(-1 * $n * $gl) * cos($n * ($l - $lc));
        
        return array('x' => $x93, 'y' => $y93);                
    }
    
    
    /**
	 * Converts WGS84 coordinates to the Lambert Zone I
	 * @param lon longitude
	 * @param lat latitude
	 * @return coordinates to Lambert Zone I
	 */
	public static function WGS84toLambertI($lon, $lat) {

        $lambda_w =  $lon * M_PI / 180;
        $phi_w = $lat * M_PI / 180;
        
        $a_w = 6378137.0;
        $b_w = 6356752.314;

        $e2_w = ($a_w * $a_w - $b_w * $b_w) / ($a_w * $a_w);

        $N = $a_w / sqrt(1 - $e2_w * pow(sin($phi_w), 2));

        $X_w = $N * cos($phi_w) * cos($lambda_w);
        $Y_w = $N * cos($phi_w) * sin($lambda_w);
        $Z_w = $N * (1 - $e2_w) * sin($phi_w);
           
        $dX = 168.0;
        $dY = 60.0;
        $dZ = -320.0;

        $X_n = $X_w + $dX;
        $Y_n = $Y_w + $dY;
        $Z_n = $Z_w + $dZ;
               
        $a_n = 6378249.2;
        $b_n = 6356515.0;

        $e2_n = ($a_n * $a_n - $b_n * $b_n) / ($a_n * $a_n);

        $epsilon = pow(10, -10);
       
        $p0 = atan($Z_n / sqrt($X_n * $X_n + $Y_n * $Y_n) * (1 - ($a_n * $e2_n) / (sqrt($X_n * $X_n + $Y_n * $Y_n + $Z_n * $Z_n))));
        $p1 = atan(($Z_n / sqrt($X_n * $X_n + $Y_n * $Y_n))/(1 - ($a_n * $e2_n * cos($p0)) / (sqrt(($X_n * $X_n + $Y_n * $Y_n) * (1 - $e2_n * pow(sin($p0), 2))))));
                
        while(!(abs($p1 - $p0) < $epsilon)){
            $p0 = $p1; 
            $p1 = atan(($Z_n / sqrt($X_n * $X_n + $Y_n * $Y_n)) / (1 - ($a_n * $e2_n * cos($p0)) / (sqrt(($X_n * $X_n + $Y_n * $Y_n) * (1 - $e2_n * pow(sin($p0), 2)))))); 
        }
        
        $phi_n = $p1;
        $lambda_n = atan($Y_n / $X_n);
        
        $n = 0.7604059656;
        $c = 11603796.98;
        $Xs = 600000.0;
        $Ys = 5657616.674;
            
        $e_n = sqrt($e2_n);
        $lambda0 = 0.04079234433198;   
        
        $L = log(tan(M_PI / 4 + $phi_n / 2) * pow(((1 - $e_n * sin($phi_n)) / (1 + $e_n * sin($phi_n))), ($e_n / 2)));

        $X_lz1 = $Xs + $c * exp((-$n * $L)) * sin($n * ($lambda_n - $lambda0));
        $Y_lz1 = $Ys - $c * exp((-$n * $L)) * cos($n * ($lambda_n - $lambda0));
                
        return array('x' => $X_lz1, 'y' => $Y_lz1);
    }
	
	/**
	 * Converts WGS84 coordinates to Lambert Zone II coordinates
	 * @param lon longitude
	 * @param lat latitude
	 * @return coordinates to Lambert Zone II coordinates
	 */
	public static function WGS84toLambertII($lon, $lat) {

        $lambda_w =  $lon * M_PI / 180 ;
        $phi_w = $lat * M_PI / 180 ;
                
        $a_w = 6378137.0;
        $b_w = 6356752.314;

        $e2_w = ($a_w * $a_w - $b_w * $b_w) / ($a_w * $a_w);

        $N = $a_w / sqrt(1 - $e2_w * pow(sin($phi_w), 2));

        $X_w = $N * cos($phi_w) * cos($lambda_w);
        $Y_w = $N * cos($phi_w) * sin($lambda_w);
        $Z_w = $N * (1 - $e2_w) * sin($phi_w);
        
        $dX = 168.0;
        $dY = 60.0;
        $dZ = -320.0;

        $X_n = $X_w + $dX;
        $Y_n = $Y_w + $dY;
        $Z_n = $Z_w + $dZ;
        
        $a_n = 6378249.2;
        $b_n = 6356515.0;

        $e2_n = ($a_n * $a_n - $b_n * $b_n)/($a_n * $a_n);

        $epsilon = pow(10, -10);
       
        $p0 = atan($Z_n / sqrt($X_n * $X_n + $Y_n * $Y_n) * (1 - ($a_n * $e2_n) / (sqrt($X_n * $X_n + $Y_n * $Y_n + $Z_n * $Z_n))));
        $p1 = atan(($Z_n / sqrt($X_n * $X_n + $Y_n * $Y_n)) / (1 - ($a_n * $e2_n * cos($p0)) / (sqrt(($X_n * $X_n + $Y_n * $Y_n) * (1 - $e2_n * pow(sin($p0) , 2))))));
                        
        while(!(abs($p1 - $p0) < $epsilon)){

            $p0 = $p1;
            $p1 = atan(($Z_n / sqrt($X_n * $X_n + $Y_n * $Y_n)) / (1 - ($a_n * $e2_n * cos($p0)) / (sqrt(($X_n * $X_n + $Y_n * $Y_n) * (1 - $e2_n * pow(sin($p0), 2)))))); 
        }
        
        $phi_n = $p1;
        $lambda_n = atan($Y_n / $X_n);
        
        $n = 0.7289686274;
        $c = 11745793.39;
        $Xs = 600000.0;
        $Ys = 6199965.768;
            
        $e_n = sqrt($e2_n);
        $lambda0 = 0.04079234433198;   
        
        $L = log(tan(M_PI / 4 + $phi_n / 2) * pow(((1 - $e_n * sin($phi_n)) / (1 + $e_n * sin($phi_n))),($e_n / 2)));

        $X_lz2 = $Xs + $c * exp((-$n * $L)) * sin($n * ($lambda_n - $lambda0));
        $Y_lz2 = $Ys - $c * exp((-$n * $L)) * cos($n * ($lambda_n - $lambda0));
                
        return array('x' => $X_lz2, 'y' => $Y_lz2);        
    }
	
	/**
	 * Converts WGS84 coordinates to the Extended Lambert II	 
	 * @param lon longitude
	 * @param lat latitude
	 * @return coordinates in extented Lambert II
	 */
	public static function WGS84toLambertIIE($lon, $lat){

        $lambda_w = $lon * M_PI / 180;
        $phi_w = $lat * M_PI / 180;
                
        $a_w = 6378137.0;
        $b_w = 6356752.314;

        $e2_w = ($a_w * $a_w - $b_w * $b_w) / ($a_w * $a_w);

        $N = $a_w / sqrt(1 - $e2_w * pow(sin($phi_w), 2));

        $X_w = $N * cos($phi_w) * cos($lambda_w);
        $Y_w = $N * cos($phi_w) * sin($lambda_w);
        $Z_w = $N * (1 - $e2_w) * sin($phi_w);
        
        $dX = 168.0;
        $dY = 60.0;
        $dZ = -320.0;

        $X_n = $X_w + $dX;
        $Y_n = $Y_w + $dY;
        $Z_n = $Z_w + $dZ;
        
        $a_n = 6378249.2;
        $b_n = 6356515.0;

        $e2_n = ($a_n * $a_n - $b_n * $b_n) / ($a_n * $a_n);

        $epsilon = pow(10, -10);
       
        $p0 = atan($Z_n / sqrt($X_n * $X_n + $Y_n * $Y_n) * (1 - ($a_n * $e2_n) / (sqrt($X_n * $X_n + $Y_n * $Y_n + $Z_n * $Z_n))));
        $p1 = atan(($Z_n / sqrt($X_n * $X_n + $Y_n * $Y_n)) / (1 - ($a_n * $e2_n * cos($p0)) / (sqrt(($X_n * $X_n + $Y_n * $Y_n) * (1 - $e2_n * pow(sin($p0), 2))))));
                
        while(!(abs($p1 - $p0) < $epsilon)){

            $p0 = $p1;
            $p1 = atan(($Z_n / sqrt($X_n * $X_n + $Y_n * $Y_n)) / (1 - ($a_n * $e2_n * cos($p0)) / (sqrt(($X_n * $X_n + $Y_n * $Y_n) * (1 - $e2_n * pow(sin($p0) , 2)))))); 
        }
        
        $phi_n = $p1;
        $lambda_n = atan($Y_n / $X_n);
        
        $n = 0.7289686274;
        $c = 11745793.39;
        $Xs = 600000.0;
        $Ys = 8199695.768;
            
        $e_n = sqrt($e2_n);
        $lambda0 = 0.04079234433198;   
        
        $L =log(tan(M_PI / 4 + $phi_n / 2) * pow(((1 - $e_n * sin($phi_n)) / (1 + $e_n * sin($phi_n))), ($e_n / 2)));


        $X_l2e = $Xs + $c * exp((-$n * $L)) * sin($n * ($lambda_n - $lambda0));
        $Y_l2e = $Ys - $c * exp((-$n * $L)) * cos($n * ($lambda_n - $lambda0));
        
        return array('x' => $X_l2e, 'y' => $Y_l2e);
    }
    
    /**
	 * Converts WGS84 coordinates to the Lambert Zone III coordinates
	 * @param lon longitude
	 * @param lat latitude
	 * @return coordinates in Lambert Zone III
	 */
	public static function WGS84toLambertIII($lon, $lat){

        $lambda_w = $lon * M_PI / 180 ;
        $phi_w = $lat * M_PI / 180 ;
                
        $a_w = 6378137.0;
        $b_w = 6356752.314;

        $e2_w = ($a_w * $a_w - $b_w * $b_w) / ($a_w * $a_w);

        $N = $a_w / sqrt(1 - $e2_w * pow(sin($phi_w), 2));

        $X_w = $N * cos($phi_w) * cos($lambda_w);
        $Y_w = $N * cos($phi_w) * sin($lambda_w);
        $Z_w = $N * (1 - $e2_w) * sin($phi_w);

        $dX = 168.0;
        $dY = 60.0;
        $dZ = -320.0;

        $X_n = $X_w + $dX;
        $Y_n = $Y_w + $dY;
        $Z_n = $Z_w + $dZ;
        
        $a_n = 6378249.2;
        $b_n = 6356515.0;

        $e2_n = ($a_n * $a_n - $b_n * $b_n) / ($a_n * $a_n);

        $epsilon = pow(10,-10);
       
        $p0 = atan($Z_n / sqrt($X_n * $X_n + $Y_n * $Y_n) * (1 - ($a_n * $e2_n) / (sqrt($X_n * $X_n + $Y_n * $Y_n + $Z_n * $Z_n))));
        $p1 = atan(($Z_n / sqrt($X_n * $X_n + $Y_n * $Y_n)) / (1 - ($a_n * $e2_n * cos($p0)) / (sqrt(($X_n * $X_n + $Y_n * $Y_n) * (1 - $e2_n * pow(sin($p0), 2))))));
                
        while(!(abs($p1 - $p0) < $epsilon))
        {

            $p0 = $p1;
            $p1 = atan(($Z_n / sqrt($X_n * $X_n + $Y_n * $Y_n)) / (1 - ($a_n * $e2_n * cos($p0)) / (sqrt(($X_n * $X_n + $Y_n * $Y_n) * (1 - $e2_n * pow(sin($p0), 2)))))); 
        }
        
        $phi_n = $p1;
        $lambda_n = atan($Y_n / $X_n);
        
        $n = 0.6959127966;
        $c = 11947992.52;
        $Xs = 600000.0;
        $Ys = 6791905.085;
            
        $e_n = sqrt($e2_n);
        $lambda0 = 0.04079234433198;   
        
        $L = log(tan(M_PI / 4 + $phi_n / 2) * pow(((1 - $e_n * sin($phi_n)) / (1 + $e_n * sin($phi_n))), ($e_n / 2)));

        $X_lz3 = $Xs + $c * exp((-$n * $L)) * sin($n * ($lambda_n - $lambda0));
        $Y_lz3 = $Ys - $c * exp((-$n * $L)) * cos($n * ($lambda_n - $lambda0));
        
        return array('x' => $X_lz3, 'y' => $Y_lz3);
    }
	

	/**
	 * Converts WGS84 coordinates to the Lambert Zone IV
	 * @param lon longitude
	 * @param lat latitude
	 * @return coordinates to Lambert Zone IV
	 */
	public static function WGS84toLambertIV($lon, $lat){

        $lambda_w =  $lon * M_PI / 180 ;
        $phi_w = $lat * M_PI / 180 ;
                
        $a_w = 6378137.0;
        $b_w = 6356752.314;

        $e2_w = ($a_w * $a_w - $b_w * $b_w) / ($a_w * $a_w);

        $N = $a_w / sqrt(1 - $e2_w * pow(sin($phi_w), 2));

        $X_w = $N * cos($phi_w) * cos($lambda_w);
        $Y_w = $N * cos($phi_w) * sin($lambda_w);
        $Z_w = $N * (1 - $e2_w) * sin($phi_w);
        
        $dX = 168.0;
        $dY = 60.0;
        $dZ = -320.0;

        $X_n = $X_w + $dX;
        $Y_n = $Y_w + $dY;
        $Z_n = $Z_w + $dZ;
        
        $a_n = 6378249.2;
        $b_n = 6356515.0;

        $e2_n = ($a_n * $a_n - $b_n * $b_n) / ($a_n * $a_n);

        $epsilon = pow(10, -10);
       
        $p0 = atan($Z_n / sqrt($X_n * $X_n + $Y_n * $Y_n) * (1 - ($a_n * $e2_n) / (sqrt($X_n * $X_n + $Y_n * $Y_n + $Z_n * $Z_n))));
        $p1 = atan(($Z_n / sqrt($X_n * $X_n + $Y_n * $Y_n)) / (1 - ($a_n * $e2_n * cos($p0)) / (sqrt(($X_n * $X_n + $Y_n * $Y_n) * (1 - $e2_n * pow(sin($p0), 2))))));
                
        while(!(abs($p1 - $p0) < $epsilon)){

            $p0 = $p1;
            $p1 = atan(($Z_n / sqrt($X_n * $X_n + $Y_n * $Y_n)) / (1 - ($a_n * $e2_n * cos($p0)) / (sqrt(($X_n * $X_n + $Y_n * $Y_n) * (1 - $e2_n * pow(sin($p0), 2)))))); 
        }
        
        $phi_n = $p1;
        $lambda_n = atan($Y_n / $X_n);
        
        $n = 0.6712679322;
        $c = 12136281.99;
        $Xs = 234.358;
        $Ys = 7239161.542;
            
        $e_n = sqrt($e2_n);
        $lambda0 = 0.04079234433198;   
        
        $L = log(tan(M_PI / 4 + $phi_n / 2) * pow(((1 - $e_n * sin($phi_n)) / (1 + $e_n * sin($phi_n))), ($e_n / 2)));


        $X_lz4 = $Xs + $c * exp((-$n * $L)) * sin($n * ($lambda_n - $lambda0));
        $Y_lz4 = $Ys - $c * exp((-$n * $L)) * cos($n * ($lambda_n - $lambda0));
        
        return array('x' => $X_lz4, 'y' => $Y_lz4);
    }
}