<?php namespace Idevoc\Dbmanage;

/**
 * Archive Management Library.
 *
 * @author      Anshad
 * @version     1.1.0
 */

class Compress
{
    /**
     * Constructor.
     *
     */

    public function __construct()
    {
        if (!function_exists('gzopen')){
            throw new Exception('Zlib support is not enabled in PHP. Try uncompressed file.');
        }
    }

    /**
     * Compresses a file to .gz.
     *
     * @param string $from The source
     * @param string $to   The target
     */

    public function pack($from, $to)
    {
        if (($gzip = gzopen($to, 'wb')) === false){
            throw new Exception('Unable create compressed file.');
        }

        if (($source = fopen($from, 'rb')) === false){
            throw new Exception('Unable open the compression source file.');
        }

        while (!feof($source)){
            $content = fread($source, 4096);
            gzwrite($gzip, $content, strlen($content));
        }

        gzclose($gzip);
        fclose($source);
    }

    /**
     * Uncompresses a file.
     *
     * @param string $from The source
     * @param string $to   The target
     */

    public function unpack($from, $to)
    {
        if (($gzip = gzopen($from, 'rb')) === false){
            throw new Exception('Unable to read compressed file.');
        }

        if (($target = fopen($to, 'w')) === false){
            throw new Exception('Unable to open the target.');
        }

        while ($string = gzread($gzip, 4096)){
            fwrite($target, $string, strlen($string));
        }

        gzclose($gzip);
        fclose($target);
    }
}