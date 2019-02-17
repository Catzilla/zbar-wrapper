<?php

namespace Catzilla\ZBarWrapper;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

use Catzilla\ZBarWrapper\ZBarResultCollection;

class ZBarWrapper
{
    const TYPES = [
        'code128',
        'code39',
        'ean13',
        'ean8',
        'i25',
        //'isbn10',
        //'isbn13',
        'qrcode',
        'upca',
        'upce'
    ];

    private $args;
    private $filters;
    private $zbarimg;
    private $source;
    private $unlink;

    function __construct()
    {
        $this->setZbarimgPath(
            PHP_OS_FAMILY == 'Windows' ?
            'C:\Program Files (x86)\ZBar\bin\zbarimg' :
            '/usr/bin/zbarimg'
        );
        $this->setZbarimgArgs([]);
        $this->typesAll();
    }

    /**
     * Sets path to zbarimg binary
     *
     * @param string $path
     */
    public function setZbarimgPath($path)
    {
        $this->zbarimg = $path;
    }

    /**
     * Sets additional arguments to zbarimg
     *
     * @param Array $args
     */
    public function setZbarimgArgs($args)
    {
        $this->args = $args;
    }

    /**
     * Select image source (file path or URL)
     *
     * @param string $source
     */
    public function select($source)
    {
        if (filter_var($source, FILTER_VALIDATE_URL) !== false) {
            $ext = '.' . pathinfo($source, PATHINFO_EXTENSION);
            $tmpfile = tempnam(sys_get_temp_dir(), 'ZBarWrapper');
            $this->source = $tmpfile . $ext;
            rename($tmpfile, $this->source);
            file_put_contents($this->source, file_get_contents($source));
            $this->unlink = true;
        } else {
            $this->source = realpath($source);
            $this->unlink = false;
        }
    }

    /**
     * Include all barcode types to result
     */
    public function typesAll()
    {
        $this->filters = [
            '--set',
            'enable'
        ];
    }

    /**
     * Exclude all barcode types from result
     */
    public function typesNone()
    {
        $this->filters = [
            '--set',
            'disable'
        ];
    }

    /**
     * Sets barcode types thats will be included to result
     *
     * @param Array $types
     */
    public function typesOnly($types)
    {
        $this->typesNone();

        $types = array_intersect($types, self::TYPES);

        foreach ($types as $type) {
            $this->filters[] = '--set';
            $this->filters[] = $type . '.enable';
        }
    }

    /**
     * Sets barcode types thats will be excluded from result
     *
     * @param Array $types
     */
    public function typesExcept($types)
    {
        $this->typesAll();

        $types = array_intersect($types, self::TYPES);

        foreach ($types as $type) {
            $this->filters[] = '--set';
            $this->filters[] = $type . '.disable';
        }
    }

    /**
     * Returns all decoded barcodes from source image
     *
     * @param string $source
     * @return Catzilla\ZBarWrapper\ZBarResultCollection
     * @throws Symfony\Component\Process\Exception\ProcessFailedException
     */
    public function decode($source = null)
    {
        if ($source) {
            $this->select($source);
        }

        if (!$this->source) {
            throw new \Exception('No valid source specified');
        }

        $args = [
            $this->zbarimg,
            '--quiet'
        ];

        $args = array_merge($args, $this->args);
        $args = array_merge($args, $this->filters);
        $args[] = $this->source;

        $process = new Process($args);
        $process->run();

        if ($this->unlink) {
            unlink($this->source);
        }

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return new ZBarResultCollection($process->getOutput());
    }

    /**
     * Returns first decoded barcode from source image
     *
     * @param string $source
     * @return Catzilla\ZBarWrapper\ZBarResult
     * @throws Symfony\Component\Process\Exception\ProcessFailedException
     */
    public function decodeSingle($source = null)
    {
        return $this->decode($source)->first();
    }
}
