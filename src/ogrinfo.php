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
 * Implements `ogrinfo` function.
 *
 * @author Jonathan Beliën <jbe@geo6.be>
 *
 * @link   http://www.gdal.org/ogrinfo.html
 */
class ogrinfo
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
    private $_source;

    /**
     * @var string[]
     */
    private $_layers;

    /**
     * @param string   $source Datasource.
     * @param string[] $layers Layers from datasource (optional).
     *
     * @return void
     */
    public function __construct(string $source, $layers = [])
    {
        $this->_source = $source;
        $this->_layers = (is_string($layers) ? [$layers] : $layers);
        $this->_options = new ogrinfo\Options();

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
        if ($this->_options->ro === true) {
            $options .= ' -ro';
        }
        if ($this->_options->q === true) {
            $options .= ' -q';
        }
        if (!empty($this->_options->where)) {
            $options .= sprintf(
                ' -where %s',
                escapeshellarg($this->_options->where)
            );
        }
        if (!empty($this->_options->spat)) {
            $options .= sprintf(
                ' -spat %f %f %f %f',
                $this->_options->spat[0],
                $this->_options->spat[1],
                $this->_options->spat[2],
                $this->_options->spat[3]
            );
        }
        if (!empty($this->_options->geomfield)) {
            $options .= sprintf(
                ' -geomfield %s',
                escapeshellarg($this->_options->geomfield)
            );
        }
        if (!empty($this->_options->fid)) {
            $options .= sprintf(
                ' -fid %s',
                $this->_options->fid
            );
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
        if ($this->_options->al === true) {
            $options .= ' -al';
        }
        if ($this->_options->rl === true) {
            $options .= ' -rl';
        }
        if ($this->_options->so === true) {
            $options .= ' -so';
        }
        if (!empty($this->_options->fields)) {
            $options .= sprintf(
                ' -fields %s',
                escapeshellarg($this->_options->fields)
            );
        }
        if (!empty($this->_options->geom)) {
            $options .= sprintf(
                ' -geom %s',
                escapeshellarg($this->_options->geom)
            );
        }
        if ($this->_options->formats === true) {
            $options .= ' --formats';
        }
        if ($this->_options->nomd === true) {
            $options .= ' -nomd';
        }
        if ($this->_options->listmdd === true) {
            $options .= ' -listmdd';
        }
        if (!empty($this->_options->mdd)) {
            $options .= sprintf(
                ' -mdd %s',
                escapeshellarg($this->_options->mdd)
            );
        }
        if ($this->_options->nocount === true) {
            $options .= ' -nocount';
        }
        if ($this->_options->noextent === true) {
            $options .= ' -noextent--';
        }

        if (!empty($this->_options->oo) && is_array($this->_options->oo)) {
            foreach ($this->_options->oo as $name => $value) {
                $options .= sprintf(
                    ' -oo %s',
                    escapeshellarg(sprintf('%s=%s', $name, $value))
                );
            }
        }

        $this->_command = sprintf(
            'ogrinfo %s %s %s',
            $options,
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
