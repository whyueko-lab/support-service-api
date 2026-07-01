<?php

namespace App\Helpers;

class DatasetGenerator
{

    protected array $dataset = [];

    /**
     * Tambah data
     */
    public function add(
        string $kategori,
        string $text
    ): void
    {

        $this->dataset[] = [

            'kategori'=>$kategori,

            'text'=>$text

        ];

    }

    /**
     * Ambil seluruh dataset
     */
    public function get(): array
    {

        return $this->dataset;

    }

    /**
     * Simpan menjadi JSON
     */
    public function save(string $path): void
    {

        file_put_contents(

            $path,

            json_encode(

                $this->dataset,

                JSON_PRETTY_PRINT|
                JSON_UNESCAPED_UNICODE

            )

        );

    }

}