<?php

namespace model\file;
class File extends \model\entity\Entity
{

    public function __construct()
    {
        parent::__construct();
        $this->setTable('file');
    }


    public function save($userfile)
    {
        $uploadfile = DIR_FILES . '/' . basename($userfile['name']);
        debug_log("move_uploaded_file($userfile[tmp_name], $uploadfile)");


        /*
        while( file_exists( $uploadfile ) ) {
            $basename = basename( $uploadfile );
            list( $filename, $rest )= explode('.', $basename, 2 );
            if( preg_match('/\((\d+)\)$/', $filename, $ms)){
                debug_log($ms);
                $filename = preg_replace('/\(\d+\)$/', "(" . ($ms[1]+1) . ")"  , $filename );
                $uploadfile = DIR_FILES . '/' . $filename . '.' . $rest;
            }
            else {
                $uploadfile = DIR_FILES . '/' . $filename . '(1).' . $rest;
            }
        }
        */

        // @todo use file index to get file name.
        // @todo if fail to upload, delete it from db.
        // @todo delete old files that were not successfully hooked.
        // @todo hook
        // @todo download with filename. ?route=download&size=100x200&quality=100&resize=crop&name=/abcdef.jpg
        // @todo when you get posts, give option of photo size, and other options.
        // @todo count download
        // @todo check/select primary photo among others
        // @todo delete
        // @todo delete all.
        // @todo admin management.
        // @todo

        if ( is_test() ) $re = copy( $userfile['tmp_name'], $uploadfile );
        else $re = move_uploaded_file( $userfile['tmp_name'], $uploadfile );

        if( ! $re ) return ERROR_MOVE_UPLOADED_FILE;

        $idx = $this->set('name',$userfile['name'])
            ->set('size', $userfile['size'])
            ->set('type',$userfile['type'])
            ->set('name_saved', $uploadfile )
            ->set('model', in('model') )
            ->set('model_idx', in('model_idx') )
            ->set('user_idx', currentUser()->idx )
            ->create();
        return $idx;
    }


}