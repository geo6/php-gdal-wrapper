<?php
/*
 * This file is part of the GDAL package.
 *
 * (c) Jonathan Beliën <jbe@geo6.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Geo6\GDAL;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class ogr2ogr
{
    /**
     * @var string
     */
    private $command;

    /**
     * @var string{}
     */
    private $options;

    /**
     * @var string
     */
    private $destination;

    /**
     * @var string
     */
    private $source;

    /**
     * @var string[]
     */
    private $layers;

    public function __construct($destination, $source, $layers = [])
    {
        $this->destination = $destination;
        $this->source = $source;
        $this->layers = (is_string($layers) ? [$layers] : $layers);
        $this->options = new ogr2ogr\options();

        $this->setCommand();
    }

    public function setOption($name, $value = true)
    {
        $this->options->{$name} = $value;

        $this->setCommand();
    }

    private function setCommand()
    {
        $options = '';
        if ($this->options->helpGeneral === true) {
            $options .= ' --help-general';
        }
        if ($this->options->skipfailures === true) {
            $options .= ' -skipfailures';
        }
        if ($this->options->append === true) {
            $options .= ' -append';
        }
        if ($this->options->update === true) {
            $options .= ' -update';
        }
        if (!empty($this->options->select)) {
            $options .= sprintf(' -select %s', escapeshellarg($this->options->select));
        }
        if (!empty($this->options->where)) {
            $options .= sprintf(' -where %s', escapeshellarg($this->options->where));
        }
        if ($this->options->progress === true) {
            $options .= ' -progress';
        }
        if (!empty($this->options->sql)) {
            $options .= sprintf(' -sql %s', escapeshellarg($this->options->sql));
        }
        if (!empty($this->options->dialect)) {
            $options .= sprintf(' -dialect %s', escapeshellarg($this->options->dialect));
        }
        if ($this->options->preserve_fid === true) {
            $options .= ' -preserve_fid';
        }
        if (!empty($this->options->fid)) {
            $options .= sprintf(' -fid %s', escapeshellarg($this->options->fid));
        }
        if (!empty($this->options->limit)) {
            $options .= sprintf(' -limit %s', escapeshellarg($this->options->limit));
        }
        if (!empty($this->options->spat)) {
            $options .= sprintf(' -spat %s', escapeshellarg($this->options->spat));
        }
        if (!empty($this->options->spat_srs)) {
            $options .= sprintf(' -spat_srs %s', escapeshellarg($this->options->spat_srs));
        }
        if (!empty($this->options->geomfield)) {
            $options .= sprintf(' -geomfield %s', escapeshellarg($this->options->geomfield));
        }
        if (!empty($this->options->a_srs)) {
            $options .= sprintf(' -a_srs %s', escapeshellarg($this->options->a_srs));
        }
        if (!empty($this->options->t_srs)) {
            $options .= sprintf(' -t_srs %s', escapeshellarg($this->options->t_srs));
        }
        if (!empty($this->options->s_srs)) {
            $options .= sprintf(' -s_srs %s', escapeshellarg($this->options->s_srs));
        }
        if (!empty($this->options->f)) {
            $options .= sprintf(' -f %s', escapeshellarg($this->options->f));
        }
        if ($this->options->overwrite === true) {
            $options .= ' -overwrite';
        }
        if (!empty($this->options->nln)) {
            $options .= sprintf(' -nln %s', escapeshellarg($this->options->nln));
        }
        if (!empty($this->options->nlt)) {
            $options .= sprintf(' -nlt %s', escapeshellarg($this->options->nlt));
        }
        if (!empty($this->options->dim)) {
            $options .= sprintf(' -dim %s', escapeshellarg($this->options->dim));
        }
        if (!empty($this->options->gt)) {
            $options .= sprintf(' -gt %s', escapeshellarg($this->options->gt));
        }
        if (!empty($this->options->clipsrc)) {
            $options .= sprintf(' -clipsrc %s', escapeshellarg($this->options->clipsrc));
        }
        if (!empty($this->options->clipsrcsql)) {
            $options .= sprintf(' -clipsrcsql %s', escapeshellarg($this->options->clipsrcsql));
        }
        if (!empty($this->options->clipsrclayer)) {
            $options .= sprintf(' -clipsrclayer %s', escapeshellarg($this->options->clipsrclayer));
        }
        if (!empty($this->options->clipsrcwhere)) {
            $options .= sprintf(' -clipsrcwhere %s', escapeshellarg($this->options->clipsrcwhere));
        }
        if (!empty($this->options->clipdst)) {
            $options .= sprintf(' -clipdst %s', escapeshellarg($this->options->clipdst));
        }
        if (!empty($this->options->clipdstsql)) {
            $options .= sprintf(' -clipdstsql %s', escapeshellarg($this->options->clipdstsql));
        }
        if (!empty($this->options->clipdstlayer)) {
            $options .= sprintf(' -clipdstlayer %s', escapeshellarg($this->options->clipdstlayer));
        }
        if (!empty($this->options->clipdstwhere)) {
            $options .= sprintf(' -clipdstwhere %s', escapeshellarg($this->options->clipdstwhere));
        }
        if ($this->options->wrapdatakline === true) {
            $options .= ' -wrapdatakline';
        }
        if (!empty($this->options->datelineoffset)) {
            $options .= sprintf(' -datelineoffset %s', escapeshellarg($this->options->datelineoffset));
        }
        if (!empty($this->options->simplify)) {
            $options .= sprintf(' -simplify %s', escapeshellarg($this->options->simplify));
        }
        if (!empty($this->options->segmentize)) {
            $options .= sprintf(' -segmentize %s', escapeshellarg($this->options->segmentize));
        }
        if ($this->options->addfields === true) {
            $options .= ' -addfields';
        }
        if ($this->options->unsetFid === true) {
            $options .= ' -unsetFid';
        }
        if ($this->options->relaxedFieldNameMatch === true) {
            $options .= ' -relaxedFieldNameMatch';
        }
        if ($this->options->forceNullable === true) {
            $options .= ' -forceNullable';
        }
        if ($this->options->unsetDefault === true) {
            $options .= ' -unsetDefault';
        }
        if (!empty($this->options->fieldTypeToString)) {
            $options .= sprintf(' -fieldTypeToString %s', escapeshellarg($this->options->fieldTypeToString));
        }
        if ($this->options->unsetFieldWidth === true) {
            $options .= ' -unsetFieldWidth';
        }
        if (!empty($this->options->mapFieldType)) {
            $options .= sprintf(' -mapFieldType %s', escapeshellarg($this->options->mapFieldType));
        }
        if (!empty($this->options->fieldmap)) {
            $options .= sprintf(' -fieldmap %s', escapeshellarg($this->options->fieldmap));
        }
        if ($this->options->splitlistfields === true) {
            $options .= ' -splitlistfields';
        }
        if (!empty($this->options->maxsubfields)) {
            $options .= sprintf(' -maxsubfields %s', escapeshellarg($this->options->maxsubfields));
        }
        if ($this->options->explodecollections === true) {
            $options .= ' -explodecollections';
        }
        if (!empty($this->options->zfield)) {
            $options .= sprintf(' -zfield %s', escapeshellarg($this->options->zfield));
        }
        if (!empty($this->options->gcp)) {
            $options .= sprintf(' -gcp %s', escapeshellarg($this->options->gcp));
        }
        if (!empty($this->options->order)) {
            $options .= sprintf(' -order %s', escapeshellarg($this->options->order));
        }
        if ($this->options->tps === true) {
            $options .= ' -tps';
        }
        if ($this->options->nomd === true) {
            $options .= ' -nomd';
        }
        if (!empty($this->options->mo)) {
            $options .= sprintf(' -mo %s', escapeshellarg($this->options->mo));
        }
        if ($this->options->noNativeData === true) {
            $options .= ' -noNativeData';
        }

        if (!empty($this->options->dsco) && is_array($this->options->dsco)) {
            foreach ($this->options->dsco as $name => $value) {
                $options .= sprintf(' -dsco %s', escapeshellarg(sprintf('%s=%s', $name, $value)));
            }
        }
        if (!empty($this->options->lco) && is_array($this->options->lco)) {
            foreach ($this->options->lco as $name => $value) {
                $options .= sprintf(' -lco %s', escapeshellarg(sprintf('%s=%s', $name, $value)));
            }
        }
        if (!empty($this->options->oo) && is_array($this->options->oo)) {
            foreach ($this->options->oo as $name => $value) {
                $options .= sprintf(' -oo %s', escapeshellarg(sprintf('%s=%s', $name, $value)));
            }
        }
        if (!empty($this->options->doo) && is_array($this->options->doo)) {
            foreach ($this->options->doo as $name => $value) {
                $options .= sprintf(' -doo %s', escapeshellarg(sprintf('%s=%s', $name, $value)));
            }
        }

        $this->command = sprintf('ogr2ogr %s %s %s %s', $options, escapeshellarg($this->destination), escapeshellarg($this->source), implode(' ', $this->layers));

        return $this->command;
    }

    public function getCommand()
    {
        return $this->command;
    }

    public function run()
    {
        $process = new Process($this->command);
        $process->mustRun();

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return $process->getOutput();
    }
}

