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

        /**
     * Generate Dataset Server
     */
    public function generateServer(): void
    {

        $subjek=[

            "server",
            "database",
            "mysql",
            "application server",
            "api",
            "backend"

        ];

        $masalah=[

            "down",
            "mati",
            "offline",
            "restart",
            "hang",
            "error",
            "overload",
            "timeout",
            "crash",
            "tidak aktif"

        ];

        foreach($subjek as $s){

            foreach($masalah as $m){

                $this->add(

                    "SERVER",

                    "$s $m"

                );

                $this->add(

                    "SERVER",

                    "$m pada $s"

                );

                $this->add(

                    "SERVER",

                    "$s mengalami $m"

                );

                $this->add(

                    "SERVER",

                    "$s sedang $m"

                );

            }

        }

    }

    public function generateBTS(): void
    {

        $device=[

            "bts",
            "rf",
            "radio",
            "transmitter",
            "multirat"

        ];

        $problem=[

            "tidak transmit",
            "gagal transmit",
            "mati",
            "offline",
            "tidak aktif",
            "tidak memancar",
            "signal hilang",
            "restart",
            "hang"

        ];

        foreach($device as $d){

            foreach($problem as $p){

                $this->add(

                    "BTS",

                    "$d $p"

                );

                $this->add(

                    "BTS",

                    "$p pada $d"

                );

                $this->add(

                    "BTS",

                    "$d mengalami $p"

                );

            }

        }

    }

}