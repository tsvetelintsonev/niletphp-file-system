<?php

namespace Nilet\Components\FileSystem;

use Nilet\Components\ErrorHandling\ErrorHandler;
use PHPUnit\Framework\TestCase;

class FileSystemTest extends TestCase {

    /**
     * @var FileSystem
     */
    protected $fileSystem;

    protected static $tempDir;

    const DS = DIRECTORY_SEPARATOR;

    public static function setUpBeforeClass() {
        $errorHandler = new ErrorHandler();
        $errorHandler->handleErrors();

        self::$tempDir = __DIR__ .self::DS.'tmp';
        mkdir(self::$tempDir);
    }

    protected function setUp() {
        $this->fileSystem = new FileSystem;
    }

    public function testCreateDirectory() {
        $dir = $this->fileSystem->createDirectory(new Directory(self::$tempDir), "tmp1/tmp2", 0750);
        $this->assertFileExists($dir->getPath());
    }

    public function testCreateFile() {
        $file = $this->fileSystem->createFile(new Directory(self::$tempDir), "foo.txt", 0640);
        $this->assertFileExists($file->getRealPath());
    }

    public function testCopyFile() {
        $file = $this->fileSystem->createFile(new Directory(self::$tempDir), "foo.txt");
        $tmp1 = new Directory(self::$tempDir .self::DS."tmp1");
        $moved = $this->fileSystem->copyFile($file, $tmp1);
        $movedFile = new File(self::$tempDir.self::DS."foo.txt");
        $this->assertTrue($moved);
        $this->assertFileExists($movedFile->getRealPath());
    }

    public function testCleanDirectory() {
        $this->makeTestDirectoriesAndFiles();
        $dir = new Directory(self::$tempDir .self::DS."test");
        $this->assertTrue($this->fileSystem->cleanDirectory($dir));
        $this->assertCount(0, $dir->getItems());
    }

    public function testDeleteDirectory() {
        $this->makeTestDirectoriesAndFiles();
        $this->assertTrue($this->fileSystem->deleteDirectory(new Directory(self::$tempDir)));
        $this->assertFileNotExists(self::$tempDir);
    }

    public function makeTestDirectoriesAndFiles() {
        $this->fileSystem->createDirectory(new Directory(self::$tempDir), "test/nested1/nested2");
        $this->fileSystem->createFile(new Directory(self::$tempDir . "/test"), "bar.txt");
        $this->fileSystem->createFile(new Directory(self::$tempDir . "/test/nested1"), "baz.txt");
        $this->fileSystem->createFile(new Directory(self::$tempDir . "/test/nested1/nested2"), "qux.txt");
    }
}
