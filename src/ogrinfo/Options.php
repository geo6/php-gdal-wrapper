<?php

/**
 * This file is part of the GDAL package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * PHP version 7
 *
 * @license GPL License
 */

declare(strict_types=1);

namespace Geo6\GDAL\ogrinfo;

/**
 * @author Jonathan Beliën <jbe@geo6.be>
 */
class Options
{
    public $helpGeneral = false;
    public $ro = false;
    public $q = false;
    public $where = null;
    public $spat = null;
    public $geomfield = null;
    public $fid = null;
    public $sql = null;
    public $dialect = null;
    public $al = false;
    public $rl = false;
    public $so = false;
    public $fields = 'YES';
    public $geom = 'YES';
    public $formats = false;
    public $oo = [];
    public $nomd = false;
    public $listmdd = false;
    public $mdd = null;
    public $nocount = false;
    public $noextent = false;
}
