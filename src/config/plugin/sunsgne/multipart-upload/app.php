<?php
return [
    'enable' => true,

    'default' => 'default',

    'config' => [
        'default' => [

            'exts'      => [], // 允许上传的文件后缀

            'sliceSize' => 0, // 分片文件大小限制

            'rootPath'  => base_path() . '/runtime/files',  // 保存路径

            'tmpPath'   => 'tmp'  //临时文件存储路径，基于rootPath
        ]
    ]
];