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
interface DirectoryInterface {
    
    /**
     * Retrieves dir's real path.
     *
     * @return string Directory path.
     */
    public function getPath() : string;

    /**
     * Retrieves directory items.
     * @param bool $recursive
     * @return array containing FileInterface, DirectoryInterface implementation instances.
     */
    public function getItems() : array;
}
