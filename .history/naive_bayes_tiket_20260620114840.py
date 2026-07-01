import re
import math
import random
from collections import defaultdict

# ==========================================
# 1. Kamus Slang (Kata Tidak Baku)
# ==========================================
slang_dict = {
    'lemot': 'lambat', 'ngadat': 'error', 'gabisa': 'tidak bisa', 'gak': 'tidak', 
    'ga': 'tidak', 'gk': 'tidak', 'tdk': 'tidak', 'net': 'internet', 'inet': 'internet',
    'wifie': 'wifi', 'wi-fi': 'wifi', 'ap': 'access point', 'cam': 'kamera', 
    'camera': 'kamera', 'srv': 'server', 'db': 'database', 'apps': 'aplikasi', 
    'app': 'aplikasi', 'ram': 'memory', 'memori': 'memory', 'hdd': 'harddisk', 
    'ssd': 'harddisk', 'tx': 'transmit', 'transmisi': 'transmit', 'pancar': 'transmit',
    'memancar': 'transmit', 'sinyal': 'signal', 'unit': 'mobil', 'kendaraan': 'mobil', 
    'zenix': 'mobil', 'gsm': '2g'
}

priority_map = {
    'server': 'high', 'internet': 'high', 'wifi': 'medium', 'cctv': 'high',
    'perangkat_bts': 'high', 'perangkat_rf': 'high', 'hardware': 'medium',
    'software': 'medium', 'umum': 'low'
}

# ==========================================
# 2. Fungsi Preprocessing
# ==========================================
def preprocess_text(text):
    # Case folding & hapus karakter selain huruf/angka
    text = text.lower()
    text = re.sub(r'[^a-z0-9\s]', ' ', text)
    text = re.sub(r'\s+', ' ', text).strip()
    
    # Ganti kata slang (menggunakan word boundary agar akurat)
    for slang, baku in slang_dict.items():
        text = re.sub(r'\b' + slang + r'\b', baku, text)
        
    return text.split() # Kembalikan dalam bentuk list kata (token)

# ==========================================
# 3. Class Naive Bayes (Logika Utama)
# ==========================================
class NaiveBayesClassifier:
    def __init__(self):
        self.category_count = defaultdict(int)
        self.word_count_per_category = defaultdict(lambda: defaultdict(int))
        self.total_words_per_category = defaultdict(int)
        self.vocabulary = set()
        self.total_documents = 0

    def train(self, training_data):
        self.total_documents = len(training_data)
        
        for data in training_data:
            kategori = data['kategori']
            self.category_count[kategori] += 1
            
            words = preprocess_text(data['text'])
            for word in words:
                if not word: continue
                self.word_count_per_category[kategori][word] += 1
                self.total_words_per_category[kategori] += 1
                self.vocabulary.add(word)

    def predict(self, text):
        words = preprocess_text(text)
        vocab_size = len(self.vocabulary)
        result = []

        for kategori, jumlah_dok_kat in self.category_count.items():
            # Prior probability (dalam log)
            prior = jumlah_dok_kat / self.total_documents
            log_prob = math.log(prior)

            # Likelihood dengan Laplace Smoothing
            for word in words:
                if not word: continue
                word_freq = self.word_count_per_category[kategori].get(word, 0)
                likelihood = (word_freq + 1) / (self.total_words_per_category[kategori] + vocab_size)
                log_prob += math.log(likelihood)

            result.append({'kategori': kategori, 'log_score': log_prob})

        # Urutkan dari score tertinggi
        result.sort(key=lambda x: x['log_score'], reverse=True)
        kategori_terpilih = result[0]['kategori']
        max_score = result[0]['log_score']

        # Hitung probabilitas persentase (Softmax sederhana)
        total_exp = sum(math.exp(item['log_score'] - max_score) for item in result)
        confidence = math.exp(max_score - max_score) / total_exp
        confidence_percent = round(confidence * 100, 2)

        return {
            'kategori': kategori_terpilih,
            'prioritas': priority_map.get(kategori_terpilih, 'low'),
            'score': confidence_percent
        }

# ==========================================
# 4. Generator 100 Data Sampling
# ==========================================
def generate_sample_data(num_samples=100):
    templates = [
        ("server mati tidak bisa diakses", "server"),
        ("database error dan cpu load tinggi", "server"),
        ("koneksi inet putus di cabang", "internet"),
        ("router gateway tidak dapat ip", "internet"),
        ("wifie lambat banyak user", "wifi"),
        ("ap mati lampu indikator merah", "wifi"),
        ("cam mati nvr tidak rekam", "cctv"),
        ("bts zenix ga mau tx", "perangkat_bts"),
        ("sensor rf hunter offline", "perangkat_rf"),
        ("printer kertas macet", "hardware"),
        ("aplikasi force close login gabisa", "software"),
        ("minta jadwal cek rutin", "umum"),
        ("laporan gangguan jaringan", "umum"),
        ("permintaan upgrade perangkat", "umum"),
        ("kendala akses remote server", "server"),
        ("gangguan sinyal di area kantor", "perangkat_rf"),
        ("perangkat wifi sering drop", "wifi"),
        ("kamera cctv tidak menampilkan gambar", "cctv"),
        ("aplikasi mobile tidak bisa login", "software"),
        ("hardware laptop rusak tidak bisa booting", "hardware")
    ]
    
    data = []
    for _ in range(num_samples):
        text, category = random.choice(templates)
        # Menambahkan sedikit noise/variasi agar unik
        noise = random.choice([" bang", " tolong", " segera", " dari kemarin", " di lantai 2", ""])
        data.append({"text": text + noise, "kategori": category})
    return data

# ==========================================
# 5. Blok Eksekusi Utama (Testing)
# ==========================================
if __name__ == "__main__":
    print("Membangun dataset 100 sampel...")
    dataset = generate_sample_data(100)
    
    # Kita pisah: 80 untuk Training, 20 untuk Testing
    training_data = dataset[:80]
    testing_data = dataset[80:]

    # Inisialisasi dan Latih Model
    model = NaiveBayesClassifier()
    model.train(training_data)
    print(f"Model berhasil dilatih dengan {len(training_data)} data!\n")

    print("-" * 50)
    print(" HASIL PENGUJIAN (20 Data Test)")
    print("-" * 50)
    
    benar = 0
    for test in testing_data:
        prediksi = model.predict(test['text'])
        status = "✅ BENAR" if prediksi['kategori'] == test['kategori'] else "❌ SALAH"
        
        if prediksi['kategori'] == test['kategori']:
            benar += 1
            
        print(f"Teks Asli  : '{test['text']}'")
        print(f"Prediksi   : {prediksi['kategori']} (Prioritas: {prediksi['prioritas']})")
        print(f"Keyakinan  : {prediksi['score']}% [{status}]\n")

    akurasi = (benar / len(testing_data)) * 100
    print("=" * 50)
    print(f"Total Akurasi Pengujian: {akurasi}% ({benar}/{len(testing_data)} benar)")
    print("=" * 50)