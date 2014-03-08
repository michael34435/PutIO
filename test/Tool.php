<?php

class Tool 
{
    public static function unzip($source, $destination) {
        @mkdir($destination, 0777, true);
        
        echo PHP_EOL . "Preparing Zip..." . PHP_EOL;
        $zip = new ZipArchive;
        if ($zip->open(str_replace("//", "/", $source)) === true) {
            echo "Starting..." . PHP_EOL;
            $zip->extractTo($destination);
            $zip->close();
            echo "Finihing..." . PHP_EOL;
        }
    }
}
?>