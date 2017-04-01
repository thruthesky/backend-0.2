<?php

add_route('upload',[
    'path'=> '\\model\\file\\file_interface',
    'method'=> 'upload',
    'variables'=>[
        'required'=>[],
        'optional'=>[ 'model', 'model_idx', 'code', 'finish' ],
        'system'=>['session_id', 'unique']
    ]
]);



add_route('download',[
    'path'=> '\\model\\file\\file_interface',
    'method'=> 'download',
    'variables'=>[
        'required'=>[ 'idx' ],
        'optional'=>[ 'type', 'width', 'height', 'quality', 'resize', 'x', 'y', 'enlarge' ],
        'system'=>[ 'name' ]
    ],
    'validator' => function () {
        $file = ( new \model\file\File() )->load( in('idx') );
        if ( ! $file->exist() ) return ERROR_FILE_NOT_EXIST;
        return [ $file ];
    }
]);

add_route('file.delete',[
    'path'=> '\\model\\file\\file_interface',
    'method'=> 'delete',
    'variables'=>[
        'required'=>[ 'idx' ],
        'optional'=>[ ],
        'system'=>[ ]
    ],
    'validator' => function() {
        $file = ( new model\file\File() )->load( in('idx' ) );
        if ( $file->user_idx == currentUser() ) {

        }
        else if ( currentUser()->isAdmin() ) {

        }
        else return ERROR_NOT_YOUR_FILE;
        return [ $file ];
    }
]);


add_route('file.test', [
    'path'=> '\\model\\file\\file_test',
    'method'=>'run',
    'variable'=>[
        'required'=>[],
        'optional'=>[],
        'system'=>[]
    ]
]);
//add_route('upload_test', [
//    'path'=> '\\model\\file\\file_test',
//    'method'=>'run',
//    'variable'=>[
//        'required'=>[],
//        'optional'=>[],
//        'system'=>[]
//    ]
//]);