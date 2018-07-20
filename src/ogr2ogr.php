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

namespace Geo6\GDAL;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

/**
 * Implements `ogr2ogr` function.
 *
 * @author Jonathan Beliën <jbe@geo6.be>
 *
 * @link   http://www.gdal.org/ogr2ogr.html
 */
class ogr2ogr
{
    /**
     * @var string
     */
    private $_command;

    /**
     * @var string{}
     */
    private $_options;

    /**
     * @var string
     */
    private $_destination;

    /**
     * @var string
     */
    private $_source;

    /**
     * @var string[]
     */
    private $_layers;

    /**
     * @param string   $destination Destination datasource.
     * @param string   $source      Source datasource.
     * @param string[] $layers      Layers from source datasource (optional).
     *
     * @return void
     */
    public function __construct(string $destination, string $source, $layers = [])
    {
        $this->_destination = $destination;
        $this->_source = $source;
        $this->_layers = (is_string($layers) ? [$layers] : $layers);
        $this->_options = new ogr2ogr\options();

        $this->_setCommand();
    }

    /**
     * @param string $name  Option name.
     * @param mixed  $value Option value.
     *
     * @return void
     */
    public function setOption(string $name, $value = true) : void
    {
        $this->_options->{$name} = $value;

        $this->_setCommand();
    }

