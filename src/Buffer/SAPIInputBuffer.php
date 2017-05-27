<?php
namespace Funk\Buffer;

use PHPSGI\Buffer\ReadableBuffer;

class SAPIInputBuffer implements ReadableBuffer
{
    protected $fd;

    public function __construct()
    {
        $this->fd = fopen('php://input', 'r');
    }

    public function read($bytes)
    {
        return fread($this->fd, $bytes);
    }

    public function __destruct()
    {
        fclose($this->fd);
    }
}

