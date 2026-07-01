<?php

namespace App\Helpers;

class ModelManager
{
    protected string $modelPath;

    public function __construct()
    {
        $this->modelPath = storage_path('app/model.json');
    }

    public function save(array $model): bool
    {
        return file_put_contents(
            $this->modelPath,
            json_encode(
                $model,
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
            )
        ) !== false;
    }

    public function load(): array
    {
        if (!file_exists($this->modelPath)) {

            throw new \Exception("Model belum dibuat.");

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
}