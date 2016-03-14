<?php

namespace AtlasLib;

require("Atlas.php");



$lambert93 = Atlas::WSG84toLambert93(4.835, 45.76);
var_dump($lambert93);

$lambertI = Atlas::WGS84toLambertI(4.835, 45.76);
var_dump($lambertI);

$lambertII = Atlas::WGS84toLambertII(4.835, 45.76);
var_dump($lambertII);

$lambertIIe = Atlas::WGS84toLambertIIE(4.835, 45.76);
var_dump($lambertIIe);

$lambertIII = Atlas::WGS84toLambertIII(4.835, 45.76);
var_dump($lambertIII);

$lambertIV = Atlas::WGS84toLambertIV(4.835, 45.76);
var_dump($lambertIV);
