<?php

namespace App\Helpers;


class DatasetGenerator
{

    protected array $dataset = [];

    protected array $templates = [

        "{device} {problem}",

        "{device} mengalami {problem}",

        "{device} sedang {problem}",

        "{device} dalam kondisi {problem}",

        "{problem} pada {device}",

        "{problem} terjadi pada {device}",

        "{device} tidak bisa digunakan karena {problem}",

        "{device} tidak dapat digunakan",

        "mohon periksa {device} karena {problem}",

        "mohon pengecekan {device}",

        "terjadi {problem} pada {device}",

        "operator melaporkan {device} {problem}",

        "{device} mengalami gangguan",

        "{device} tidak normal",

        "{device} bermasalah",

        "unit {device} mengalami {problem}",

        "perangkat {device} {problem}",

        "{device} tidak berfungsi",

        "{device} gagal bekerja",

        "{device} perlu diperiksa"
    ];
 
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

    public function get(): array
    {

        return $this->dataset;

    }
 
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

        $this->add("BTS","bts backpack tidak transmit");
        $this->add("BTS","backpack tidak transmit");
        $this->add("BTS","bts backpack mati");
        $this->add("BTS","bts backpack tidak aktif");
        $this->add("BTS","bts tidak berfungsi");
        $this->add("BTS","backpack tidak berfungsi");
        $this->add("BTS","bts tidak bisa transmit");
        $this->add("BTS","backpack tidak bisa transmit");
        $this->add("BTS","bts transmitter tidak berfungsi");

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

    public function generatePrinter(): void
    {

        $device=[

            "printer",
            "epson",
            "canon",
            "hp printer"

        ];

        $problem=[

            "paper jam",
            "offline",
            "tinta habis",
            "tidak mencetak",
            "error"

        ];

        foreach($device as $d){

            foreach($problem as $p){

                $this->add("PRINTER","$d $p");

                $this->add("PRINTER","$p pada $d");

            }

        }

    }

    public function generateLaptop(): void
    {

        $problem=[

            "lambat",
            "hang",
            "mati",
            "restart sendiri",
            "tidak menyala",
            "blue screen"

        ];

        foreach($problem as $p){

            $this->add("LAPTOP","laptop $p");

            $this->add("LAPTOP","notebook $p");

        }

    }

    public function generateGPS(): void
    {

        $problem=[

            "tidak lock",
            "signal hilang",
            "offline",
            "error",
            "tidak akurat"

        ];

        foreach($problem as $p){

            $this->add("GPS","gps $p");
            $this->add("GPS","gps tidak mendapatkan sinyal");
            $this->add("GPS","gps tidak terkoneksi");
            $this->add("GPS","gps tidak aktif");
            $this->add("GPS","gps offline");
            $this->add("GPS","gps hilang sinyal");
            $this->add("GPS","tracker tidak aktif");
            $this->add("GPS","gps tidak update");
            $this->add("GPS","gps tidak mengirim lokasi");
            $this->add("GPS","gps tidak muncul");
            $this->add("GPS","alat gps mati");

        }

    }

    public function generateVehicle(): void
    {

        $problem=[

            "aki soak",
            "mesin mati",
            "ban bocor",
            "starter rusak",
            "tidak bisa hidup"

        ];

        foreach($problem as $p){

            $this->add("KENDARAAN","mobil $p");
            $this->add("KENDARAAN","mobil tidak bisa hidup");
            $this->add("KENDARAAN","mobil mogok");
            $this->add("KENDARAAN","mobil tidak menyala");
            $this->add("KENDARAAN","starter mobil rusak");
            $this->add("KENDARAAN","aki mobil habis");
            $this->add("KENDARAAN","mesin mobil mati");
            $this->add("KENDARAAN","mobil tidak dapat digunakan");
            $this->add("KENDARAAN","kendaraan tidak bisa dinyalakan");
            $this->add("KENDARAAN","mobil mati mendadak");
            $this->add("KENDARAAN","mesin tidak hidup");

        }

    }

    public function generateFakeBTS(): void
    {

        $problem=[

            "fake bts terdeteksi",
            "imsi catcher aktif",
            "cell id mencurigakan",
            "mcc mnc berubah",
            "lac berubah",
            "pci berubah",
            "tac berubah",
            "signal tiba tiba penuh",
            "rogue bts"

        ];

        foreach($problem as $p){

            $this->add("FAKE_BTS",$p);
            $this->add("FAKE_BTS","terdeteksi fake bts");
            $this->add("FAKE_BTS","indikasi imsi catcher");
            $this->add("FAKE_BTS","cell id mencurigakan");
            $this->add("FAKE_BTS","tac berubah");
            $this->add("FAKE_BTS","pci berubah");
            $this->add("FAKE_BTS","mcc berubah");
            $this->add("FAKE_BTS","mnc berubah");
            $this->add("FAKE_BTS","rogue bts ditemukan");
            $this->add("FAKE_BTS","indikasi base station palsu");
            $this->add("FAKE_BTS","anomali jaringan seluler");

        }

    }

    public function generateAll(): void
    {
        $this->generateServer();
        $this->generateBTS();
        $this->generateInternet();
        $this->generateWifi();
        $this->generateCCTV();
        $this->generateHardware();
        $this->generateSoftware();
        $this->generatePrinter();
        $this->generateLaptop();
        $this->generateGPS();
        $this->generateVehicle();
        $this->generateFakeBTS();
    }
}