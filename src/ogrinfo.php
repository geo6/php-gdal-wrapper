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

class ogrinfo
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
    private $source;

    /**
     * @var string[]
     */
    private $layers;

    public function __construct($source, $layers = [])
    {
        $this->source = $source;
        $this->layers = (is_string($layers) ? [$layers] : $layers);
        $this->options = new ogrinfo\options();

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
        if ($this->options->ro === true) {
            $options .= ' -ro';
        }
        if ($this->options->q === true) {
            $options .= ' -q';
        }
        if (!empty($this->options->where)) {
            $options .= sprintf(' -where %s', escapeshellarg($this->options->where));
        }
        if (!empty($this->options->spat)) {
            $options .= sprintf(' -spat %f %f %f %f', $this->options->spat[0], $this->options->spat[1], $this->options->spat[2], $this->options->spat[3]);
        }
        if (!empty($this->options->geomfield)) {
            $options .= sprintf(' -geomfield %s', escapeshellarg($this->options->geomfield));
        }
        if (!empty($this->options->fid)) {
            $options .= sprintf(' -fid %s', $this->options->fid);
        }
        if (!empty($this->options->sql)) {
            $options .= sprintf(' -sql %s', escapeshellarg($this->options->sql));
        }
        if (!empty($this->options->dialect)) {
            $options .= sprintf(' -dialect %s', escapeshellarg($this->options->dialect));
        }
        if ($this->options->al === true) {
            $options .= ' -al';
        }
        if ($this->options->rl === true) {
            $options .= ' -rl';
        }
        if ($this->options->so === true) {
            $options .= ' -so';
        }
        if (!empty($this->options->fields)) {
            $options .= sprintf(' -fields %s', escapeshellarg($this->options->fields));
        }
        if (!empty($this->options->geom)) {
            $options .= sprintf(' -geom %s', escapeshellarg($this->options->geom));
        }
        if ($this->options->formats === true) {
            $options .= ' --formats';
        }
        if ($this->options->nomd === true) {
            $options .= ' -nomd';
        }
        if ($this->options->listmdd === true) {
            $options .= ' -listmdd';
        }
        if (!empty($this->options->mdd)) {
            $options .= sprintf(' -mdd %s', escapeshellarg($this->options->mdd));
        }
        if ($this->options->nocount === true) {
            $options .= ' -nocount';
        }
        if ($this->options->noextent === true) {
            $options .= ' -noextent--';
        }

        if (!empty($this->options->oo) && is_array($this->options->oo)) {
            foreach ($this->options->oo as $name => $value) {
                $options .= sprintf(' -oo %s', escapeshellarg(sprintf('%s=%s', $name, $value)));
            }
        }

        $this->command = sprintf('ogrinfo %s %s %s', $options, escapeshellarg($this->source), implode(' ', $this->layers));

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

namespace GDAL\ogrinfo;

class options
{
    private $helpGeneral = false;
    private $ro = false;
    private $q = false;
    private $where = null;
    private $spat = null;
    private $geomfield = null;
    private $fid = null;
    private $sql = null;
    private $dialect = null;
    private $al = false;
    private $rl = false;
    private $so = false;
    private $fields = 'YES';
    private $geom = 'YES';
    private $formats = false;
    private $oo = [];
    private $nomd = false;
    private $listmdd = false;
    private $mdd = null;
    private $nocount = false;
    private $noextent = false;

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
