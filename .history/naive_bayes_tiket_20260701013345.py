```python
import re
import math
import random
from collections import defaultdict
from sklearn.model_selection import train_test_split
from sklearn.metrics import (
    accuracy_score,
    precision_score,
    recall_score,
    f1_score,
    confusion_matrix,
    classification_report
)

# ==========================================
# 1. Kamus Slang (Kata Tidak Baku)
# ==========================================
slang_dict = {
    'lemot': 'lambat',
    'ngadat': 'error',
    'gabisa': 'tidak bisa',
    'gak': 'tidak',
    'ga': 'tidak',
    'gk': 'tidak',
    'tdk': 'tidak',
    'net': 'internet',
    'inet': 'internet',
    'wifie': 'wifi',
    'wi-fi': 'wifi',
    'ap': 'access point',
    'cam': 'kamera',
    'camera': 'kamera',
    'srv': 'server',
    'db': 'database',
    'apps': 'aplikasi',
    'app': 'aplikasi',
    'ram': 'memory',
    'memori': 'memory',
    'hdd': 'harddisk',
    'ssd': 'harddisk',
    'tx': 'transmit',
    'transmisi': 'transmit',
    'pancar': 'transmit',
    'memancar': 'transmit',
    'sinyal': 'signal',
    'unit': 'mobil',
    'kendaraan': 'mobil',
    'zenix': 'mobil',
    'gsm': '2g'
}

priority_map = {
    'server': 'high',
    'internet': 'high',
    'wifi': 'medium',
    'cctv': 'high',
    'perangkat_bts': 'high',
    'perangkat_rf': 'high',
    'hardware': 'medium',
    'software': 'medium',
    'umum': 'low'
}

# ==========================================
# 2. Preprocessing
# ==========================================
def preprocess_text(text):
    text = text.lower()
    text = re.sub(r'[^a-z0-9\s]', ' ', text)
    text = re.sub(r'\s+', ' ', text).strip()

    for slang, baku in slang_dict.items():
        text = re.sub(r'\b' + re.escape(slang) + r'\b', baku, text)

    return text.split()

# ==========================================
# 3. Naive Bayes Classifier
# ==========================================
class NaiveBayesClassifier:
    def __init__(self):
        self.category_count = defaultdict(int)
        self.word_count_per_category = defaultdict(
            lambda: defaultdict(int)
        )
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
                self.word_count_per_category[kategori][word] += 1
                self.total_words_per_category[kategori] += 1
                self.vocabulary.add(word)

    def predict(self, text):
        words = preprocess_text(text)
        vocab_size = len(self.vocabulary)
        result = []

        for kategori, jumlah_dok_kat in self.category_count.items():

            prior = jumlah_dok_kat / self.total_documents
            log_prob = math.log(prior)

            for word in words:
                word_freq = (
                    self.word_count_per_category[kategori]
                    .get(word, 0)
                )

                likelihood = (
                    (word_freq + 1)
                    /
                    (
                        self.total_words_per_category[kategori]
                        + vocab_size
                    )
                )

                log_prob += math.log(likelihood)

            result.append({
                'kategori': kategori,
                'log_score': log_prob
            })

        result.sort(
            key=lambda x: x['log_score'],
            reverse=True
        )

        max_score = result[0]['log_score']

        total_exp = sum(
            math.exp(item['log_score'] - max_score)
            for item in result
        )

        for item in result:
            prob = (
                math.exp(item['log_score'] - max_score)
                / total_exp
            )
            item['score'] = round(prob * 100, 2)

        kategori_terpilih = result[0]['kategori']

        return {
            'kategori': kategori_terpilih,
            'prioritas': priority_map.get(
                kategori_terpilih,
                'low'
            ),
            'score': result[0]['score'],
            'detail_score': result
        }

# ==========================================
# 4. Dataset Dummy
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
        ("hardware laptop rusak tidak bisa booting", "hardware"),
        ("permintaan reset password user", "umum"),
        ("laporan kerusakan printer kantor", "hardware"),
        ("gangguan koneksi internet di lantai 2", "internet"),
        ("perangkat bts tidak merespon", "perangkat_bts"),
        ("kamera cctv offline di area parkir", "cctv"),
        ("aplikasi web sering error saat submit form", "software"),
        ("kendala akses database dari remote", "server"),
        ("perangkat rf tidak bisa terkoneksi ke jaringan", "perangkat_rf"),
        ("laporan gangguan jaringan di cabang", "umum")
    ]

    data = []

    for _ in range(num_samples):
        text, category = random.choice(templates)

        noise = random.choice([
            " bang",
            " tolong",
            " segera",
            " dari kemarin",
            " di lantai 2",
            ""
        ])

        data.append({
            "text": text + noise,
            "kategori": category
        })

    return data

# ==========================================
# 5. Main Program
# ==========================================
if __name__ == "__main__":

    random.seed(42)

    print("Membangun dataset...")
    dataset = generate_sample_data(100)

    training_data, testing_data = train_test_split(
        dataset,
        test_size=0.2,
        random_state=42,
        shuffle=True
    )

    model = NaiveBayesClassifier()
    model.train(training_data)

    print(f"Training data : {len(training_data)}")
    print(f"Testing data  : {len(testing_data)}")

    y_true = []
    y_pred = []

    print("\nHASIL PENGUJIAN\n")

    for test in testing_data:
        prediksi = model.predict(test['text'])

        y_true.append(test['kategori'])
        y_pred.append(prediksi['kategori'])

        print(f"Teks      : {test['text']}")
        print(f"Asli      : {test['kategori']}")
        print(f"Prediksi  : {prediksi['kategori']}")
        print(f"Prioritas : {prediksi['prioritas']}")
        print(f"Confidence: {prediksi['score']}%")

        print("Score kelas:")
        for item in prediksi['detail_score']:
            print(
                f"  {item['kategori']} : "
                f"{item['score']}%"
            )

        print("-" * 50)

    accuracy = accuracy_score(y_true, y_pred)
    precision = precision_score(
        y_true,
        y_pred,
        average='weighted',
        zero_division=0
    )
    recall = recall_score(
        y_true,
        y_pred,
        average='weighted',
        zero_division=0
    )
    f1 = f1_score(
        y_true,
        y_pred,
        average='weighted',
        zero_division=0
    )

    print("\n===== HASIL EVALUASI =====")
    print(f"Accuracy  : {accuracy*100:.2f}%")
    print(f"Precision : {precision*100:.2f}%")
    print(f"Recall    : {recall*100:.2f}%")
    print(f"F1-Score  : {f1*100:.2f}%")

    labels = sorted(list(set(y_true)))

    print("\nConfusion Matrix:")
    print(
        confusion_matrix(
            y_true,
            y_pred,
            labels=labels
        )
    )

    print("\nClassification Report:")
    print(
        classification_report(
            y_true,
            y_pred,
            zero_division=0
        )
    )
```
