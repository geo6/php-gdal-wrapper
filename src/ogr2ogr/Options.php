<?php
/**
 * This file is part of the GDAL package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * PHP version 7
 *
 * @package Geo6\GDAL\ogr2ogr
 * @license GPL License
 */

declare(strict_types=1);

namespace Geo6\GDAL\ogr2ogr;

/**
 *
 * @author Jonathan Beliën <jbe@geo6.be>
 */
class Options
{
    public $helpGeneral = false;
    public $skipfailures = false;
    public $append = false;
    public $update = false;
    public $select = null;
    public $where = null;
    public $progress = false;
    public $sql = null;
    public $dialect = null;
    public $preserve_fid = false;
    public $fid = null;
    public $limit = null;
    public $spat = null;
    public $spat_srs = null;
    public $geomfield = null;
    public $a_srs = null;
    public $t_srs = null;
    public $s_srs = null;
    public $f = null;
    public $overwrite = false;
    public $dsco = [];
    public $lco = [];
    public $nln = null;
    public $nlt = null;
    public $dim = null;
    public $gt = null;
    public $oo = [];
    public $doo = [];
    public $clipsrc = null;
    public $clipsrcsql = null;
    public $clipsrclayer = null;
    public $clipsrcwhere = null;
    public $clipdst = null;
    public $clipdstsql = null;
    public $clipdstlayer = null;
    public $clipdstwhere = null;
    public $wrapdatakline = false;
    public $datelineoffset = null;
    public $simplify = null;
    public $segmentize = null;
    public $addfields = false;
    public $unsetFid = false;
    public $relaxedFieldNameMatch = false;
    public $forceNullable = false;
    public $unsetDefault = false;
    public $fieldTypeToString = null;
    public $unsetFieldWidth = false;
    public $mapFieldType = null;
    public $fieldmap = null;
    public $splitlistfields = false;
    public $maxsubfields = null;
    public $explodecollections = false;
    public $zfield = null;
    public $gcp = null;
    public $order = null;
    public $tps = false;
    public $nomd = false;
    public $mo = null;
    public $noNativeData = false;
}
