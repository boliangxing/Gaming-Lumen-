<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace Cai\Extend;

use Illuminate\Http\UploadedFile;
use OSS\Core\OssException;
use OSS\OssClient;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class Storage
{
    /**
     * @var OssClient
     */
    protected $client;

    const BUCKET_AVATAR = 'shoucai-avatar';

    const BUCKET_NEWS = 'shoucai-news';

    public function __construct(OssClient $client)
    {
        $this->client = $client;
    }

    /**
     * 上传头像
     *
     * @param UploadedFile $file
     * @return string
     */
    public function uploadAvatar(UploadedFile $file)
    {
        return $this->uploadFile($file, 'avatar');
    }

    /**
     * 上传资讯图片
     *
     * @param UploadedFile $file
     * @return string
     */
    public function uploadNewsImage(UploadedFile $file)
    {
        return $this->uploadFile($file, 'news');
    }

    protected function uploadFile(UploadedFile $file, $category)
    {
        list($subDirectory, $fileName) = $this->getFileName($file);

        $directory = $category . '/' . $subDirectory;

        $targetDirectory = base_path('public') . '/' .  $directory;

        try {
            $file->move($targetDirectory, $fileName);
        } catch (FileException $e) {
            \Log::err($e->getMessage());

            throw new \RuntimeException('文件上传失败');
        }

        $object = $directory . '/' . $fileName;

        $this->put($targetDirectory . '/' . $fileName, $object, $this->getBucketNameByCategory($category));

        return $object;
    }

    /**
     * 上传到OSS服务器
     *
     * @param $filePath
     * @param $object
     * @param $bucket
     * @return bool
     */
    public function put($filePath, $object, $bucket)
    {
        try {
            $this->client->uploadFile($bucket, $object, $filePath);
        } catch (OssException $e) {
            \Log::err($e->getMessage());

            throw new \RuntimeException('文件上传失败，请稍后再试');
        }

        return true;
    }

    protected function getFileName(UploadedFile $file)
    {
        $fileHash = hash_file('md5', $file->getPathname());

        $directoryName = substr($fileHash, 0, 2);

        return  [$directoryName, substr($fileHash, 2). '.' . $file->guessExtension()];
    }

    /**
     * 获取bucket名
     *
     * @param $category
     * @return string
     */
    protected function getBucketNameByCategory($category)
    {
        return self::BUCKET_AVATAR;
        switch ($category) {
            case 'avatar':
                return self::BUCKET_AVATAR;
            case 'news':
                return self::BUCKET_AVATAR;
        }
    }
}