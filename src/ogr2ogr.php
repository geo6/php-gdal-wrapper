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

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class ogr2ogr {
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

  public function __construct($destination, $source, $layers = array()) {
    $this->destination = $destination;
    $this->source = $source;
    $this->layers = (is_string($layers) ? array($layers) : $layers);
    $this->options = new ogr2ogr\options();

    $this->setCommand();
  }

  public function setOption($name, $value = TRUE) {
    $this->options->{$name} = $value;

    $this->setCommand();
  }

  private function setCommand() {
    $options = '';
    if ($this->options->helpGeneral === TRUE) { $options .= ' --help-general'; }
    if ($this->options->skipfailures === TRUE) { $options .= ' -skipfailures'; };
    if ($this->options->append === TRUE) { $options .= ' -append'; };
    if ($this->options->update === TRUE) { $options .= ' -update'; };
    if (!empty($this->options->select)) { $options .= sprintf(' -select %s', escapeshellarg($this->options->select)); };
    if (!empty($this->options->where)) { $options .= sprintf(' -where %s', escapeshellarg($this->options->where)); };
    if ($this->options->progress === TRUE) { $options .= ' -progress'; };
    if (!empty($this->options->sql)) { $options .= sprintf(' -sql %s', escapeshellarg($this->options->sql)); };
    if (!empty($this->options->dialect)) { $options .= sprintf(' -dialect %s', escapeshellarg($this->options->dialect)); };
    if ($this->options->preserve_fid === TRUE) { $options .= ' -preserve_fid'; };
    if (!empty($this->options->fid)) { $options .= sprintf(' -fid %s', escapeshellarg($this->options->fid)); };
    if (!empty($this->options->limit)) { $options .= sprintf(' -limit %s', escapeshellarg($this->options->limit)); };
    if (!empty($this->options->spat)) { $options .= sprintf(' -spat %s', escapeshellarg($this->options->spat)); };
    if (!empty($this->options->spat_srs)) { $options .= sprintf(' -spat_srs %s', escapeshellarg($this->options->spat_srs)); };
    if (!empty($this->options->geomfield)) { $options .= sprintf(' -geomfield %s', escapeshellarg($this->options->geomfield)); };
    if (!empty($this->options->a_srs)) { $options .= sprintf(' -a_srs %s', escapeshellarg($this->options->a_srs)); };
    if (!empty($this->options->t_srs)) { $options .= sprintf(' -t_srs %s', escapeshellarg($this->options->t_srs)); };
    if (!empty($this->options->s_srs)) { $options .= sprintf(' -s_srs %s', escapeshellarg($this->options->s_srs)); };
    if (!empty($this->options->f)) { $options .= sprintf(' -f %s', escapeshellarg($this->options->f)); };
    if ($this->options->overwrite === TRUE) { $options .= ' -overwrite'; };
    if (!empty($this->options->nln)) { $options .= sprintf(' -nln %s', escapeshellarg($this->options->nln)); };
    if (!empty($this->options->nlt)) { $options .= sprintf(' -nlt %s', escapeshellarg($this->options->nlt)); };
    if (!empty($this->options->dim)) { $options .= sprintf(' -dim %s', escapeshellarg($this->options->dim)); };
    if (!empty($this->options->gt)) { $options .= sprintf(' -gt %s', escapeshellarg($this->options->gt)); };
    if (!empty($this->options->clipsrc)) { $options .= sprintf(' -clipsrc %s', escapeshellarg($this->options->clipsrc)); };
    if (!empty($this->options->clipsrcsql)) { $options .= sprintf(' -clipsrcsql %s', escapeshellarg($this->options->clipsrcsql)); };
    if (!empty($this->options->clipsrclayer)) { $options .= sprintf(' -clipsrclayer %s', escapeshellarg($this->options->clipsrclayer)); };
    if (!empty($this->options->clipsrcwhere)) { $options .= sprintf(' -clipsrcwhere %s', escapeshellarg($this->options->clipsrcwhere)); };
    if (!empty($this->options->clipdst)) { $options .= sprintf(' -clipdst %s', escapeshellarg($this->options->clipdst)); };
    if (!empty($this->options->clipdstsql)) { $options .= sprintf(' -clipdstsql %s', escapeshellarg($this->options->clipdstsql)); };
    if (!empty($this->options->clipdstlayer)) { $options .= sprintf(' -clipdstlayer %s', escapeshellarg($this->options->clipdstlayer)); };
    if (!empty($this->options->clipdstwhere)) { $options .= sprintf(' -clipdstwhere %s', escapeshellarg($this->options->clipdstwhere)); };
    if ($this->options->wrapdatakline === TRUE) { $options .= ' -wrapdatakline'; };
    if (!empty($this->options->datelineoffset)) { $options .= sprintf(' -datelineoffset %s', escapeshellarg($this->options->datelineoffset)); };
    if (!empty($this->options->simplify)) { $options .= sprintf(' -simplify %s', escapeshellarg($this->options->simplify)); };
    if (!empty($this->options->segmentize)) { $options .= sprintf(' -segmentize %s', escapeshellarg($this->options->segmentize)); };
    if ($this->options->addfields === TRUE) { $options .= ' -addfields'; };
    if ($this->options->unsetFid === TRUE) { $options .= ' -unsetFid'; };
    if ($this->options->relaxedFieldNameMatch === TRUE) { $options .= ' -relaxedFieldNameMatch'; };
    if ($this->options->forceNullable === TRUE) { $options .= ' -forceNullable'; };
    if ($this->options->unsetDefault === TRUE) { $options .= ' -unsetDefault'; };
    if (!empty($this->options->fieldTypeToString)) { $options .= sprintf(' -fieldTypeToString %s', escapeshellarg($this->options->fieldTypeToString)); };
    if ($this->options->unsetFieldWidth === TRUE) { $options .= ' -unsetFieldWidth'; };
    if (!empty($this->options->mapFieldType)) { $options .= sprintf(' -mapFieldType %s', escapeshellarg($this->options->mapFieldType)); };
    if (!empty($this->options->fieldmap)) { $options .= sprintf(' -fieldmap %s', escapeshellarg($this->options->fieldmap)); };
    if ($this->options->splitlistfields === TRUE) { $options .= ' -splitlistfields'; };
    if (!empty($this->options->maxsubfields)) { $options .= sprintf(' -maxsubfields %s', escapeshellarg($this->options->maxsubfields)); };
    if ($this->options->explodecollections === TRUE) { $options .= ' -explodecollections'; };
    if (!empty($this->options->zfield)) { $options .= sprintf(' -zfield %s', escapeshellarg($this->options->zfield)); };
    if (!empty($this->options->gcp)) { $options .= sprintf(' -gcp %s', escapeshellarg($this->options->gcp)); };
    if (!empty($this->options->order)) { $options .= sprintf(' -order %s', escapeshellarg($this->options->order)); };
    if ($this->options->tps === TRUE) { $options .= ' -tps'; };
    if ($this->options->nomd === TRUE) { $options .= ' -nomd'; };
    if (!empty($this->options->mo)) { $options .= sprintf(' -mo %s', escapeshellarg($this->options->mo)); };
    if ($this->options->noNativeData === TRUE) { $options .= ' -noNativeData'; };

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

  public function getCommand() {
    return $this->command;
  }

  public function run() {
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

class options {
  private $helpGeneral = FALSE;
  private $skipfailures = FALSE;
  private $append = FALSE;
  private $update = FALSE;
  private $select = NULL;
  private $where = NULL;
  private $progress = FALSE;
  private $sql = NULL;
  private $dialect = NULL;
  private $preserve_fid = FALSE;
  private $fid = NULL;
  private $limit = NULL;
  private $spat = NULL;
  private $spat_srs = NULL;
  private $geomfield = NULL;
  private $a_srs = NULL;
  private $t_srs = NULL;
  private $s_srs = NULL;
  private $f = NULL;
  private $overwrite = FALSE;
  private $dsco = array();
  private $lco = array();
  private $nln = NULL;
  private $nlt = NULL;
  private $dim = NULL;
  private $gt = NULL;
  private $oo = array();
  private $doo = array();
  private $clipsrc = NULL;
  private $clipsrcsql = NULL;
  private $clipsrclayer = NULL;
  private $clipsrcwhere = NULL;
  private $clipdst = NULL;
  private $clipdstsql = NULL;
  private $clipdstlayer = NULL;
  private $clipdstwhere = NULL;
  private $wrapdatakline = FALSE;
  private $datelineoffset = NULL;
  private $simplify = NULL;
  private $segmentize = NULL;
  private $addfields = FALSE;
  private $unsetFid = FALSE;
  private $relaxedFieldNameMatch = FALSE;
  private $forceNullable = FALSE;
  private $unsetDefault = FALSE;
  private $fieldTypeToString = NULL;
  private $unsetFieldWidth = FALSE;
  private $mapFieldType = NULL;
  private $fieldmap = NULL;
  private $splitlistfields = FALSE;
  private $maxsubfields = NULL;
  private $explodecollections = FALSE;
  private $zfield = NULL;
  private $gcp = NULL;
  private $order = NULL;
  private $tps = FALSE;
  private $nomd = FALSE;
  private $mo = NULL;
  private $noNativeData = FALSE;

  public function __construct() {
  }

  public function __set($name, $value) {
    $this->{$name} = $value;
  }

  public function __get($name) {
    return $this->{$name};
  }

  public function __isset($name) {
    return isset($this->{$name});
  }

  public function __unset($name) {
    unset($this->{$name});
  }
}
