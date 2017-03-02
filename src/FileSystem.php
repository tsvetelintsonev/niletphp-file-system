<?php
/**
 * NiletPHP - Simple and lightweight web MVC framework
 * (c) Tsvetelin Tsonev <github.tsonev@yahoo.com>
 * For copyright and license information of this source code, please view the LICENSE file.
 */

namespace Nilet\Components\FileSystem;

use Nilet\Components\ErrorHandling\ErrorHandler;

/**
 * @author Tsvetelin Tsonev <github.tsonev@yahoo.com>
 */
class FileSystem implements FileSystemInterface {

    /**
     * @var ErrorHandler
     */
    private $errorHandler = null;

    public function __construct(bool $initErrorHandler = true) {
        if ($initErrorHandler) {
            $this->errorHandler = new ErrorHandler();
            $this->errorHandler->handleErrors();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function createDirectory(DirectoryInterface $directory, string $dirName, int $chmod = 0755) : DirectoryInterface {
        $_dirName = str_replace(array('\\', '/'), DIRECTORY_SEPARATOR, trim($dirName, '\\/'));
        $path = $directory->getPath().DIRECTORY_SEPARATOR.$_dirName;
        try {
            if (!is_dir($path)) {
                mkdir($path, $chmod, true);
                return new Directory($path);
            }
        } catch (\Error $err) {
            throw new FileSystemException("Following error occurred while making a directory {$path} : {$err->getMessage()}");
        }
    }

    /**
     * {@inheritdoc}
     */
    public function cleanDirectory(DirectoryInterface $directory): bool {
        try {
            $this->deleteDirectoryItems($directory->getItems());
        } catch (\Error $err) {
            throw new FileSystemException("Following error occurred while cleaning directory {$directory->getPath()} : {$err->getMessage()}");
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteDirectory(DirectoryInterface $directory) : bool {
        try {
            $this->deleteDirectoryItems($directory->getItems());
            return rmdir($directory->getPath());
        } catch (\Error $err) {
            throw new FileSystemException("Following error occurred while deleting directory {$directory->getPath()} : {$err->getMessage()}");
        }
    }

    private function deleteDirectoryItems(array $directoryItems) {
        foreach ($directoryItems as $item) {
            if ($item instanceof FileInterface) {
                $this->deleteFile($item);
            } else if ($item instanceof DirectoryInterface) {
                $items = $item->getItems();
                if (!empty($items)) {
                    $this->deleteDirectoryItems($items);
                }
                rmdir($item->getPath());
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function createFile(DirectoryInterface $directory, string $fileName, string $data = "", int $chmod = 0644) : FileInterface {
        $filePath = $directory->getPath().DIRECTORY_SEPARATOR.$fileName;
        try {
            file_put_contents($filePath, $data);
            chmod($filePath, $chmod);
            return new File($filePath);
        } catch (\Error $err) {
            throw new FileSystemException("Following error occurred while creating file {$filePath} : {$err->getMessage()}");
        }
    }

    /**
     * {@inheritdoc}
     */
    public function deleteFile(FileInterface $file) : bool {
        try {
            return unlink($file->getRealPath());
        } catch (\Error $err) {
            throw new FileSystemException("Following error occurred while deleting file {$file->getRealPath()} : {$err->getMessage()}");
        }
    }

    /**
     * {@inheritdoc}
     */
    public function copyFile(FileInterface $file, DirectoryInterface $directory) : bool {
        try {
            return copy($file->getRealPath(), $directory->getPath().DIRECTORY_SEPARATOR.$file->getBasename());
        } catch (\Error $err) {
            throw new FileSystemException("Following error occurred while copying file {$file->getRealPath()} : {$err->getMessage()}");
        }
    }

    /**
     * {@inheritdoc}
     */
    public function moveUploadedFile(DirectoryInterface $targetDirectory, string $tmpFilePath, string $fileName) : bool {
        if (is_uploaded_file($tmpFilePath)) {
            try {
                move_uploaded_file($tmpFilePath, $targetDirectory->getPath().DIRECTORY_SEPARATOR.$fileName);
            } catch (\Error $err) {
                throw new FileSystemException("Following error occurred while moving uploded file {$tmpFilePath} : {$err->getMessage()}");
            }
            return true;
        }
        return false;

    }

    /**
     * Restores the previous error handler function.
     *
     * @return bool
     */
    public function restoreErrorHandler() : bool {
        return $this->errorHandler->restoreErrorHandler();
    }
}
