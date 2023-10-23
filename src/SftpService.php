<?php

namespace Cameron\SftpService;

use Exception;
use phpseclib3\Net\SFTP;

class SftpService
{
    public function __construct(
        protected string $host,
        protected string $port,
        protected string $username,
        protected string $password,
        protected string $path,
    ) {}

    /**
     * @throws Exception
     */
    private function connect(): SFTP
    {
        $sftp = new SFTP($this->host, $this->port);

        if (!$sftp->login($this->username, $this->password)) {
            throw new Exception("Error logging into SFTP server: {$sftp->getLastError()}");
        }

        return $sftp;
    }

    /**
     * @throws Exception
     */
    public function write(string $filename, string $data): bool
    {
        $sftp = $this->connect();

        if (!$sftp->put($this->resolveFilePath($filename), $data)) {
            $sftp->disconnect();
            throw new Exception("Error saving file to SFTP server: {$sftp->getLastError()}");
        }

        $sftp->disconnect();
        return true;
    }

    /**
     * @throws Exception
     */
    public function read(string $filename): bool|string
    {
        $sftp = $this->connect();
        $contents = $sftp->get($this->resolveFilePath($filename));

        if (!$contents) {
            $sftp->disconnect();
            throw new Exception("Error retrieving file from SFTP server: {$sftp->getLastError()}");
        }

        $sftp->disconnect();
        return $contents;
    }

    /**
     * @throws Exception
     */
    public function getFilenames(): array
    {
        $files = [];
        $sftp = $this->connect();
        $items = $sftp->nlist($this->path);

        foreach ($items as $item) {
            if ($sftp->is_file($this->resolveFilePath($item))) {
                $files[] = $item;
            }
        }

        $sftp->disconnect();
        return $files;
    }

    protected function resolveFilePath(string $filename): string
    {
        return $this->path . '/' . $filename;
    }
}