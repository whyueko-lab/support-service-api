<?php

if (!function_exists('klasifikasiNaiveBayes')) {

    function klasifikasiNaiveBayes($deskripsi)
    {
        // =========================
        // 1. Preprocessing Text
        // =========================
        $text = strtolower($deskripsi);
        $text = preg_replace('/[^a-zA-Z0-9\s]/', ' ', $text);
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);

        // =========================
        // 2. Normalisasi Kata Tidak Baku
        // =========================
        $slang = [
            'lemot'  => 'lambat',
            'ngadat' => 'error',
            'gabisa' => 'tidak bisa',
            'gak'    => 'tidak',
            'ga'     => 'tidak',
            'gk'     => 'tidak',
            'tdk'    => 'tidak',
            'bisa'   => 'bisa',
            'net'    => 'internet',
            'inet'   => 'internet',
            'wifi'   => 'wifi',
            'wifie'  => 'wifi',
            'wi-fi'  => 'wifi',
            'ap'     => 'access point',
            'cctv'   => 'cctv',
            'cam'    => 'kamera',
            'camera' => 'kamera',
            'kamera' => 'kamera',
            'nvr'    => 'nvr',
            'dvr'    => 'dvr',
            'srv'    => 'server',
            'db'     => 'database',
            'apps'   => 'aplikasi',
            'app'    => 'aplikasi',
            'login'  => 'login',
            'cpu'    => 'cpu',
            'ram'    => 'memory',
            'memori' => 'memory',
            'hdd'    => 'harddisk',
            'ssd'    => 'harddisk',
            'rf'     => 'rf',
            'bts'    => 'bts',
            'hunter' => 'hunter',
            'disruptor' => 'disruptor',
            'monitoring' => 'monitoring',
            'tx' => 'transmit',
            'transmisi' => 'transmit',
            'pancar' => 'transmit',
            'memancar' => 'transmit',
            'sinyal' => 'signal',
            'unit' => 'mobil',
            'kendaraan' => 'mobil',
            'zenix' => 'mobil',
            '2g' => '2g',
            'gsm' => '2g',
            '3g' => '3g',
            '4g' => '4g',
            '5g' => '5g',
            'modem' => 'modem',
            'router' => 'router',
            'gateway' => 'gateway',
            'multirat' => 'multirat',
            'kabel' => 'kabel',
            'port' => 'port',
            'kabel rf' => 'kabel rf',
            'switch' => 'switch',
            'on off' => 'on off',
            'restart' => 'restart',
            'reset' => 'reset'
        ];

        foreach ($slang as $slangWord => $correctWord) {
            $text = preg_replace(
                '/\b' . preg_quote($slangWord, '/') . '\b/',
                $correctWord,
                $text
            );
        }

        // Pecah kalimat input menjadi kata
        $words = explode(' ', $text);

        // =========================
        // 3. Dataset Training Sederhana
        // =========================
        $trainingData = [
            // =========================
            // Kategori Server
            // =========================
            [
                'text' => 'server down aplikasi tidak bisa diakses service mati',
                'kategori' => 'server',
                'prioritas' => 'high'
            ],
            [
                'text' => 'cpu server tinggi memory penuh database lambat',
                'kategori' => 'server',
                'prioritas' => 'high'
            ],
            [
                'text' => 'storage server hampir penuh backup gagal',
                'kategori' => 'server',
                'prioritas' => 'high'
            ],
            [
                'text' => 'server tidak merespon ping timeout akses gagal',
                'kategori' => 'server',
                'prioritas' => 'high'
            ],
            [
                'text' => 'service apache nginx mysql tidak berjalan',
                'kategori' => 'server',
                'prioritas' => 'high'
            ],
            [
                'text' => 'log server penuh sistem berjalan lambat',
                'kategori' => 'server',
                'prioritas' => 'medium'
            ],
            [
                'text' => 'backup server perlu dicek jadwal backup tidak jalan',
                'kategori' => 'server',
                'prioritas' => 'medium'
            ],
            [
                'text' => 'akses remote server gagal ssh rdp tidak bisa login',
                'kategori' => 'server',
                'prioritas' => 'high'
            ],
            [
                'text' => 'server monitoring tidak menampilkan data perangkat lapangan',
                'kategori' => 'server',
                'prioritas' => 'high'
            ],
            [
                'text' => 'database server penuh menyebabkan aplikasi lambat',
                'kategori' => 'server',
                'prioritas' => 'high'
            ],
            [
                'text' => 'service monitoring server berhenti dan perlu restart',
                'kategori' => 'server',
                'prioritas' => 'high'
            ],
            [
                'text' => 'akses rdp server gagal dari jaringan kantor',
                'kategori' => 'server',
                'prioritas' => 'medium'
            ],

            // Tambahan Kategori Internet
            [
                'text' => 'internet mobil operasional tidak stabil saat digunakan',
                'kategori' => 'internet',
                'prioritas' => 'high'
            ],
            [
                'text' => 'koneksi internet ke server pusat sering timeout',
                'kategori' => 'internet',
                'prioritas' => 'medium'
            ],
            [
                'text' => 'router gateway tidak mendapatkan koneksi dari provider',
                'kategori' => 'internet',
                'prioritas' => 'high'
            ],

            // =========================
            // Kategori Internet
            // =========================
            [
                'text' => 'internet kantor putus koneksi tidak stabil',
                'kategori' => 'internet',
                'prioritas' => 'high'
            ],
            [
                'text' => 'bandwidth lambat akses website lama terbuka',
                'kategori' => 'internet',
                'prioritas' => 'medium'
            ],
            [
                'text' => 'gateway internet tidak bisa diakses jaringan keluar mati',
                'kategori' => 'internet',
                'prioritas' => 'high'
            ],
            [
                'text' => 'latency tinggi koneksi ke server pusat lambat',
                'kategori' => 'internet',
                'prioritas' => 'medium'
            ],
            [
                'text' => 'koneksi isp bermasalah internet cabang mati',
                'kategori' => 'internet',
                'prioritas' => 'high'
            ],
            [
                'text' => 'internet sering timeout saat akses aplikasi internal',
                'kategori' => 'internet',
                'prioritas' => 'medium'
            ],
            [
                'text' => 'modem router internet tidak mendapatkan ip',
                'kategori' => 'internet',
                'prioritas' => 'high'
            ],
            [
                'text' => 'dns bermasalah website tidak bisa dibuka',
                'kategori' => 'internet',
                'prioritas' => 'medium'
            ],

            // =========================
            // Kategori WiFi
            // =========================
            [
                'text' => 'wifi tidak bisa connect ssid tidak muncul',
                'kategori' => 'wifi',
                'prioritas' => 'medium'
            ],
            [
                'text' => 'access point mati lampu indikator tidak menyala',
                'kategori' => 'wifi',
                'prioritas' => 'high'
            ],
            [
                'text' => 'wifi lambat banyak user tidak stabil',
                'kategori' => 'wifi',
                'prioritas' => 'medium'
            ],
            [
                'text' => 'password wifi tidak bisa digunakan autentikasi gagal',
                'kategori' => 'wifi',
                'prioritas' => 'medium'
            ],
            [
                'text' => 'sinyal wifi lemah di area kerja',
                'kategori' => 'wifi',
                'prioritas' => 'low'
            ],
            [
                'text' => 'client wifi sering terputus roaming bermasalah',
                'kategori' => 'wifi',
                'prioritas' => 'medium'
            ],
            [
                'text' => 'access point overload user terlalu banyak',
                'kategori' => 'wifi',
                'prioritas' => 'medium'
            ],
            [
                'text' => 'wifi kantor tidak mendapatkan ip dhcp gagal',
                'kategori' => 'wifi',
                'prioritas' => 'medium'
            ],

            // =========================
            // Kategori CCTV
            // =========================
            [
                'text' => 'kamera cctv mati tidak tampil di monitor',
                'kategori' => 'cctv',
                'prioritas' => 'high'
            ],
            [
                'text' => 'rekaman cctv hilang nvr tidak menyimpan video',
                'kategori' => 'cctv',
                'prioritas' => 'high'
            ],
            [
                'text' => 'kamera buram gelap infrared tidak menyala',
                'kategori' => 'cctv',
                'prioritas' => 'medium'
            ],
            [
                'text' => 'dvr nvr tidak bisa diakses dari jaringan',
                'kategori' => 'cctv',
                'prioritas' => 'high'
            ],
            [
                'text' => 'cctv offline kamera ip tidak terhubung',
                'kategori' => 'cctv',
                'prioritas' => 'high'
            ],
            [
                'text' => 'tampilan kamera patah patah delay tinggi',
                'kategori' => 'cctv',
                'prioritas' => 'medium'
            ],
            [
                'text' => 'harddisk cctv penuh perlu pengecekan storage',
                'kategori' => 'cctv',
                'prioritas' => 'medium'
            ],
            [
                'text' => 'akses live view cctv gagal dari aplikasi',
                'kategori' => 'cctv',
                'prioritas' => 'medium'
            ],
            [
                'text' => 'kamera cctv kendaraan tidak tampil di aplikasi monitoring',
                'kategori' => 'cctv',
                'prioritas' => 'high'
            ],
            [
                'text' => 'rekaman cctv mobil tidak tersimpan di storage',
                'kategori' => 'cctv',
                'prioritas' => 'high'
            ],
            [
                'text' => 'kamera cctv offline setelah perangkat jaringan restart',
                'kategori' => 'cctv',
                'prioritas' => 'medium'
            ],

            // =========================
            // Kategori Perangkat BTS / Lab Seluler
            // =========================
            [
                'text' => 'bts 2g tidak mau transmit di mobil operasional',
                'kategori' => 'perangkat_bts',
                'prioritas' => 'high'
            ],
            [
                'text' => 'bts 2g tidak transmit unit mobil tidak keluar sinyal',
                'kategori' => 'perangkat_bts',
                'prioritas' => 'high'
            ],
            [
                'text' => 'perangkat bts di mobil zenix tidak mau transmit',
                'kategori' => 'perangkat_bts',
                'prioritas' => 'high'
            ],
            [
                'text' => 'bts mobile tidak transmit perlu pengecekan teknisi',
                'kategori' => 'perangkat_bts',
                'prioritas' => 'high'
            ],
            [
                'text' => 'modul bts 2g tidak aktif transmit gagal',
                'kategori' => 'perangkat_bts',
                'prioritas' => 'high'
            ],
            [
                'text' => 'perangkat bts kendaraan tidak mengirim sinyal',
                'kategori' => 'perangkat_bts',
                'prioritas' => 'high'
            ],
            [
                'text' => 'bts mobil tidak keluar transmit setelah dinyalakan',
                'kategori' => 'perangkat_bts',
                'prioritas' => 'high'
            ],
            [
                'text' => 'unit bts zenix nomor mobil bermasalah transmit tidak jalan',
                'kategori' => 'perangkat_bts',
                'prioritas' => 'high'
            ],
            [
                'text' => 'perangkat bts lab tidak sinkron modul tidak aktif',
                'kategori' => 'perangkat_bts',
                'prioritas' => 'high'
            ],
            [
                'text' => 'dashboard monitoring bts tidak menampilkan status perangkat',
                'kategori' => 'perangkat_bts',
                'prioritas' => 'medium'
            ],
            [
                'text' => 'perangkat seluler lab gagal booting setelah restart',
                'kategori' => 'perangkat_bts',
                'prioritas' => 'high'
            ],
            [
                'text' => 'status modul bts tidak terbaca di sistem monitoring',
                'kategori' => 'perangkat_bts',
                'prioritas' => 'high'
            ],
            [
                'text' => 'alarm perangkat bts muncul perlu pengecekan teknisi',
                'kategori' => 'perangkat_bts',
                'prioritas' => 'medium'
            ],
            [
                'text' => 'perangkat simulasi jaringan seluler tidak terkoneksi ke server',
                'kategori' => 'perangkat_bts',
                'prioritas' => 'high'
            ],
            [
                'text' => 'log perangkat bts penuh perlu dilakukan pengecekan',
                'kategori' => 'perangkat_bts',
                'prioritas' => 'medium'
            ],
            [
                'text' => 'konfigurasi perangkat lab tidak terbaca oleh aplikasi monitoring',
                'kategori' => 'perangkat_bts',
                'prioritas' => 'medium'
            ],
            [
                'text' => 'bts 2g di mobil tidak transmit setelah perangkat dinyalakan',
                'kategori' => 'perangkat_bts',
                'prioritas' => 'high'
            ],
            [
                'text' => 'bts 4g tidak aktif modul perangkat tidak terbaca',
                'kategori' => 'perangkat_bts',
                'prioritas' => 'high'
            ],
            [
                'text' => 'perangkat bts mobile gagal transmit saat operasional lapangan',
                'kategori' => 'perangkat_bts',
                'prioritas' => 'high'
            ],
            [
                'text' => 'bts kendaraan tidak keluar signal perlu pengecekan teknisi',
                'kategori' => 'perangkat_bts',
                'prioritas' => 'high'
            ],
            [
                'text' => 'perangkat bts multirat tidak sinkron dengan sistem monitoring',
                'kategori' => 'perangkat_bts',
                'prioritas' => 'high'
            ],

            // Tambahan Kategori Perangkat RF
            [
                'text' => 'perangkat rf hunter tidak mendeteksi signal di dashboard',
                'kategori' => 'perangkat_rf',
                'prioritas' => 'high'
            ],
            [
                'text' => 'perangkat rf monitoring offline setelah restart sistem',
                'kategori' => 'perangkat_rf',
                'prioritas' => 'medium'
            ],
            [
                'text' => 'modul rf tidak stabil hasil monitoring berubah ubah',
                'kategori' => 'perangkat_rf',
                'prioritas' => 'medium'
            ],
            [
                'text' => 'data perangkat rf tidak masuk ke aplikasi pusat',
                'kategori' => 'perangkat_rf',
                'prioritas' => 'high'
            ],
            [
                'text' => 'perangkat disruptor internal tidak tampil pada dashboard monitoring',
                'kategori' => 'perangkat_rf',
                'prioritas' => 'high'
            ],

            // =========================
            // Kategori Perangkat RF / Monitoring
            // =========================
            [
                'text' => 'perangkat rf monitoring tidak menampilkan data sinyal',
                'kategori' => 'perangkat_rf',
                'prioritas' => 'high'
            ],
            [
                'text' => 'sensor rf tidak terbaca aplikasi monitoring kosong',
                'kategori' => 'perangkat_rf',
                'prioritas' => 'high'
            ],
            [
                'text' => 'perangkat hunter tidak mengirim data ke dashboard',
                'kategori' => 'perangkat_rf',
                'prioritas' => 'high'
            ],
            [
                'text' => 'modul rf perlu kalibrasi hasil pembacaan tidak stabil',
                'kategori' => 'perangkat_rf',
                'prioritas' => 'medium'
            ],
            [
                'text' => 'perangkat disruptor internal tidak terdeteksi oleh sistem monitoring',
                'kategori' => 'perangkat_rf',
                'prioritas' => 'high'
            ],
            [
                'text' => 'status perangkat rf offline setelah dipindahkan lokasi',
                'kategori' => 'perangkat_rf',
                'prioritas' => 'medium'
            ],
            [
                'text' => 'dashboard rf menampilkan alarm perangkat perlu pengecekan',
                'kategori' => 'perangkat_rf',
                'prioritas' => 'medium'
            ],
            [
                'text' => 'data monitoring spektrum tidak masuk ke server pusat',
                'kategori' => 'perangkat_rf',
                'prioritas' => 'high'
            ],

            // =========================
            // Kategori Hardware
            // =========================
            [
                'text' => 'laptop mati tidak bisa menyala adaptor bermasalah',
                'kategori' => 'hardware',
                'prioritas' => 'medium'
            ],
            [
                'text' => 'komputer lambat harddisk bermasalah',
                'kategori' => 'hardware',
                'prioritas' => 'medium'
            ],
            [
                'text' => 'printer tidak bisa mencetak kertas macet',
                'kategori' => 'hardware',
                'prioritas' => 'medium'
            ],
            [
                'text' => 'monitor tidak tampil layar blank',
                'kategori' => 'hardware',
                'prioritas' => 'medium'
            ],
            [
                'text' => 'keyboard mouse tidak berfungsi',
                'kategori' => 'hardware',
                'prioritas' => 'low'
            ],
            [
                'text' => 'ups bunyi baterai lemah perangkat mati mendadak',
                'kategori' => 'hardware',
                'prioritas' => 'medium'
            ],
            [
                'text' => 'switch jaringan mati port tidak menyala',
                'kategori' => 'hardware',
                'prioritas' => 'high'
            ],
            [
                'text' => 'power supply perangkat rusak tidak ada daya',
                'kategori' => 'hardware',
                'prioritas' => 'high'
            ],

            // =========================
            // Kategori Software
            // =========================
            [
                'text' => 'aplikasi error tidak bisa login',
                'kategori' => 'software',
                'prioritas' => 'medium'
            ],
            [
                'text' => 'database bermasalah data tidak tersimpan',
                'kategori' => 'software',
                'prioritas' => 'high'
            ],
            [
                'text' => 'dashboard aplikasi blank tidak menampilkan data',
                'kategori' => 'software',
                'prioritas' => 'medium'
            ],
            [
                'text' => 'user tidak bisa reset password akun terkunci',
                'kategori' => 'software',
                'prioritas' => 'medium'
            ],
            [
                'text' => 'aplikasi force close keluar sendiri',
                'kategori' => 'software',
                'prioritas' => 'medium'
            ],
            [
                'text' => 'error 500 saat submit form',
                'kategori' => 'software',
                'prioritas' => 'high'
            ],
            [
                'text' => 'api tidak merespon integrasi gagal',
                'kategori' => 'software',
                'prioritas' => 'high'
            ],
            [
                'text' => 'laporan tidak bisa digenerate dari sistem',
                'kategori' => 'software',
                'prioritas' => 'medium'
            ],

            // =========================
            // Kategori Umum
            // =========================
            [
                'text' => 'permintaan bantuan informasi layanan support',
                'kategori' => 'umum',
                'prioritas' => 'low'
            ],
            [
                'text' => 'bertanya prosedur penggunaan sistem',
                'kategori' => 'umum',
                'prioritas' => 'low'
            ],
            [
                'text' => 'permintaan jadwal pengecekan perangkat',
                'kategori' => 'umum',
                'prioritas' => 'low'
            ],
            [
                'text' => 'butuh informasi kontak teknisi',
                'kategori' => 'umum',
                'prioritas' => 'low'
            ],
            [
                'text' => 'permintaan pendampingan penggunaan aplikasi',
                'kategori' => 'umum',
                'prioritas' => 'low'
            ],
            [
                'text' => 'request pengecekan rutin perangkat kantor',
                'kategori' => 'umum',
                'prioritas' => 'low'
            ],
            [
                'text' => 'permintaan laporan status perangkat',
                'kategori' => 'umum',
                'prioritas' => 'low'
            ],
            [
                'text' => 'konsultasi kendala teknis umum',
                'kategori' => 'umum',
                'prioritas' => 'low'
            ],
        ];

        // =========================
        // 4. Inisialisasi Perhitungan
        // =========================
        $categoryCount = [];
        $wordCountPerCategory = [];
        $totalWordsPerCategory = [];
        $vocabulary = [];

        $totalDocuments = count($trainingData);

        // =========================
        // 5. Training Naive Bayes
        // =========================
        foreach ($trainingData as $data) {
            $kategori = $data['kategori'];

            if (!isset($categoryCount[$kategori])) {
                $categoryCount[$kategori] = 0;
                $wordCountPerCategory[$kategori] = [];
                $totalWordsPerCategory[$kategori] = 0;
            }

            $categoryCount[$kategori]++;

            $cleanText = strtolower($data['text']);
            $cleanText = preg_replace('/[^a-zA-Z0-9\s]/', ' ', $cleanText);
            $cleanText = preg_replace('/\s+/', ' ', $cleanText);

            foreach ($slang as $slangWord => $correctWord) {
                $cleanText = preg_replace(
                    '/\b' . preg_quote($slangWord, '/') . '\b/',
                    $correctWord,
                    $cleanText
                );
            }

            $cleanText = trim($cleanText);

            $trainingWords = explode(' ', $cleanText);

            foreach ($trainingWords as $word) {
                if ($word == '') {
                    continue;
                }

                if (!isset($wordCountPerCategory[$kategori][$word])) {
                    $wordCountPerCategory[$kategori][$word] = 0;
                }

                $wordCountPerCategory[$kategori][$word]++;
                $totalWordsPerCategory[$kategori]++;

                $vocabulary[$word] = true;
            }
        }

        $vocabSize = count($vocabulary);

        // =========================
        // 6. Hitung Probabilitas Naive Bayes
        // =========================
        $result = [];

        foreach ($categoryCount as $kategori => $jumlahDokumenKategori) {
            // Prior Probability: P(kategori)
            $prior = $jumlahDokumenKategori / $totalDocuments;

            // Menggunakan log probability agar perhitungan stabil
            $logProbability = log($prior);

            foreach ($words as $word) {
                if ($word == '') {
                    continue;
                }

                $wordFrequency = $wordCountPerCategory[$kategori][$word] ?? 0;

                // Likelihood dengan Laplace Smoothing
                // P(kata | kategori)
                $likelihood = ($wordFrequency + 1) / ($totalWordsPerCategory[$kategori] + $vocabSize);

                $logProbability += log($likelihood);
            }

            $result[] = [
                'kategori' => $kategori,
                'log_score' => $logProbability
            ];
        }

        // =========================
        // 7. Urutkan Berdasarkan Score Tertinggi
        // =========================
        usort($result, function ($a, $b) {
            return $b['log_score'] <=> $a['log_score'];
        });

        $kategoriTerpilih = $result[0]['kategori'];
        $scoreTerpilih = $result[0]['log_score'];

        // =========================
        // 8. Konversi Log Score Menjadi Persentase
        // =========================
        // Teknik ini mirip softmax sederhana.
        // Tujuannya agar score lebih enak dibaca, misalnya 87.52%.
        $maxScore = $result[0]['log_score'];
        $totalExp = 0;

        foreach ($result as $item) {
            $totalExp += exp($item['log_score'] - $maxScore);
        }

        $confidence = exp($scoreTerpilih - $maxScore) / $totalExp;
        $confidencePercent = round($confidence * 100, 2);

        // =========================
        // 9. Tentukan Prioritas
        // =========================
        $priorityMap = [
            'server' => 'high',
            'internet' => 'high',
            'wifi' => 'medium',
            'cctv' => 'high',
            'perangkat_bts' => 'high',
            'perangkat_rf' => 'high',
            'hardware' => 'medium',
            'software' => 'medium',
            'umum' => 'low'
        ];

        return [
            'kategori' => $kategoriTerpilih,
            'prioritas' => $priorityMap[$kategoriTerpilih] ?? 'low',
            'score' => $confidencePercent
        ];
    }
}