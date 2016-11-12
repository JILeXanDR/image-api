<?php

namespace app;

use app\Plugin\ImagePlugin;

class App
{
    public $config = [];

    public $routes = [];

    public function __construct()
    {
        $this->config = require __DIR__ . '/../config.php';

        $this->routes = [
            '/' => function () {
                phpinfo();
            },
            '/api/image/resize' => 'app\App@apiImageResize',
        ];
    }

    /**
     * @Api
     * @Method GET
     * @Url /api/image/resize
     * @param $request
     */
    public function apiImageResize(array $request)
    {
        $cloud = new GoogleCloudStorage($this->config['gcs']);

        try {

            if (empty($request['imageUrl'])) {
                $this->response(['error' => 'Param `imageUrl` is required!']);
            }

            $newImageSize = $request['size'] ?? [];

            if (!isset($newImageSize['width']) && !isset($newImageSize['height'])) {
                $this->response(['error' => 'Params `width` and `height` are required!']);
            }

            $imageUrl = $cloud->uploadFileByUrl($request['imageUrl']);

            $image = new Image($imageUrl);

            $plugin = new ImagePlugin();

            $newImageSize['height'] = $newImageSize['height'] ?? null;
            $newImageSize['width'] = $newImageSize['width'] ?? null;

            $processedImage = $plugin->resize($image, $newImageSize);

            header('Content-Type: image/png');

            $processedImage->output(IMAGETYPE_PNG);

        } catch (\InvalidArgumentException $e) {
            $this->response(['error' => $e->getMessage()]);
        }
    }

    private function response($data)
    {
        if (is_array($data)) {
            header('Content-Type: application/json');
            echo json_encode($data);
            exit;
        }

        echo $data;
        exit;
    }
}