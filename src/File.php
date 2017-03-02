<?php
/**
 * NiletPHP - Simple and lightweight web MVC framework
 * (c) Tsvetelin Tsonev <github.tsonev@yahoo.com>
 * For copyright and license information of this source code, please view the LICENSE file.
 */

namespace Nilet\Components\FileSystem;

/**
 * @author Tsvetelin Tsonev <github.tsonev@yahoo.com>
 */
class File implements FileInterface {

    /**
     * The File (file path).
     * @var string Description string 
     */
    private $file;

    /**
     * Create new instance.
     * Throws FileNotFoundException if the given parameter is not a file.
     * @param string $file File path.
     * @param bool $sftp
     * @throws FileNotFoundException .
     */
    public function __construct(string $file, $sftp = false) {
        if (($this->file = realpath($file)) === false || !is_file($file)) {
            throw new FileNotFoundException("{$file} does not exist.");
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string {
        return pathinfo($this->file, PATHINFO_FILENAME);
    }

    /**
     * {@inheritdoc}
     */
    public function getBasename(): string {
        return pathinfo($this->file, PATHINFO_BASENAME);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtension(): string {
        return pathinfo($this->file, PATHINFO_EXTENSION);
    }

    /**
     * {@inheritdoc}
     */
    public function getPath(): string {
        return pathinfo($this->file, PATHINFO_DIRNAME);
    }

    /**
     * {@inheritdoc}
     */
    public function getRealPath(): string {
        return $this->file;
    }

    /**
     * {@inheritdoc}
     */
    public function getSize(): int {
        clearstatcache();
        return filesize($this->file);
    }

    /**
     * {@inheritdoc}
     */
    public function getMimeType(): string {
        return finfo_file(finfo_open(FILEINFO_MIME_TYPE), $this->file);
    }

    /**
     * {@inheritdoc}
     */
    public function getLastModifiedTime(): string {
        return date("Y-m-d H:i:s", filemtime($this->file));
    }

    /**
     * {@inheritdoc}
     */
    public function getContents(): string {
        try {
            return file_get_contents($this->file);
        } catch (\Error $err) {
            throw new FileSystemException("Following error occurred while getting {$this->file} contents : {$err->getMessage()}");
        }
    }

    /**
     * {@inheritdoc}
     */
    public function truncate(int $length): string {
        try {
            $resource = fopen($this->file, "rb+");
            ftruncate($resource, $length);
            rewind($resource);
            fclose($resource);
        } catch (\Error $err) {
            throw new FileSystemException("Following error occurred while truncating {$this->file} : {$err->getMessage()}");
        }
        return $this->getContents();
    }

    /**
     * {@inheritdoc}
     */
    public function deleteContents(): bool {
        try {
            file_put_contents($this->file, "");
        } catch (\Error $err) {
            throw new FileSystemException("Following error occurred while deleting {$this->file} contents : {$err->getMessage()}");
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isReadable() : bool {
        return is_readable($this->file);
    }

    /**
     * {@inheritdoc}
     */
    public function isWritable() : bool {
        return is_writeable($this->file);
    }

    /**
     * {@inheritdoc}
     */
    public function append(string $data): bool {
        try {
            file_put_contents($this->file, $data, FILE_APPEND);
        } catch (\Error $err) {
            throw new FileSystemException("Following error occurred while appending data to {$this->file} : {$err->getMessage()}");
        }
        return true;
    }

    /**
     * Create file. Throws FileSystemException on E_ERROR or E_WARNING
     * @param \Nilet\Components\FileSystem\DirectoryInterface $directory instance representing the directory where the file will be created.
     * @param string $fileName File name.
     * @param string $data File content.
     * @param integer $chmod (octal) Access permissions. Default to 0644.
     * @return FileInterface instance representing the newly created file.
     * @throws FileSystemException
     * @internal param array|string $Data to write to the file, if any.
     */
    public static function create(DirectoryInterface $directory, string $fileName, string $data = "", int $chmod = 0644) : FileInterface {
        $filePath = $directory->getPath().DIRECTORY_SEPARATOR.trim($fileName, "\\/");
        try {
            file_put_contents($filePath, $data);
            chmod($filePath, $chmod);
        } catch (\Error $err) {
            throw new FileSystemException("Following error occurred while creating file {$filePath} : {$err->getMessage()}");
        }
        return new File($filePath);
    }
}
