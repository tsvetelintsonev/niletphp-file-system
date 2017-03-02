<?php

namespace Nilet\Components\FileSystem;

use Nilet\Components\ErrorHandling\ErrorHandler;
use PHPUnit\Framework\TestCase;

class FileTest extends TestCase {

    private static $tempDir;

    const DS = DIRECTORY_SEPARATOR;

    /**
     * @var File
     */
    protected $file;

    public static function setUpBeforeClass() {
        self::$tempDir = __DIR__ . self::DS . 'tmp';
        mkdir(self::$tempDir);

    }

    protected function setUp() {
        file_put_contents(self::$tempDir . "/foo.txt", "Nilet");
        $this->file = new File(self::$tempDir . self::DS . "foo.txt");
    }

    /**
     * @expectedException \Nilet\Components\FileSystem\FileNotFoundException
     */
    public function testConstructorThrowsFileNotFoundException() {
        new File(self::$tempDir . self::DS . "bar.txt");
    }

    public function testGetName() {
        $this->assertEquals("foo", $this->file->getName());
    }

    public function testGetBasename() {
        $this->assertEquals("foo.txt", $this->file->getBasename());
    }

    public function testGetExtension() {
        $this->assertEquals("txt", $this->file->getExtension());
    }


    public function testGetPath() {
        $this->assertTrue(self::$tempDir === $this->file->getPath());
    }

    public function testGetRealPath() {
        $this->assertTrue(self::$tempDir.self::DS."foo.txt" === $this->file->getRealPath());
    }

    public function testGetSize() {
        $this->assertEquals(5, $this->file->getSize());
    }

    public function testGetMimeType() {
        $this->assertEquals("text/plain", $this->file->getMimeType());
    }

    public function testGetContents() {
        $this->assertEquals("Nilet", $this->file->getContents());
    }

    public function testTruncate() {
        $this->file->truncate(3);
        $this->assertEquals("Nil", $this->file->getContents());
    }

    public function testDeleteContents() {
        $this->assertTrue($this->file->deleteContents());
        $this->assertEquals(0, $this->file->getSize());
    }

    public function testAppend() {
        $this->file->append("appendedBytes");
        $this->assertTrue("NiletappendedBytes" == $this->file->getContents());
        file_put_contents($this->file->getRealPath(), "");
    }

    public function testIsReadable() {
        chmod($this->file->getRealPath(), 0444);
        $this->assertTrue($this->file->isReadable());

        chmod($this->file->getRealPath(), 0222);
        $this->assertFalse($this->file->isReadable());
    }

    public function testIsWritable() {
        chmod($this->file->getRealPath(), 0222);
        $this->assertTrue($this->file->isWritable());

        chmod($this->file->getRealPath(), 0444);
        $this->assertFalse($this->file->isWritable());
    }

    public static function tearDownAfterClass() {
        (new FileSystem())->deleteDirectory(new Directory(self::$tempDir));
    }
}
