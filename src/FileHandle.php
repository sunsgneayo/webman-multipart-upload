<?php
declare(strict_types=1);

namespace Sunsgne\WebmanMultipartUpload;

use InvalidArgumentException;
use Sunsgne\WebmanMultipartUpload\trait\Instance;

/**
 * @Time 2022/12/26 14:56
 * @author sunsgne
 */
class FileHandle
{

    use Instance;


    /**
     * 创建目录
     *
     * @param string $dirPath 目录路径
     * @return boolean
     */
    public function createDir(string $dirPath): bool
    {
        if (is_dir($dirPath)) {
            return true;
        }
        return mkdir($dirPath, 0755, true);
    }



    /**
     * 删除非空目录
     * 说明:只能删除非系统和特定权限的文件,否则会出现错误
     *
     * @param string $dirPath 目录路径
     * @param boolean $all     是否删除所有
     * @return boolean
     */
    public function removeDir(string $dirPath, bool $all = false): bool
    {
        $dirName = $this->pathReplace($dirPath);
        $handle = @opendir($dirName);
        while (($file = @readdir($handle)) !== FALSE) {
            if ($file != '.' && $file != '..') {
                $dir = $dirName . '/' . $file;
                if ($all) {
                    is_dir($dir) ? $this->removeDir($dir) : $this->removeFile($dir);
                } else {
                    if (is_file($dir)) {
                        $this->removeFile($dir);
                    }
                }
            }
        }
        closedir($handle);
        return @rmdir($dirName);
    }



    /**
     * 删除文件
     *
     * @param string $path 文件路径
     * @return boolean
     */
    public function removeFile(string $path): bool
    {
        $path = $this->pathReplace($path);
        if (file_exists($path)) {
            return unlink($path);
        }

        return true;
    }





    /**
     * 获取文件后缀名
     *
     * @param string $path 文件路径
     * @return string
     */
    public function getExt(string $path): string
    {
        return pathinfo($this->pathReplace($path), PATHINFO_EXTENSION);
    }

    /**
     * 重命名文件
     *
     * @param string $oldFileName 旧名称
     * @param string $newFileNmae 新名称
     * @return boolean
     */
    public function rename(string $oldFileName, string $newFileNmae): bool
    {
        if (($oldFileName != $newFileNmae) && is_writable($oldFileName)) {
            return rename($oldFileName, $newFileNmae);
        }

        return false;
    }

    /**
     * 读取文件内容
     *
     * @param string $file 文件路径
     * @return string
     *@throws InvalidArgumentException
     */
    public function read(string $file): string
    {
        if (!file_exists($file)) {
            throw new InvalidArgumentException('file not found[' . $file . ']');
        }
        return file_get_contents($file);
    }



    /**
     * 路径替换相应的字符
     *
     * @param string $path 路径
     * @return string
     */
    protected function pathReplace(string $path): string
    {
        return str_replace('//', '/', str_replace('\\', '/', $path));
    }
}