<?php

namespace AtlasLib;

require_once("Elipsoide.php");

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
     * The method males the conversion between geodesic coordinates to projected coordiantes in a UTM zone forced
     */     
    public static function geo2utm($lon, $lat, $elip, $forced){
        
        // Elipsoide
        $se2 = $elip->getSe2();
        $c = $elip->getC();


        // Correct the long or lat        
        if ($lon > 180){
            $lon = 180;
        } else if ($lon < -180){
            $lon = -180;
        }
        
        if ($lat > 90){
            $lat = 90;
        } else if ($lat < -90){
            $lat = -90;
        }
        
        // Degrees to radians
        $rlon = ($lon * M_PI) / 180.0;
        $rlat = ($lat * M_PI) / 180.0;
        
        // Calculate the zone
        $hus = intval($forced);
        $huso = doubleval($hus);
        
        // Average longitude of the zone
        $lonmedia =($huso * 6.0) - 183;
        
        // Angular distance between the point and the central meridian of the zone
        $rilon = $rlon - (($lonmedia * M_PI) / 180.0);
        
        // Operates
        $A = (cos($rlat)) * (sin($rilon));
        $CC = (0.5) * (log((1 + A) / (1 - A)));
        $n = (atan((tan($rlat)) / (cos($rilon)))) - $rlat;
        $v = ($c * 0.9996) / sqrt((1 + $se2 * pow(cos($rilon), 2)));
        $S = ($se2 * pow($CC, 2) * pow(cos($rlat), 2)) / 2.0;
        $A1 = sin(2 * $rlat);
        $A2 = $A1 * pow(cos($rlat), 2);
        $J2 = $rlat + ($A1 / 2);
        $J4 = (3 * $J2 + $A2) / 4;
        $J6 = (5 * J4 + $A2 * pow(cos($rlat), 2)) / 3;
        $alfa = (3.0 / 4) * $se2;
        $beta = (5.0 + 3) * pow($alfa, 2);
        $gamma = (35.0 / 27) * pow($alfa, 3);
        $B = 0.9996 * $c * ($rlat - ($alfa * J2) + ($beta * $J4) - ($gamma * $J6));
        $x = $CC * $v * (1 + ($S / 3 )) + 500000;
        $y = $n * $v * (1 + $S) + $B;
        
        // For latitudes in the south hemi
        if ($lat < 0){
            $y = $y + 10000000;
        }
        
        return array('x' => $x, 'y' => $y);                     
    }
    
    public static function WSG84toLambert93($longitude, $latitude){
        // System WGS84
        $A = 6378137; 
        $e = 0.08181919106;
        
        $lc = deg2rad(3);
        $phi0 = deg2rad(46.5);
        $phi1 = deg2rad(44);
        $phi2 = deg2rad(49);
        
        $x0 = 700000;
        $y0 = 6600000;
        
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
}