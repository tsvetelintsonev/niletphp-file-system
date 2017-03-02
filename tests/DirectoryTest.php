<?php

namespace Nilet\Components\FileSystem;

use PHPUnit\Framework\TestCase;

class DirectoryTest extends TestCase {

    private static $tempDir;

    const DS = DIRECTORY_SEPARATOR;

    public static function setUpBeforeClass() {
        self::$tempDir = __DIR__.self::DS.'tmp';
        mkdir(self::$tempDir);
    }

    /**
     * @expectedException Nilet\Components\FileSystem\DirectoryNotFoundException
     */
    public function testInstantiationThrowsDirectoryNotFoundException() {
        new Directory(self::$tempDir .self::DS."missingDir");
    }

    public function testGetPath() {
        $this->assertSame(__DIR__, (new Directory(__DIR__))->getPath());
    }

    public function testGetItems() {
        $dir = Directory::create(new Directory(self::$tempDir), "foo");
        Directory::create($dir, "bar");
        Directory::create($dir, "baz");
        File::create($dir, "foo.txt");
        File::create($dir, "bar.txt");

        $this->assertCount(4, $dir->getItems());
    }

    public function testCreate() {
        $newDir = Directory::create(new Directory(self::$tempDir), "qux");

        $this->assertTrue(is_dir($newDir->getPath()));
    }

    public static function tearDownAfterClass() {
        (new FileSystem())->deleteDirectory(new Directory(self::$tempDir));
    }
}
