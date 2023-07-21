<div align="center" style="border-radius: 50px">
    <img width="260px"  src="https://cdn.nine1120.cn/logo-i.png" alt="sunsgne">
</div>

**<p align="center">sunsgne/webman-multipart-upload</p>**

**<p align="center">🐬 Webman's File multipart upload  🐬</p>**

<div align="center">

[![Latest Stable Version](http://poser.pugx.org/sunsgne/webman-multipart-upload/v)](https://packagist.org/packages/sunsgne/webman-multipart-upload)
[![Total Downloads](http://poser.pugx.org/sunsgne/webman-multipart-upload/downloads)](https://packagist.org/packages/sunsgne/webman-multipart-upload)
[![Latest Unstable Version](http://poser.pugx.org/sunsgne/webman-multipart-upload/v/unstable)](https://packagist.org/packages/sunsgne/webman-multipart-upload)
[![License](http://poser.pugx.org/sunsgne/webman-multipart-upload/license)](https://packagist.org/packages/sunsgne/webman-multipart-upload)
[![PHP Version Require](http://poser.pugx.org/sunsgne/webman-multipart-upload/require/php)](https://packagist.org/packages/sunsgne/webman-multipart-upload)

</div>

### 说明
这是一个基于 PHP 实现的大文件分片上传开源项目，它允许用户将大文件分成小片段进行上传，并最终合并为完整的文件。项目利用文件的 MD5 值进行分片上传和合并请求，确保数据的完整性和准确性。

### 如何工作？
通常情况下，上传大文件可能会面临以下问题：上传过程中的网络不稳定、上传耗时过长、服务器内存不足等。为了解决这些问题，本示例项目采用了分片上传的策略，将大文件拆分成若干小片段，分别上传到服务器。一旦所有分片都上传完成，服务器再将这些分片按照特定算法合并为完整的文件。

整个过程主要分为以下几个步骤：

- 文件拆分：客户端将待上传的大文件拆分成固定大小的小片段，通常称为分片或块。

- 分片上传：客户端逐个将这些分片上传到服务器，每个分片都带有它在整个文件中的偏移量信息和对应的 MD5 值。

- 服务器存储：服务器接收到上传的分片后，将其存储在一个临时目录中，等待所有分片都上传完毕。

- 分片合并：当所有分片都上传完毕后，服务器根据接收到的分片顺序和 MD5 值进行验证，确保所有分片的完整性。然后按顺序将它们合并为完整的文件。

- 清理临时文件：合并完成后，服务器将删除所有临时存储的分片文件，释放空间。

### 引用
```shell
composer require sunsgne/webman-multipart-upload
```

### 在webman中使用
伪代码 仅供参考：
```php
    public function MultipartUpload(Request $request): Response
    {
        try {
            $data = v::input($request->post(), [
                'action'      => v::nullable(v::stringVal()->in(['slice', 'merge']))->setName("action"),
                'filename'    => v::stringVal()->setName("filename"),
                'chunk'       => v::nullable(v::intVal()->min(0))->setName("chunk"),
                'chunkLength' => v::intVal()->min(0)->setName("chunkLength"),
                'uuid'        => v::stringVal()->setName("uuid"), 
            ]);
            $apk  = $request->file('files');
            /** 验证上传分片必须的参数 */
            if ($request->post('action') == 'slice' && is_null($request->post('chunk'))) {
                return response_error(ErrorCode::BAD_REQUEST, "chunk required, parameter[action ,chunk] should appear at the same time");
            }
            if ($request->post('action') == 'slice' && empty($request->file('files'))) {
                return response_error(ErrorCode::BAD_REQUEST, "upload error,parameter[slice ,file] should appear at the same time");
            }

            $sdk = new MultipartUpload();
            if ($data['action'] == 'slice') {
                /** 保存分片 */
                return response_success(
                    $sdk->upload($data['uuid'], $apk, intval($data['chunk']))
                );
            }
            /** @var  $mergeInfo *合并分片 */
            $mergeInfo = $sdk->merge($data['uuid'], intval($data['chunkLength']), $data['filename']);
            return response_success($mergeInfo);
        } catch (UploadException $e) {
            return response_error(ErrorCode::BAD_REQUEST, $e->getMessage());
        }
    }
```

### 前端示例
详见： `./example/upload.html`


### 注意事项
- 为了保证文件的完整性，建议在客户端和服务器端同时校验 MD5 值。

- 分片大小需要根据网络环境和服务器配置进行调整，以达到最佳上传性能。

- 请确保服务器具有足够的磁盘空间来存储大文件的分片和最终合并后的完整文件。

### 贡献
欢迎对该项目提供贡献！您可以提交问题、建议或者发起 Pull Request。在贡献前，请确保您的代码符合项目的编码规范。

### 授权
本项目基于开源协议 MIT License 发布，您可以自由使用、修改和分发本项目，但请在您的项目中包含原始项目的授权信息。
