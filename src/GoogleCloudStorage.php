<?php

namespace app;

use Google\Cloud\Storage\StorageClient;
use Google\Cloud\Storage\StorageObject;

class GoogleCloudStorage extends Storage
{
    /** @var StorageClient */
    private $client;

    /** @var \Google\Cloud\Storage\Bucket */
    private $bucket;

    public function __construct(array $config)
    {
        $this->init($config);
    }

    public function init(array $config)
    {
        $this->client = new StorageClient([
            'projectId' => $config['projectId']
        ]);

        $this->bucket = $this->client->bucket($config['bucketName']);
    }

    /**
     * @param string $url
     * @return string Image link
     * @throws \RuntimeException When something happens while file uploading
     */
    public function uploadFileByUrl(string $url)
    {
        $image = @file_get_contents($url);

        if (!is_null(error_get_last())) {
            throw new \RuntimeException(error_get_last()['message']);
        }

        /** @var StorageObject $imageStorageObject */
        $imageStorageObject = $this->bucket->upload($image, [
            'name' => time() . '.png',
            'predefinedAcl' => 'publicRead' // sets link as public
        ]);

        return $imageStorageObject->info()['mediaLink'];
    }
}