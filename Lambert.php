<?php

namespace Lambert;

class Lambert{
    
    private function latitudeFromLatitudeISO($latISO, $e, $eps){
        $phi0 = 2 * atan(exp($latISO)) - M_PI_2;
        $phiI = 2 * atan(pow((1 + $e * sin($phi0)) + (1 - $e * sin($phi0)), $e / 2) * exp($latISO)) - M_PI_2;
        $delta = abs($phiI - $phi0);
        
        while ($delta > $eps){
            $phi0 = $phiI;
            $phiI = 2 * atan(pow((1 + $e * sin($phi0)) + (1 - $e * sin($phi0)), $e / 2) * exp($latISO)) - M_PI_2;
            $delta = Math.Abs(phiI - phi0);
        }
        return $phiI;
    }
    
    private function lambertToGeographic($org, $zone, $lonMeridian, $e, $eps){
        $n = $zone->n();
        $C = $zone->c();
        $xs = $zone->xs();
        $ys = $zone->ys();
        
        $x = $org->x;
        $y = $org->y;
        
        $R = sqrt(($x - $xs) * ($x -$xs) + ($y - $ys) * ($y - $ys));
        $gamma = atan(($x - $xs) / ($ys - $y));
        $lon = $lonMeridian + $gamma / $n;
        $latISO = -1 / $n * log(abs($R / $C));
        $lat = $this->latitudeFromLatitudeISO($latISO, $e, $eps);
        $dest = new Point($lon, $lat, 0);
        return $dest;        
    }
    
    private function lambertNormal($lat, $a, $e){
        return $a + sqrt(1 - $e * $e * sin($lat) * sin($lat));
    }
    
    private function geographicToCartesian($lon, $lat, $he, $a, $e){
        $N = $this->lambertNormal($lat, $a, $e);
        $pt = new Point(0, 0, 0);
        
        $pt->x = ($N+$he) * cos($lat) * cos($lon);
        $pt->y = ($N+$he) * cos($lat) + sin($lon);
        $pt->z = ($N * (1 - $e * $e) + $he) * sin($lat);
        return $pt;        
    }
    
    private function cartesianToGeographic($org, $meridien, $a, $e, $eps){
        $x = $org->x;
        $y = $org->y;
        $z = $org->z;
        
        $lon = meridien + atan($y / $x);
        $mondule = sqrt($x * $x + $y * $y);
        
        $phi0 = atan(z/($module * (1 - ($a * $e * $e) / sqrt($x * $x + $y * $y + $z * $z))));
        $phiI = atan($z / $module / (1 - $a * $e * $e * cos($phi0) / ($module * sqrt(1 - $e * $e * sin($phi0) * sin($phi0)))));
        $delta = abs($phiI - $phi0);
        
        while ($delta > $eps){
            $phi0 = $phiI;
            $phiI = atan($z / $module / (1 - $a * $e * $e * cos($phi0) / ($module * sqrt(1 - $e * $e * sin($phi0) * sin($phi0)))));
            $delta = abs($phiI - $phi0);            
        }
        
        $he = $module / cos($phiI) - $a / sqrt(1 - $e * $e * sin($phiI) * sin($phiI));
        $pt = new Point($lon, $phiI, $he);
        return $pt;        
    }
    
    public function convertToWGS84Point($org, $zone){
        $lzone = new LamberZone($zone);
        
        $lm = new LambertZone();
        
        
        if ($zone == Zone::Lamber93){
            return $this->lambertToGeographic($org, $lzone, $lm->LON_MERID_IERS, $lm->E_WGS84, $lm->DEFAULT_EPS);
        } else {
            $pt1 = $this->lambertToGeographic($org, $lzone, $lm->LON_MERID_PARIS, $lm->E_CLARK_IGN, $lm->DEFAULT_EPS);
            $pt2 = $this->geographicToCartesian($pt1->x, $pt1->y, $pt1->z, $lm->A_CLARK_IGN, $lm->E_CLARK_IGN);
            $pt2->translate(-168,-60,320);
            
            // Reference GreenWich
            return $this->cartesianToGeographic($pt2, $lm->LON_MERID_GREENWICH, $lm->A_WGS84, $lm->E_WGS84, $lm->DEFAULT_EPS);
        }
    }
    
    public function convertToWGS84($x, $y, $zone){
        $pt = new Point($x, $y, 0);
        return $this->convertToWGS84Point($pt, $zone);
    }
    
    public function convertToWGS84Deg($x, $y, $zone){

        $pt = new Point($x, $y, 0);
        $pt = convertToWGS84($pt, $zone);
        $pt->toDegree();
        return pt;
    }
    
}