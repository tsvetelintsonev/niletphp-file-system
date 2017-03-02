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
interface FileInterface {

    /**
     * Retrieve file name (without file extension).
     * @return string
     */
    public function getName(): string;

    /**
     * Retrieve file name (with file extension).
     * @return string
     */
    public function getBasename(): string;

    /**
     * Retrieve file extension e.g. txt, csv, php etc.
     * @return string
     */
    public function getExtension(): string;

    /**
     * Retrieve file parent directory e.g. given a "foo/bar/baz.txt" will return  "foo/bar"
     * @return string
     */
    public function getPath(): string;

    /**
     * Retrieve file real path e.g. /foo/bar/public_html/baz.php
     * @return string
     */
    public function getRealPath(): string;

    /**
     * Retrieve file size in bytes.
     * @return integer
     */
    public function getSize(): int;

    /**
     * Retrieve file mime type e.g. text/plain , text/html, image/gif etc.
     * @return string
     */
    public function getMimeType(): string;
    
    /**
     * Retrieve files last modified time
     * @return string Time in "Y-m-d H:i:s" format.
     */
    public function getLastModifiedTime() : string;
    
    /**
     * Retrieve file contents as string. Throws FileSystemException on E_ERROR or E_WARNING
     * @return string File content.
     * @throws FileSystemException
     */
    public function getContents() : string;

    /**
     * Truncates a file to a given length. Throws FileSystemException on E_ERROR or E_WARNING
     *
     * @param int $length
     * @return string File content after truncation.
     */
    public function truncate(int $length) : string;
    
    /**
     * Delete file contents. Throws FileSystemException on E_ERROR or E_WARNING
     * @return boolean TRUE on success.
     * @throws FileSystemException
     */
    public function deleteContents() : bool;
    
    /**
     * Determine if file is readable.
     * @return bool TRUE if the file is readable, FALSE otherwise.
     */
    public function isReadable() : bool;
    
    /**
     * Determine if file is writable.
     * @return bool TRUE if the file is writable, FALSE otherwise.
     */
    public function isWritable() : bool;
    
    /**
     * Appends data. Throws FileSystemException on E_ERROR or E_WARNING
     * @param string $data Data to be appended to the file.
     * @return boolean TRUE on success.
     * @throws FileSystemException
     */
    public function append(string $data) : bool;
}
