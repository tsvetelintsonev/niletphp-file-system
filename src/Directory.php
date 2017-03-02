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
class Directory implements DirectoryInterface {

    /**
     * @var string Directory path. 
     */
    private $path;

    /**
     * @var array containing FileInterface, DirectoryInterface implementation instances. 
     */
    private $contents = [];

    /**
     * Create new instance.
     * Throws DirectoryNotFoundException if the given parameter is not a directory.
     * @param string $path Directory path.
     * @throws DirectoryNotFoundException
     */
    public function __construct(string $path) {
        if (($this->path = realpath($path)) == false || !is_dir($path)) {
            throw new DirectoryNotFoundException("{$path} does not exists");
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getPath() : string {
        return $this->path;
    }

    /**
     * {@inheritdoc}
     */
    public function getItems() : array {
        $handle = opendir($this->path);
        while (($entry = readdir($handle)) !== false) {
            if ($entry != "." && $entry != "..") {
                $this->addToContents($entry);
            }
        }
        closedir($handle);
        $contents = $this->contents;
        $this->contents = [];
        return $contents;
    }

    private function addToContents(string $entry) {
        $entryRealpath = $this->path.DIRECTORY_SEPARATOR.$entry;
        if ( is_file($entryRealpath) ) {
            $this->contents[] = new File($entryRealpath);
        } else if( is_dir($entryRealpath) ) {
            $this->contents[] = new Directory($entryRealpath);
        }
    }

    /**
     * Creates empty directory/directories. Throws FileSystemException on E_ERROR or E_WARNING
     * @param \Nilet\Components\FileSystem\DirectoryInterface $directory Target directory.
     * @param string $dirName Name of new directory/directories.
     * @param integer $chmod (octal) Access permissions. Default to 0755.
     * @return \Nilet\Components\FileSystem\DirectoryInterface The new directory.
     * @throws FileSystemException
     */
    public static function create(DirectoryInterface $directory, string $dirName, int $chmod = 0755) : DirectoryInterface {
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
}