namespace Geo6\GDAL\ogr2ogr;

class options
{
    private $helpGeneral = false;
    private $skipfailures = false;
    private $append = false;
    private $update = false;
    private $select = null;
    private $where = null;
    private $progress = false;
    private $sql = null;
    private $dialect = null;
    private $preserve_fid = false;
    private $fid = null;
    private $limit = null;
    private $spat = null;
    private $spat_srs = null;
    private $geomfield = null;
    private $a_srs = null;
    private $t_srs = null;
    private $s_srs = null;
    private $f = null;
    private $overwrite = false;
    private $dsco = [];
    private $lco = [];
    private $nln = null;
    private $nlt = null;
    private $dim = null;
    private $gt = null;
    private $oo = [];
    private $doo = [];
    private $clipsrc = null;
    private $clipsrcsql = null;
    private $clipsrclayer = null;
    private $clipsrcwhere = null;
    private $clipdst = null;
    private $clipdstsql = null;
    private $clipdstlayer = null;
    private $clipdstwhere = null;
    private $wrapdatakline = false;
    private $datelineoffset = null;
    private $simplify = null;
    private $segmentize = null;
    private $addfields = false;
    private $unsetFid = false;
    private $relaxedFieldNameMatch = false;
    private $forceNullable = false;
    private $unsetDefault = false;
    private $fieldTypeToString = null;
    private $unsetFieldWidth = false;
    private $mapFieldType = null;
    private $fieldmap = null;
    private $splitlistfields = false;
    private $maxsubfields = null;
    private $explodecollections = false;
    private $zfield = null;
    private $gcp = null;
    private $order = null;
    private $tps = false;
    private $nomd = false;
    private $mo = null;
    private $noNativeData = false;

    public function __construct()
    {
    }

    public function __set($name, $value)
    {
        $this->{$name} = $value;
    }

    public function __get($name)
    {
        return $this->{$name};
    }

    public function __isset($name)
    {
        return isset($this->{$name});
    }

    public function __unset($name)
    {
        unset($this->{$name});
    }
}
