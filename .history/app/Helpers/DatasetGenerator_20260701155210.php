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

    public function generateInternet(): void
    {

        $device=[

            "internet",
            "router",
            "gateway",
            "modem"

        ];

        $problem=[

            "putus",
            "timeout",
            "lambat",
            "disconnect",
            "offline",
            "tidak terkoneksi"

        ];

        foreach($device as $d){

            foreach($problem as $p){

                $this->add(

                    "INTERNET",

                    "$d $p"

                );

                $this->add(

                    "INTERNET",

                    "$p pada $d"

                );

            }

        }

    }

        /**
     * Generate Dataset WIFI
     */
    public function generateWifi(): void
    {

        $device = [

            "wifi",
            "access point",
            "hotspot",
            "wireless"

        ];

        $problem = [

            "putus",
            "lambat",
            "tidak connect",
            "disconnect",
            "tidak aktif",
            "signal lemah",
            "tidak muncul"

        ];

        foreach($device as $d){

            foreach($problem as $p){

                $this->add("WIFI","$d $p");

                $this->add("WIFI","$p pada $d");

                $this->add("WIFI","$d mengalami $p");

            }

        }

    }

    public function generateCCTV(): void
    {

        $device=[

            "kamera",
            "cctv",
            "nvr",
            "dvr"

        ];

        $problem=[

            "mati",
            "offline",
            "tidak tampil",
            "gambar hitam",
            "blur",
            "putus",
            "error"

        ];

        foreach($device as $d){

            foreach($problem as $p){

                $this->add("CCTV","$d $p");

                $this->add("CCTV","$p pada $d");

                $this->add("CCTV","$d mengalami $p");

            }

        }

    }

    public function generateHardware(): void
    {

        $device=[

            "laptop",
            "pc",
            "komputer",
            "motherboard",
            "harddisk",
            "memory"

        ];

        $problem=[

            "mati",
            "hang",
            "blue screen",
            "restart",
            "error",
            "rusak",
            "tidak booting"

        ];

        foreach($device as $d){

            foreach($problem as $p){

                $this->add("HARDWARE","$d $p");

                $this->add("HARDWARE","$p pada $d");

                $this->add("HARDWARE","$d mengalami $p");

            }

        }

    }

    public function generateSoftware(): void
    {

        $software=[

            "windows",
            "office",
            "aplikasi",
            "browser",
            "software"

        ];

        $problem=[

            "error",
            "force close",
            "tidak bisa dibuka",
            "crash",
            "hang",
            "gagal install",
            "license habis"

        ];

        foreach($software as $d){

            foreach($problem as $p){

                $this->add("SOFTWARE","$d $p");

                $this->add("SOFTWARE","$p pada $d");

                $this->add("SOFTWARE","$d mengalami $p");

            }

        }

    }

    
}