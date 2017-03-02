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
interface FileSystemInterface {
    
    /**
     * Create empty directory/directories. Throws FileSystemException on E_ERROR or E_WARNING
     * @param \Nilet\Components\FileSystem\DirectoryInterface $directory instance representing the directory where the new directory/directories will be created.  
     * @param string $dirName Name of new directory/directories.
     * @param integer $chmod (octal) Access permissions. Default to 0755.
     * @return \Nilet\Components\FileSystem\DirectoryInterface $directory instance representing the last created directory. 
     * @throws FileSystemException
     */
    public function createDirectory(DirectoryInterface $directory, string $dirName, int $chmod = 0755) : DirectoryInterface;
    
    /**
     * Delete all items of a given directory. Throws FileSystemException on E_ERROR or E_WARNING
     * @param \Nilet\Components\FileSystem\DirectoryInterface $directory instance representing the directory which items will be deleted.
     * @return boolean TRUE on success. 
     * @throws FileSystemException
     */
    public function cleanDirectory(DirectoryInterface $directory) : bool;
    
    /**
     * Delete directory. Throws FileSystemException on E_ERROR or E_WARNING
     * @param \Nilet\Components\FileSystem\DirectoryInterface $directory instance representing the directory to be deleted.
     * @return boolean TRUE on success. 
     * @throws FileSystemException
     */
    public function deleteDirectory(DirectoryInterface $directory) : bool;

    /**
     * Create file. Throws FileSystemException on E_ERROR or E_WARNING
     * @param \Nilet\Components\FileSystem\DirectoryInterface $directory instance representing the directory where the file will be created.
     * @param string $fileName File name.
     * @param string $data File content.
     * @param integer $chmod (octal) Access permissions. Default to 0644.
     * @return FileInterface instance representing the newly created file.
     * @internal param array|string $Data to write to the file, if any.
     */
    public function createFile(DirectoryInterface $directory, string $fileName, string $data = "", int $chmod = 0644) : FileInterface;
    
    /**
     * Delete file. Throws FileSystemException on E_ERROR or E_WARNING
     * @param \Nilet\Components\FileSystem\FileInterface $file instance representing the file to be deleted.
     * @return boolean TRUE on success.
     * @throws FileSystemException
     */
    public function deleteFile(FileInterface $file) : bool;
    
    /**
     * Make copy of a given file. If the file already exists in the destination folder, it will be overwritten. 
     * Throws FileSystemException on E_ERROR or E_WARNING
     * @param \Nilet\Components\FileSystem\FileInterface $file instance representing the file to be copied.
     * @param \Nilet\Components\FileSystem\DirectoryInterface $directory instance representing the directory where the file will copied.
     * @return boolean TRUE on success.
     * @throws FileSystemException
     */
    public function copyFile(FileInterface $file, DirectoryInterface $directory) : bool;

    /**
     * Move uploaded file. If the file already exists in the destination folder, it will be overwritten.
     * Throws FileSystemException on E_ERROR or E_WARNING
     * @param DirectoryInterface $targetDirectory Directory where the file should be moved to.
     * @param string $tmpFilePath File's temp path
     * @param string $fileName
     * @throws FileSystemException
     * @return boolean TRUE on success False if file is not uploaded via HTTP POST.
     */
    public function moveUploadedFile(DirectoryInterface $targetDirectory, string $tmpFilePath, string $fileName) : bool;
}
