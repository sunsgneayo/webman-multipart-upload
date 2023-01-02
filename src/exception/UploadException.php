<?php
declare(strict_types=1);

namespace Sunsgne\WebmanMultipartUpload\exception;

/**
 * @Time 2022/12/26 14:52
 * @author sunsgne
 */
class UploadException extends \RuntimeException
{


    /**
     * 上传目录不存在或不可写入
     */
    const ERROR_UPLOAD_DIR_NOT_FOUND = 5;



    /**
     * 上传文件大小不符
     */
    const ERROR_UPLOAD_SIZE_FAILD = 9;


    /**
     * 上传文件后缀不允许
     */
    const ERROR_UPLOAD_EXT_FAILD = 11;



    /**
     * 分片文件不完整
     */
    const ERROR_CHUNK_FAILD = 13;
}