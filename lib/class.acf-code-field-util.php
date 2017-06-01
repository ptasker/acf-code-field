<?php

/**
 * Class ACF_Code_Field_Util
 *
 * Util class for helper methods
 *
 */
class ACF_Code_Field_Util
{

    public function get_files($dir)
    {
        $iterator  = new DirectoryIterator( $dir );
        $filenames = array();
        foreach ($iterator as $fileinfo) {
            if (! in_array( $fileinfo->getFilename(), array( '.', '..' ) )) {
                $filenames[] = $fileinfo->getFilename();
            }
        }

        return $filenames;
    }

    public function get_codemirror_themes()
    {
        $files = $this->get_files( ACFCF_PLUGIN_DIR . '/js/' . ACFCF_CODEMIRROR_VERSION . '/theme' );

        if (empty( $files )) {
            return array();
        }

        $assoc_array = array();

        foreach ($files as $file) {
            $file                 = str_replace( ".css", "", $file );
            $assoc_array[ $file ] = $file;
        }

        return $assoc_array;
    }
}
