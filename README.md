Object oriented design approach, that provides a convenient way for working with your file system.

### Requirements
`PHP >= 7.0`

### Install

Composer

```javascript
{
    "require": {
        "niletphp/file-system": ">=v1.0"
    }
}
```

###Handling errors
By default the library registers custom error handler, 
which throws \Error when PHP emits E_WARNING or E_ERROR. 
The \Error is then being catched and `Nilet\Components\FileSystem\FileSystemException` is thrown

###Examples

File system
```php
use Nilet\Components\FileSystem\FileSystem;
use Nilet\Components\FileSystem\FileSystem\Directory;
use Nilet\Components\FileSystem\FileSystem\File;
use Nilet\Components\FileSystem\FileSystem\FileSystemException;

$fs = new FileSystem(); // with custom error handler
$fs = new FileSystem(false); // without custom error handler

// If you want to restore to previous custom event handler
$fs->restoreErrorHandler();

// Create directory example with try-catch
try {
    $fs->createDirectory(new Directory("foo/bar"), "nested/dir", 0750);
} catch(FileSystemException $exc) {
    // handle exception
}

// Delete all items of a given directory
$fs->cleanDirectory(new Directory("foo/bar"));

// Delete directory
$fs->deleteDirectory(new Directory("foo"));

// Create file
$file = $fs->createFile(new Directory("bar"), "foo.txt", 0640, "Foo bar");

// Delete file
$fs->deleteFile(new File("bar/foo.txt"));

/**
* Make copy of a given file. 
* If the file already exists in the destination folder, it will be overwritten. 
*/
$fs->copyFile(new File("foo/bar.txt"), new Directory("baz"));

/**
* Move uploaded file. 
* If the file already exists in the destination folder, it will be overwritten.
*/
$fs->moveUploadedFile(new Directory("bar"), "tmp/tmpName.txt", "qux.txt")
```

Working with files

```php
$file = new File("foo/bar.txt");

// Retrieve file name
$file->getName();

// Retrieve file name (with file extension)
$file->getBasename();

// Retrieve file extension e.g. txt, csv, php etc.
$file->getExtension();

//Retrieve file parent directory e.g. given a "foo/bar/baz.txt" will return  "foo/bar"
$file->getPath();

// Retrieve file real path e.g. /foo/bar/public_html/baz.php
$file->getRealPath();

// Retrieve file size in bytes
$file->getSize();

// Retrieve file mime type e.g. text/plain , text/html, image/gif etc.
$file->getMimeType();

// Retrieve files last modified time in "Y-m-d H:i:s" format
$file->getLastModifiedTime();

// Retrieve file contents as string
$file->getContents();

// Truncates a file to a given length
$file->truncate(10);

// Delete file contents
$file->deleteContents();

// Determine if file is readable
$file->isReadable();

// Determine if file is writable
$file->isWritable();

// Appends data
$file->append("foobarbaz");

// Create file
File::create(new Directory("foo"), "bar.txt", 0640, "foobarbaz");
```

Working with directories
```php
$dir = new Directory("foo");

// Retrieves dir's real path.
$dir->path();

// Retrieves directory items, array of File and Directory instances
$dir->getItems();

// Create directory
Directory::create(Directory("foo"), "baz/qux", 0750);
```
