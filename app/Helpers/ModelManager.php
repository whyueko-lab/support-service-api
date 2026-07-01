<?php

namespace App\Helpers;

class ModelManager
{
    protected string $modelPath;

    public function __construct()
    {
        $folder = WRITEPATH . 'ai/';

        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        $this->modelPath = $folder . 'model.json';
    }

    public function save(array $model): bool
    {
        return file_put_contents(
            $this->modelPath,
            json_encode(
                $model,
                JSON_PRETTY_PRINT |
                JSON_UNESCAPED_UNICODE
            )
        ) !== false;
    }

    public function load(): array
    {
        if (!file_exists($this->modelPath)) {
            throw new \Exception("Model belum tersedia.");
        }

        return json_decode(
            file_get_contents($this->modelPath),
            true
        );
    }

    public function exists(): bool
    {
        return file_exists($this->modelPath);
    }

    public function getPath(): string
    {
        return $this->modelPath;
    }
}