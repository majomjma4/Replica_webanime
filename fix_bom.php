<?php
function remove_bom($dir) {
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach($files as $file) {
        if($file->isFile() && $file->getExtension() === "php") {
            $content = file_get_contents($file->getPathname());
            if(substr($content, 0, 3) === "\xef\xbb\xbf") {
                file_put_contents($file->getPathname(), substr($content, 3));
                echo "Removed BOM from " . $file->getPathname() . PHP_EOL;
            }
        }
    }
}
remove_bom("c:/xampp/htdocs/WebAnime_CI4_Replica/app");