    /**
     * @return string
     */
    private function _setCommand() : string
    {
        $options = '';
        if ($this->_options->helpGeneral === true) {
            $options .= ' --help-general';
        }
        if ($this->_options->skipfailures === true) {
            $options .= ' -skipfailures';
        }
        if ($this->_options->append === true) {
            $options .= ' -append';
        }
        if ($this->_options->update === true) {
            $options .= ' -update';
        }
        if (!empty($this->_options->select)) {
            $options .= sprintf(
                ' -select %s',
                escapeshellarg($this->_options->select)
            );
        }
        if (!empty($this->_options->where)) {
            $options .= sprintf(
                ' -where %s',
                scapeshellarg($this->_options->where)
            );
        }
        if ($this->_options->progress === true) {
            $options .= ' -progress';
        }
        if (!empty($this->_options->sql)) {
            $options .= sprintf(
                ' -sql %s',
                escapeshellarg($this->_options->sql)
            );
        }
        if (!empty($this->_options->dialect)) {
            $options .= sprintf(
                ' -dialect %s',
                escapeshellarg($this->_options->dialect)
            );
        }
        if ($this->_options->preserve_fid === true) {
            $options .= ' -preserve_fid';
        }
        if (!empty($this->_options->fid)) {
            $options .= sprintf(
                ' -fid %s',
                escapeshellarg($this->_options->fid)
            );
        }
        if (!empty($this->_options->limit)) {
            $options .= sprintf(
                ' -limit %s',
                escapeshellarg($this->_options->limit)
            );
        }
        if (!empty($this->_options->spat)) {
            $options .= sprintf(
                ' -spat %s',
                escapeshellarg($this->_options->spat)
            );
        }
        if (!empty($this->_options->spat_srs)) {
            $options .= sprintf(
                ' -spat_srs %s',
                escapeshellarg($this->_options->spat_srs)
            );
        }
        if (!empty($this->_options->geomfield)) {
            $options .= sprintf(
                ' -geomfield %s',
                escapeshellarg($this->_options->geomfield)
            );
        }
        if (!empty($this->_options->a_srs)) {
            $options .= sprintf(
                ' -a_srs %s',
                escapeshellarg($this->_options->a_srs)
            );
        }
        if (!empty($this->_options->t_srs)) {
            $options .= sprintf(
                ' -t_srs %s',
                escapeshellarg($this->_options->t_srs)
            );
        }
        if (!empty($this->_options->s_srs)) {
            $options .= sprintf(
                ' -s_srs %s',
                escapeshellarg($this->_options->s_srs)
            );
        }
        if (!empty($this->_options->f)) {
            $options .= sprintf(
                ' -f %s',
                escapeshellarg($this->_options->f)
            );
        }
        if ($this->_options->overwrite === true) {
            $options .= ' -overwrite';
        }
        if (!empty($this->_options->nln)) {
            $options .= sprintf(
                ' -nln %s',
                escapeshellarg($this->_options->nln)
            );
        }
        if (!empty($this->_options->nlt)) {
            $options .= sprintf(
                ' -nlt %s',
                escapeshellarg($this->_options->nlt)
            );
        }
        if (!empty($this->_options->dim)) {
            $options .= sprintf(
                ' -dim %s',
                escapeshellarg($this->_options->dim)
            );
        }
        if (!empty($this->_options->gt)) {
            $options .= sprintf(
                ' -gt %s',
                escapeshellarg($this->_options->gt)
            );
        }
        if (!empty($this->_options->clipsrc)) {
            $options .= sprintf(
                ' -clipsrc %s',
                escapeshellarg($this->_options->clipsrc)
            );
        }
        if (!empty($this->_options->clipsrcsql)) {
            $options .= sprintf(
                ' -clipsrcsql %s',
                escapeshellarg($this->_options->clipsrcsql)
            );
        }
        if (!empty($this->_options->clipsrclayer)) {
            $options .= sprintf(
                ' -clipsrclayer %s',
                escapeshellarg($this->_options->clipsrclayer)
            );
        }
        if (!empty($this->_options->clipsrcwhere)) {
            $options .= sprintf(
                ' -clipsrcwhere %s',
                escapeshellarg($this->_options->clipsrcwhere)
            );
        }
        if (!empty($this->_options->clipdst)) {
            $options .= sprintf(
                ' -clipdst %s',
                escapeshellarg($this->_options->clipdst)
            );
        }
        if (!empty($this->_options->clipdstsql)) {
            $options .= sprintf(
                ' -clipdstsql %s',
                escapeshellarg($this->_options->clipdstsql)
            );
        }
        if (!empty($this->_options->clipdstlayer)) {
            $options .= sprintf(
                ' -clipdstlayer %s',
                escapeshellarg($this->_options->clipdstlayer)
            );
        }
        if (!empty($this->_options->clipdstwhere)) {
            $options .= sprintf(
                ' -clipdstwhere %s',
                escapeshellarg($this->_options->clipdstwhere)
            );
        }
        if ($this->_options->wrapdatakline === true) {
            $options .= ' -wrapdatakline';
        }
        if (!empty($this->_options->datelineoffset)) {
            $options .= sprintf(
                ' -datelineoffset %s',
                escapeshellarg($this->_options->datelineoffset)
            );
        }
        if (!empty($this->_options->simplify)) {
            $options .= sprintf(
                ' -simplify %s',
                escapeshellarg($this->_options->simplify)
            );
        }
        if (!empty($this->_options->segmentize)) {
            $options .= sprintf(
                ' -segmentize %s',
                escapeshellarg($this->_options->segmentize)
            );
        }
        if ($this->_options->addfields === true) {
            $options .= ' -addfields';
        }
        if ($this->_options->unsetFid === true) {
            $options .= ' -unsetFid';
        }
        if ($this->_options->relaxedFieldNameMatch === true) {
            $options .= ' -relaxedFieldNameMatch';
        }
        if ($this->_options->forceNullable === true) {
            $options .= ' -forceNullable';
        }
        if ($this->_options->unsetDefault === true) {
            $options .= ' -unsetDefault';
        }
        if (!empty($this->_options->fieldTypeToString)) {
            $options .= sprintf(
                ' -fieldTypeToString %s',
                escapeshellarg($this->_options->fieldTypeToString)
            );
        }
        if ($this->_options->unsetFieldWidth === true) {
            $options .= ' -unsetFieldWidth';
        }
        if (!empty($this->_options->mapFieldType)) {
            $options .= sprintf(
                ' -mapFieldType %s',
                escapeshellarg($this->_options->mapFieldType)
            );
        }
        if (!empty($this->_options->fieldmap)) {
            $options .= sprintf(
                ' -fieldmap %s',
                escapeshellarg($this->_options->fieldmap)
            );
        }
        if ($this->_options->splitlistfields === true) {
            $options .= ' -splitlistfields';
        }
        if (!empty($this->_options->maxsubfields)) {
            $options .= sprintf(
                ' -maxsubfields %s',
                escapeshellarg($this->_options->maxsubfields)
            );
        }
        if ($this->_options->explodecollections === true) {
            $options .= ' -explodecollections';
        }
        if (!empty($this->_options->zfield)) {
            $options .= sprintf(
                ' -zfield %s',
                escapeshellarg($this->_options->zfield)
            );
        }
        if (!empty($this->_options->gcp)) {
            $options .= sprintf(
                ' -gcp %s',
                escapeshellarg($this->_options->gcp)
            );
        }
        if (!empty($this->_options->order)) {
            $options .= sprintf(
                ' -order %s',
                escapeshellarg($this->_options->order)
            );
        }
        if ($this->_options->tps === true) {
            $options .= ' -tps';
        }
        if ($this->_options->nomd === true) {
            $options .= ' -nomd';
        }
        if (!empty($this->_options->mo)) {
            $options .= sprintf(
                ' -mo %s',
                escapeshellarg($this->_options->mo)
            );
        }
        if ($this->_options->noNativeData === true) {
            $options .= ' -noNativeData';
        }

        if (!empty($this->_options->dsco) && is_array($this->_options->dsco)) {
            foreach ($this->_options->dsco as $name => $value) {
                $options .= sprintf(
                    ' -dsco %s',
                    escapeshellarg(sprintf('%s=%s', $name, $value))
                );
            }
        }
        if (!empty($this->_options->lco) && is_array($this->_options->lco)) {
            foreach ($this->_options->lco as $name => $value) {
                $options .= sprintf(
                    ' -lco %s',
                    escapeshellarg(sprintf('%s=%s', $name, $value))
                );
            }
        }
        if (!empty($this->_options->oo) && is_array($this->_options->oo)) {
            foreach ($this->_options->oo as $name => $value) {
                $options .= sprintf(
                    ' -oo %s',
                    escapeshellarg(sprintf('%s=%s', $name, $value))
                );
            }
        }
        if (!empty($this->_options->doo) && is_array($this->_options->doo)) {
            foreach ($this->_options->doo as $name => $value) {
                $options .= sprintf(
                    ' -doo %s',
                    escapeshellarg(sprintf('%s=%s', $name, $value))
                );
            }
        }

        $this->_command = sprintf(
            'ogr2ogr %s %s %s %s',
            $options,
            escapeshellarg($this->_destination),
            escapeshellarg($this->_source),
            implode(' ', $this->_layers)
        );

        return $this->_command;
    }

    /**
     * @return string
     */
    public function getCommand() : string
    {
        return $this->_command;
    }

    /**
     * @throws ProcessFailedException if the process is not successful.
     *
     * @return string
     */
    public function run() : string
    {
        $process = new Process($this->_command);
        $process->mustRun();

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return $process->getOutput();
    }
}
