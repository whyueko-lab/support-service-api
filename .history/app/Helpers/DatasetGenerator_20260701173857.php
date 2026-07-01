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

                $this->add( "INTERNET","$d $p" );
                $this->add("INTERNET", "$p pada $d");
                $this->add("INTERNET","internet putus");
                $this->add("INTERNET","internet lambat");
                $this->add("INTERNET","internet tidak terkoneksi");
                $this->add("INTERNET","modem offline");
                $this->add("INTERNET","gateway timeout");
                $this->add("INTERNET","router disconnect");
                $this->add("INTERNET","koneksi internet terputus");
                $this->add("INTERNET","akses internet gagal");

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
                $this->add("PRINTER","printer offline");
                $this->add("PRINTER","printer tidak mencetak");
                $this->add("PRINTER","printer paper jam");
                $this->add("PRINTER","printer error");
                $this->add("PRINTER","printer tinta habis");
                $this->add("PRINTER","printer tidak terdeteksi");
                $this->add("PRINTER","epson offline");
                $this->add("PRINTER","canon error");
                $this->add("PRINTER","hp printer tidak mencetak");

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
            $this->add("LAPTOP","laptop mati");
            $this->add("LAPTOP","laptop tidak menyala");
            $this->add("LAPTOP","laptop lambat");
            $this->add("LAPTOP","laptop hang");
            $this->add("LAPTOP","laptop restart sendiri");
            $this->add("LAPTOP","laptop blue screen");
            $this->add("LAPTOP","laptop overheat");
            $this->add("LAPTOP","laptop tidak bisa booting");
            $this->add("LAPTOP","notebook mati");
            $this->add("LAPTOP","notebook lambat");
            $this->add("LAPTOP","notebook hang");
            $this->add("LAPTOP","layar laptop mati");
            $this->add("LAPTOP","keyboard laptop rusak");
            $this->add("LAPTOP","baterai laptop habis");
            $this->add("LAPTOP","charger laptop rusak");

        }

    }

    public function generateGPS(): void
{
$problem = [

```
    "tidak lock",
    "signal hilang",
    "offline",
    "error",
    "tidak akurat",
    "tidak update",
    "tidak aktif",
    "tidak terkoneksi",
    "tidak mengirim lokasi",
    "gps mati",
    "tracker mati",
    "tracker offline",
    "lokasi tidak muncul",
    "koordinat tidak muncul",
    "gps tidak merespon",
    "gps restart",
    "gps hang",
    "gps tidak mendapatkan sinyal",
    "satelit tidak terdeteksi",
    "gps tidak bisa tracking"
];

foreach ($problem as $p) {

    $this->add("GPS", "gps $p");
    $this->add("GPS", "tracker $p");
    $this->add("GPS", "$p pada gps");
    $this->add("GPS", "$p pada tracker");
    $this->add("GPS", "alat gps $p");
}
```

}

public function generateVehicle(): void
{
$problem = [

```
    "aki soak",
    "mesin mati",
    "ban bocor",
    "starter rusak",
    "tidak bisa hidup",
    "mogok",
    "tidak menyala",
    "overheat",
    "mesin tidak hidup",
    "baterai habis",
    "mesin susah hidup",
    "starter tidak berfungsi",
    "lampu mati",
    "alarm rusak",
    "kelistrikan bermasalah",
    "mesin mati mendadak",
    "tidak dapat digunakan",
    "rem bermasalah",
    "transmisi rusak",
    "kunci tidak berfungsi"
];

foreach ($problem as $p) {

    $this->add("KENDARAAN", "mobil $p");
    $this->add("KENDARAAN", "kendaraan $p");
    $this->add("KENDARAAN", "$p pada mobil");
    $this->add("KENDARAAN", "$p pada kendaraan");
    $this->add("KENDARAAN", "unit kendaraan $p");
}
```

}

public function generateWifi(): void
{
$device = [
"wifi",
"access point",
"hotspot",
"wireless",
"ssid"
];

```
$problem = [
    "putus",
    "lambat",
    "tidak connect",
    "disconnect",
    "tidak aktif",
    "signal lemah",
    "tidak muncul",
    "offline",
    "tidak dapat diakses",
    "sering putus",
    "gagal koneksi",
    "restart",
    "hang",
    "tidak mendapatkan ip",
    "tidak terhubung"
];

foreach ($device as $d) {

    foreach ($problem as $p) {

        $this->add("WIFI", "$d $p");
        $this->add("WIFI", "$p pada $d");
        $this->add("WIFI", "$d mengalami $p");
        $this->add("WIFI", "$d tidak dapat digunakan");
        $this->add("WIFI", "mohon periksa $d karena $p");
    }
}
```

}

public function generateCCTV(): void
{
$device = [
"kamera",
"cctv",
"nvr",
"dvr",
"ip camera"
];

```
$problem = [
    "mati",
    "offline",
    "tidak tampil",
    "gambar hitam",
    "blur",
    "putus",
    "error",
    "tidak merekam",
    "tidak aktif",
    "gambar hilang",
    "tidak menyala",
    "restart",
    "hang",
    "koneksi terputus",
    "storage penuh"
];

foreach ($device as $d) {

    foreach ($problem as $p) {

        $this->add("CCTV", "$d $p");
        $this->add("CCTV", "$p pada $d");
        $this->add("CCTV", "$d mengalami $p");
        $this->add("CCTV", "$d tidak dapat digunakan");
        $this->add("CCTV", "mohon periksa $d karena $p");
    }
}
```

}

public function generateFakeBTS(): void
{
$problem = [

```
    "fake bts terdeteksi",
    "imsi catcher aktif",
    "cell id mencurigakan",
    "mcc mnc berubah",
    "lac berubah",
    "pci berubah",
    "tac berubah",
    "signal tiba tiba penuh",
    "rogue bts",
    "indikasi fake bts",
    "anomali jaringan seluler",
    "base station palsu",
    "bts ilegal terdeteksi",
    "sinyal mencurigakan",
    "perubahan cell id",
    "perubahan tac",
    "perubahan pci",
    "perubahan mcc",
    "perubahan mnc",
    "indikasi imsi catcher"
];

foreach ($problem as $p) {

    $this->add("FAKE_BTS", $p);
    $this->add("FAKE_BTS", "terdeteksi $p");
    $this->add("FAKE_BTS", "indikasi $p");
    $this->add("FAKE_BTS", "$p pada jaringan");
    $this->add("FAKE_BTS", "anomali karena $p");
}
```

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