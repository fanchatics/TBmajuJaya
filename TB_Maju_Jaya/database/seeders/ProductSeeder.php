<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Seed the 23 hardware store products and save distinct product images to storage/app/public/products/.
     *
     * @param  array<string, Category>|null  $categories
     */
    public function run(?array $categories = null): void
    {
        if (! $categories) {
            $categories = Category::all()->keyBy('slug')->all();
        }

        Storage::disk('public')->makeDirectory('products');

        // [category slug, name, price (Rp), stock, unit, description, optional Unsplash URL]
        $products = [
            [
                'semen-adukan',
                'Semen Tiga Roda 50kg',
                65000,
                120,
                'sak',
                'Semen portland komposit untuk pondasi, kolom, plesteran dan acian. Kekuatan tinggi dan mengikat merata.',
                'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?w=600&q=80',
            ],
            [
                'semen-adukan',
                'Semen Gresik 40kg',
                56000,
                80,
                'sak',
                'Semen serbaguna untuk pekerjaan konstruksi rumah tinggal.',
                'https://images.unsplash.com/photo-1590069261209-f8e9b8642343?w=600&q=80',
            ],
            [
                'semen-adukan',
                'Mortar Instan MU-301 40kg',
                98000,
                45,
                'sak',
                'Adukan siap pakai untuk pasangan bata dan plesteran. Tinggal tambah air.',
                'https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=600&q=80',
            ],
            [
                'semen-adukan',
                'Semen Instan Perekat Keramik 25kg',
                89000,
                8,
                'sak',
                'Perekat keramik dan granit tile, daya rekat kuat, anti lepas.',
                'https://images.unsplash.com/photo-1581094288338-2314dddb7ece?w=600&q=80',
            ],
            [
                'pipa-fitting',
                'Pipa Rucika AW 3/4 inch (4m)',
                45000,
                90,
                'batang',
                'Pipa PVC kelas AW untuk instalasi air bersih. Tahan tekanan dan tidak berkarat.',
                'https://images.unsplash.com/photo-1581092160607-ee22621dd758?w=600&q=80',
            ],
            [
                'pipa-fitting',
                'Pipa Rucika D 3 inch (4m)',
                78000,
                60,
                'batang',
                'Pipa PVC untuk saluran pembuangan air kotor.',
                'https://images.unsplash.com/photo-1542013936693-884638332954?w=600&q=80',
            ],
            [
                'pipa-fitting',
                'Elbow PVC 1/2 inch',
                3000,
                400,
                'pcs',
                'Sambungan belokan 90 derajat untuk pipa PVC 1/2 inch.',
                'https://images.unsplash.com/photo-1585704032915-c3400ca199e7?w=600&q=80',
            ],
            [
                'pipa-fitting',
                'Kran Air Kuningan 1/2 inch',
                28000,
                70,
                'pcs',
                'Kran taman bahan kuningan, tahan karat dan awet.',
                'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?w=600&q=80',
            ],
            [
                'pipa-fitting',
                'Lem Pipa PVC Aica Aibon 100g',
                12000,
                6,
                'pcs',
                'Lem khusus sambungan pipa PVC, kering cepat.',
                'https://images.unsplash.com/photo-1572981779307-38b8cabb2407?w=600&q=80',
            ],
            [
                'cat-pelapis',
                'Cat Dulux Catylac Putih 5kg',
                125000,
                35,
                'kaleng',
                'Cat tembok interior daya tutup tinggi, hasil akhir halus dan mudah diaplikasikan.',
                'https://images.unsplash.com/photo-1562259949-e8e7689d7828?w=600&q=80',
            ],
            [
                'cat-pelapis',
                'Cat Avitex Exterior 5kg',
                168000,
                25,
                'kaleng',
                'Cat tembok eksterior tahan cuaca dan jamur.',
                'https://images.unsplash.com/photo-1589939705384-5185137a7f0f?w=600&q=80',
            ],
            [
                'cat-pelapis',
                'Cat Kayu & Besi Kuas Nippon 1kg',
                54000,
                50,
                'kaleng',
                'Cat sintetis untuk kayu dan besi, mengkilap tahan lama.',
                'https://images.unsplash.com/photo-1513519245088-0e12902e5a38?w=600&q=80',
            ],
            [
                'perkakas',
                'Paku Beton 5cm (1 Dus)',
                25000,
                200,
                'dus',
                'Paku beton baja keras isi +/- 1 kg.',
                'https://images.unsplash.com/photo-1586864387967-d02ef85d93e8?w=600&q=80',
            ],
            [
                'perkakas',
                'Palu Konde 1kg',
                55000,
                30,
                'pcs',
                'Palu konde gagang kayu, kepala baja tempa.',
                'https://images.unsplash.com/photo-1586864387789-628af9feed72?w=600&q=80',
            ],
            [
                'perkakas',
                'Meteran Roll 5m Stanley',
                35000,
                40,
                'pcs',
                'Meteran gulung 5 meter, pita baja anti karat.',
                'https://images.unsplash.com/photo-1572981779307-38b8cabb2407?w=600&q=80',
            ],
            [
                'perkakas',
                'Bor Tangan Listrik 13mm',
                385000,
                9,
                'pcs',
                'Bor listrik 500W, kecepatan variabel, cocok untuk kayu, besi dan tembok.',
                'https://images.unsplash.com/photo-1504148455328-c376907d081c?w=600&q=80',
            ],
            [
                'listrik',
                'Kabel NYM 2x1.5mm (50m)',
                395000,
                18,
                'roll',
                'Kabel listrik inti tembaga SNI untuk instalasi rumah.',
                'https://images.unsplash.com/photo-1544724569-5f546fd6f2b5?w=600&q=80',
            ],
            [
                'listrik',
                'Saklar Tunggal Broco',
                14000,
                150,
                'pcs',
                'Saklar engkel inbow warna putih.',
                'https://images.unsplash.com/photo-1558002038-1055907df827?w=600&q=80',
            ],
            [
                'listrik',
                'Lampu LED Philips 12W',
                38000,
                5,
                'pcs',
                'Lampu LED hemat energi cahaya putih, garansi 1 tahun.',
                'https://images.unsplash.com/photo-1550985616-10810253b84d?w=600&q=80',
            ],
            [
                'baja-atap',
                'Baja Ringan C75 0.75mm (6m)',
                98000,
                100,
                'batang',
                'Rangka atap baja ringan galvalume C75 tebal 0.75mm, anti karat dan rayap.',
                'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?w=600&q=80',
            ],
            [
                'baja-atap',
                'Reng Baja Ringan 0.45mm (6m)',
                42000,
                160,
                'batang',
                'Reng baja ringan untuk dudukan genteng metal/keramik.',
                'https://images.unsplash.com/photo-1535732759880-bbd5c7265e3f?w=600&q=80',
            ],
            [
                'baja-atap',
                'Genteng Metal Pasir 0.30mm (1m)',
                52000,
                75,
                'lembar',
                'Genteng metal berlapis pasir, ringan dan tahan panas.',
                'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=600&q=80',
            ],
            [
                'baja-atap',
                'Besi Beton Polos 8mm (12m)',
                62000,
                140,
                'batang',
                'Besi beton polos SNI diameter 8mm untuk tulangan.',
                'https://images.unsplash.com/photo-1504917595217-d4dc5ebe6122?w=600&q=80',
            ],
        ];

        foreach ($products as [$categorySlug, $name, $price, $stock, $unit, $description, $remoteUrl]) {
            $slug = Str::slug($name);
            $imagePath = $this->saveProductImage($slug, $categorySlug, $name, $unit, $remoteUrl);

            Product::updateOrCreate(
                ['slug' => $slug],
                [
                    'category_id' => $categories[$categorySlug]->id,
                    'name' => $name,
                    'price' => $price,
                    'stock' => $stock,
                    'unit' => $unit,
                    'description' => $description,
                    'image' => $imagePath,
                ]
            );
        }
    }

    /**
     * Save or generate a distinct local product image in storage/app/public/products/
     * and return its relative path (e.g. "products/semen-tiga-roda-50kg.svg").
     */
    private function saveProductImage(string $slug, string $categorySlug, string $name, string $unit, ?string $remoteUrl = null): string
    {
        // Optional remote download mode when explicitly enabled via SEED_DOWNLOAD_IMAGES=true
        if (env('SEED_DOWNLOAD_IMAGES', false) && $remoteUrl && ! app()->environment('testing')) {
            $jpgPath = "products/{$slug}.jpg";
            if (Storage::disk('public')->exists($jpgPath)) {
                return $jpgPath;
            }
            try {
                $response = Http::timeout(5)->get($remoteUrl);
                if ($response->successful() && strlen($response->body()) > 1000) {
                    Storage::disk('public')->put($jpgPath, $response->body());

                    return $jpgPath;
                }
            } catch (\Throwable) {
                // Fall through to deterministic SVG illustration generator
            }
        }

        $svgPath = "products/{$slug}.svg";
        $svgContent = $this->buildProductSvg($slug, $categorySlug, $name, $unit);
        Storage::disk('public')->put($svgPath, $svgContent);

        return $svgPath;
    }

    /**
     * Generate a bespoke, category- and product-specific vector illustration for each of the 23 products.
     */
    private function buildProductSvg(string $slug, string $categorySlug, string $name, string $unit): string
    {
        [$bgStart, $bgEnd, $accent, $badgeText, $illustration] = $this->productVisualSpec($slug, $categorySlug);

        $escapedName = htmlspecialchars($name, ENT_XML1);
        $escapedBadge = htmlspecialchars($badgeText, ENT_XML1);
        $escapedUnit = htmlspecialchars('SATUAN: ' . strtoupper($unit), ENT_XML1);

        // Split product name into 2 clean lines if longer than 24 chars
        $words = explode(' ', $name);
        $line1 = '';
        $line2 = '';
        foreach ($words as $w) {
            if (strlen($line1 . ' ' . $w) <= 24 || $line1 === '') {
                $line1 = trim($line1 . ' ' . $w);
            } else {
                $line2 = trim($line2 . ' ' . $w);
            }
        }
        $line1Xml = htmlspecialchars($line1, ENT_XML1);
        $line2Xml = htmlspecialchars($line2, ENT_XML1);

        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 600 600" width="600" height="600" role="img" aria-label="{$escapedName}">
  <defs>
    <linearGradient id="bg-{$slug}" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0%" stop-color="{$bgStart}"/>
      <stop offset="100%" stop-color="{$bgEnd}"/>
    </linearGradient>
    <linearGradient id="card-{$slug}" x1="0" y1="0" x2="0" y2="1">
      <stop offset="0%" stop-color="#ffffff" stop-opacity="0.12"/>
      <stop offset="100%" stop-color="#ffffff" stop-opacity="0.03"/>
    </linearGradient>
    <filter id="shadow-{$slug}" x="-15%" y="-15%" width="130%" height="130%">
      <feDropShadow dx="0" dy="10" stdDeviation="12" flood-color="#020617" flood-opacity="0.38"/>
    </filter>
  </defs>

  <!-- Background -->
  <rect width="600" height="600" fill="url(#bg-{$slug})"/>
  <circle cx="520" cy="90" r="190" fill="{$accent}" fill-opacity="0.12"/>
  <circle cx="80" cy="520" r="210" fill="#ffffff" fill-opacity="0.05"/>

  <!-- Top Category & Store Header -->
  <rect x="36" y="32" width="190" height="34" rx="17" fill="{$accent}"/>
  <text x="131" y="54" text-anchor="middle" font-family="Inter, Segoe UI, Arial, sans-serif" font-size="13" font-weight="800" fill="#0f172a" letter-spacing="1.2">{$escapedBadge}</text>
  <text x="564" y="54" text-anchor="end" font-family="Inter, Segoe UI, Arial, sans-serif" font-size="13" font-weight="700" fill="#cbd5e1" letter-spacing="1">TB MAJU JAYA</text>

  <!-- Center Stage Backdrop -->
  <rect x="48" y="84" width="504" height="340" rx="28" fill="url(#card-{$slug})" stroke="#ffffff" stroke-opacity="0.14" stroke-width="1.5"/>

  <!-- Bespoke Product Illustration -->
  <g filter="url(#shadow-{$slug})">
    {$illustration}
  </g>

  <!-- Bottom Product Label Plate -->
  <rect x="36" y="446" width="528" height="122" rx="20" fill="#0f172a" fill-opacity="0.82" stroke="{$accent}" stroke-opacity="0.55" stroke-width="1.5"/>
  <text x="60" y="488" font-family="Inter, Segoe UI, Arial, sans-serif" font-size="22" font-weight="800" fill="#f8fafc">{$line1Xml}</text>
  <text x="60" y="518" font-family="Inter, Segoe UI, Arial, sans-serif" font-size="20" font-weight="700" fill="#e2e8f0">{$line2Xml}</text>
  <text x="60" y="548" font-family="Inter, Segoe UI, Arial, sans-serif" font-size="13" font-weight="700" fill="{$accent}" letter-spacing="1.4">{$escapedUnit}</text>
</svg>
SVG;
    }

    /**
     * Return [bgStart, bgEnd, accentColor, categoryBadge, svgArtwork] tailored for each product slug.
     *
     * @return array{0:string,1:string,2:string,3:string,4:string}
     */
    private function productVisualSpec(string $slug, string $categorySlug): array
    {
        return match ($slug) {
            // ==================== SEMEN & ADUKAN ====================
            'semen-tiga-roda-50kg' => [
                '#0f2540', '#1e3a8a', '#f59e0b', 'SEMEN & ADUKAN',
                <<<'ART'
                <!-- Cement Sack 50kg Tiga Roda -->
                <rect x="195" y="110" width="210" height="285" rx="22" fill="#d6c7b2" stroke="#b8a48c" stroke-width="4"/>
                <rect x="195" y="145" width="210" height="215" fill="#f8fafc"/>
                <rect x="195" y="145" width="210" height="32" fill="#1d4ed8"/>
                <rect x="195" y="328" width="210" height="32" fill="#dc2626"/>
                <!-- Three Interlocking Wheels Emblem -->
                <circle cx="282" cy="235" r="22" fill="none" stroke="#1d4ed8" stroke-width="6"/>
                <circle cx="318" cy="235" r="22" fill="none" stroke="#1d4ed8" stroke-width="6"/>
                <circle cx="300" cy="206" r="22" fill="none" stroke="#dc2626" stroke-width="6"/>
                <text x="300" y="166" text-anchor="middle" font-family="Inter, Arial, sans-serif" font-size="14" font-weight="900" fill="#ffffff" letter-spacing="2">TIGA RODA</text>
                <text x="300" y="292" text-anchor="middle" font-family="Inter, Arial, sans-serif" font-size="16" font-weight="900" fill="#0f172a">SEMEN PCC</text>
                <text x="300" y="350" text-anchor="middle" font-family="Inter, Arial, sans-serif" font-size="18" font-weight="900" fill="#ffffff">50 KG</text>
ART,
            ],

            'semen-gresik-40kg' => [
                '#064e3b', '#0f766e', '#34d399', 'SEMEN & ADUKAN',
                <<<'ART'
                <!-- Cement Sack 40kg Semen Gresik -->
                <rect x="195" y="110" width="210" height="285" rx="22" fill="#d6c7b2" stroke="#a8947c" stroke-width="4"/>
                <rect x="195" y="148" width="210" height="210" fill="#ecfdf5"/>
                <rect x="195" y="148" width="210" height="34" fill="#047857"/>
                <polygon points="300,196 338,225 324,270 276,270 262,225" fill="#047857" stroke="#f59e0b" stroke-width="4"/>
                <text x="300" y="244" text-anchor="middle" font-family="Inter, Arial, sans-serif" font-size="18" font-weight="900" fill="#ffffff">SG</text>
                <text x="300" y="170" text-anchor="middle" font-family="Inter, Arial, sans-serif" font-size="14" font-weight="900" fill="#ffffff" letter-spacing="1.5">SEMEN GRESIK</text>
                <text x="300" y="302" text-anchor="middle" font-family="Inter, Arial, sans-serif" font-size="14" font-weight="800" fill="#064e3b">KOKOH TAK TERTANDINGI</text>
                <rect x="235" y="318" width="130" height="30" rx="8" fill="#f59e0b"/>
                <text x="300" y="339" text-anchor="middle" font-family="Inter, Arial, sans-serif" font-size="16" font-weight="900" fill="#0f172a">NETTO 40 KG</text>
ART,
            ],

            'mortar-instan-mu-301-40kg' => [
                '#451a03', '#78350f', '#fbbf24', 'SEMEN & ADUKAN',
                <<<'ART'
                <!-- Mortar Utama MU-301 Sack -->
                <rect x="195" y="110" width="210" height="285" rx="22" fill="#fde68a" stroke="#f59e0b" stroke-width="4"/>
                <rect x="195" y="148" width="210" height="75" fill="#1e293b"/>
                <text x="300" y="198" text-anchor="middle" font-family="Inter, Arial, sans-serif" font-size="38" font-weight="900" fill="#fbbf24">MU-301</text>
                <rect x="215" y="242" width="170" height="58" rx="10" fill="#d97706"/>
                <text x="300" y="268" text-anchor="middle" font-family="Inter, Arial, sans-serif" font-size="14" font-weight="800" fill="#ffffff">MORTAR INSTAN</text>
                <text x="300" y="289" text-anchor="middle" font-family="Inter, Arial, sans-serif" font-size="12" font-weight="700" fill="#fef3c7">PASANGAN &amp; PLESTER</text>
                <text x="300" y="352" text-anchor="middle" font-family="Inter, Arial, sans-serif" font-size="22" font-weight="900" fill="#1e293b">40 KG</text>
ART,
            ],

            'semen-instan-perekat-keramik-25kg' => [
                '#4c0519', '#881337', '#fb7185', 'SEMEN & ADUKAN',
                <<<'ART'
                <!-- Tile Adhesive 25kg Sack + Ceramic Grid -->
                <rect x="200" y="115" width="200" height="275" rx="20" fill="#f1f5f9" stroke="#cbd5e1" stroke-width="4"/>
                <rect x="200" y="145" width="200" height="40" fill="#e11d48"/>
                <text x="300" y="171" text-anchor="middle" font-family="Inter, Arial, sans-serif" font-size="14" font-weight="900" fill="#ffffff" letter-spacing="1">PEREKAT KERAMIK</text>
                <!-- Tile Grid Graphic -->
                <g transform="translate(245, 200)" fill="#fda4af" stroke="#be123c" stroke-width="3">
                  <rect x="0" y="0" width="48" height="48" rx="6"/>
                  <rect x="58" y="0" width="48" height="48" rx="6" fill="#e11d48"/>
                  <rect x="0" y="58" width="48" height="48" rx="6" fill="#e11d48"/>
                  <rect x="58" y="58" width="48" height="48" rx="6"/>
                </g>
                <rect x="235" y="330" width="130" height="34" rx="10" fill="#0f172a"/>
                <text x="300" y="353" text-anchor="middle" font-family="Inter, Arial, sans-serif" font-size="17" font-weight="900" fill="#fb7185">25 KG</text>
ART,
            ],

            // ==================== PIPA & FITTING ====================
            'pipa-rucika-aw-3-4-inch-4m' => [
                '#0c4a6e', '#0369a1', '#38bdf8', 'PIPA & FITTING',
                <<<'ART'
                <!-- 3D PVC AW Pipes -->
                <g transform="rotate(-18 300 250)">
                  <rect x="110" y="185" width="370" height="48" rx="10" fill="#f8fafc" stroke="#cbd5e1" stroke-width="3"/>
                  <rect x="110" y="203" width="370" height="8" fill="#0284c7"/>
                  <ellipse cx="480" cy="209" rx="10" ry="24" fill="#94a3b8" stroke="#64748b" stroke-width="3"/>
                  <text x="275" y="200" font-family="Inter, Arial, sans-serif" font-size="12" font-weight="900" fill="#0284c7" letter-spacing="2">RUCIKA AW 3/4" • JIS K-6741</text>

                  <rect x="125" y="255" width="370" height="48" rx="10" fill="#e2e8f0" stroke="#94a3b8" stroke-width="3"/>
                  <rect x="125" y="273" width="370" height="8" fill="#0284c7"/>
                  <ellipse cx="495" cy="279" rx="10" ry="24" fill="#64748b" stroke="#475569" stroke-width="3"/>
                  <text x="290" y="270" font-family="Inter, Arial, sans-serif" font-size="12" font-weight="900" fill="#0369a1" letter-spacing="2">HIGH PRESSURE • 4 METER</text>
                </g>
ART,
            ],

            'pipa-rucika-d-3-inch-4m' => [
                '#1e293b', '#334155', '#f87171', 'PIPA & FITTING',
                <<<'ART'
                <!-- Wide 3-inch PVC D Drainage Pipe -->
                <g transform="rotate(-14 300 250)">
                  <rect x="115" y="195" width="360" height="105" rx="18" fill="#cbd5e1" stroke="#94a3b8" stroke-width="4"/>
                  <rect x="115" y="218" width="360" height="12" fill="#dc2626"/>
                  <ellipse cx="475" cy="247" rx="22" ry="52" fill="#334155" stroke="#94a3b8" stroke-width="5"/>
                  <text x="280" y="262" text-anchor="middle" font-family="Inter, Arial, sans-serif" font-size="18" font-weight="900" fill="#1e293b" letter-spacing="2">RUCIKA D • 3 INCH</text>
                  <text x="280" y="284" text-anchor="middle" font-family="Inter, Arial, sans-serif" font-size="12" font-weight="700" fill="#475569">SALURAN AIR BUANGAN • 4M</text>
                </g>
ART,
            ],

            'elbow-pvc-1-2-inch' => [
                '#1e3a5f', '#0f172a', '#60a5fa', 'PIPA & FITTING',
                <<<'ART'
                <!-- 90-Degree PVC Elbow Fitting -->
                <path d="M195 140 H295 V240 H395 V340 H255 Q195 340 195 280 Z" fill="#e2e8f0" stroke="#94a3b8" stroke-width="6" stroke-linejoin="round"/>
                <!-- Socket Collars -->
                <rect x="185" y="125" width="120" height="28" rx="8" fill="#f8fafc" stroke="#64748b" stroke-width="4"/>
                <rect x="380" y="230" width="28" height="120" rx="8" fill="#f8fafc" stroke="#64748b" stroke-width="4"/>
                <text x="275" y="295" text-anchor="middle" font-family="Inter, Arial, sans-serif" font-size="18" font-weight="900" fill="#0284c7">90°</text>
                <text x="275" y="318" text-anchor="middle" font-family="Inter, Arial, sans-serif" font-size="13" font-weight="800" fill="#334155">1/2 INCH</text>
ART,
            ],

            'kran-air-kuningan-1-2-inch' => [
                '#422006', '#713f12', '#facc15', 'PIPA & FITTING',
                <<<'ART'
                <!-- Brass Garden Faucet / Kran Kuningan -->
                <!-- Red T-Handle -->
                <rect x="235" y="130" width="130" height="22" rx="11" fill="#dc2626" stroke="#991b1b" stroke-width="3"/>
                <rect x="286" y="152" width="28" height="36" fill="#ca8a04"/>
                <!-- Brass Body -->
                <rect x="175" y="210" width="190" height="52" rx="14" fill="#eab308" stroke="#a16207" stroke-width="4"/>
                <rect x="270" y="185" width="60" height="40" rx="8" fill="#facc15" stroke="#a16207" stroke-width="4"/>
                <!-- Threaded Inlet Left -->
                <rect x="145" y="218" width="32" height="36" rx="4" fill="#ca8a04" stroke="#854d0e" stroke-width="3"/>
                <!-- Downward Spout Nozzle -->
                <path d="M335 262 H385 L372 340 H340 Z" fill="#eab308" stroke="#a16207" stroke-width="4"/>
                <!-- Water Droplet -->
                <path d="M356 355 C356 355 340 378 356 392 C372 378 356 355 356 355 Z" fill="#38bdf8"/>
ART,
            ],

            'lem-pipa-pvc-aica-aibon-100g' => [
                '#450a0a', '#7f1d1d', '#fbbf24', 'PIPA & FITTING',
                <<<'ART'
                <!-- Adhesive Can 100g -->
                <rect x="255" y="118" width="90" height="32" rx="8" fill="#1e293b"/>
                <rect x="215" y="150" width="170" height="230" rx="18" fill="#fbbf24" stroke="#d97706" stroke-width="5"/>
                <rect x="215" y="195" width="170" height="95" fill="#dc2626"/>
                <text x="300" y="236" text-anchor="middle" font-family="Inter, Arial, sans-serif" font-size="22" font-weight="900" fill="#ffffff">AICA-AIBON</text>
                <text x="300" y="268" text-anchor="middle" font-family="Inter, Arial, sans-serif" font-size="14" font-weight="800" fill="#fde68a">LEM PIPA PVC</text>
                <text x="300" y="340" text-anchor="middle" font-family="Inter, Arial, sans-serif" font-size="20" font-weight="900" fill="#0f172a">100 GRAM</text>
ART,
            ],

            // ==================== CAT & PELAPIS ====================
            'cat-dulux-catylac-putih-5kg' => [
                '#172554', '#1d4ed8', '#38bdf8', 'CAT & PELAPIS',
                <<<'ART'
                <!-- Dulux Catylac 5kg Bucket -->
                <path d="M185 190 Q300 95 415 190" fill="none" stroke="#94a3b8" stroke-width="6"/>
                <polygon points="190,155 410,155 392,380 208,380" fill="#f8fafc" stroke="#cbd5e1" stroke-width="4"/>
                <rect x="182" y="138" width="236" height="24" rx="10" fill="#e2e8f0" stroke="#94a3b8" stroke-width="3"/>
                <!-- Colorful Dulux Wave -->
                <path d="M202 245 Q260 200 300 240 T398 230 L394 310 L206 310 Z" fill="#1d4ed8"/>
                <path d="M204 265 Q260 230 300 260 T396 255" fill="none" stroke="#f59e0b" stroke-width="8"/>
                <path d="M205 280 Q260 245 300 275 T395 270" fill="none" stroke="#10b981" stroke-width="6"/>
                <text x="300" y="215" text-anchor="middle" font-family="Inter, Arial, sans-serif" font-size="24" font-weight="900" fill="#1e3a8a">Catylac</text>
                <text x="300" y="352" text-anchor="middle" font-family="Inter, Arial, sans-serif" font-size="16" font-weight="900" fill="#0f172a">PUTIH • 5 KG</text>
ART,
            ],

            'cat-avitex-exterior-5kg' => [
                '#064e3b', '#047857', '#fbbf24', 'CAT & PELAPIS',
                <<<'ART'
                <!-- Avitex Exterior 5kg Bucket -->
                <path d="M185 190 Q300 95 415 190" fill="none" stroke="#cbd5e1" stroke-width="6"/>
                <polygon points="190,155 410,155 392,380 208,380" fill="#ffffff" stroke="#a7f3d0" stroke-width="4"/>
                <rect x="182" y="138" width="236" height="24" rx="10" fill="#059669"/>
                <rect x="202" y="195" width="196" height="115" rx="12" fill="#047857"/>
                <circle cx="300" cy="238" r="24" fill="#fbbf24"/>
                <text x="300" y="292" text-anchor="middle" font-family="Inter, Arial, sans-serif" font-size="22" font-weight="900" fill="#ffffff">AVITEX</text>
                <text x="300" y="352" text-anchor="middle" font-family="Inter, Arial, sans-serif" font-size="15" font-weight="900" fill="#064e3b">EXTERIOR • 5 KG</text>
ART,
            ],

            'cat-kayu-besi-kuas-nippon-1kg' => [
                '#450a0a', '#991b1b', '#f59e0b', 'CAT & PELAPIS',
                <<<'ART'
                <!-- Nippon 1kg Enamel Tin + Paintbrush -->
                <rect x="165" y="165" width="175" height="210" rx="16" fill="#dc2626" stroke="#fde68a" stroke-width="4"/>
                <ellipse cx="252" cy="165" rx="87" ry="16" fill="#e2e8f0" stroke="#94a3b8" stroke-width="3"/>
                <rect x="185" y="215" width="135" height="90" rx="10" fill="#fef3c7"/>
                <text x="252" y="252" text-anchor="middle" font-family="Inter, Arial, sans-serif" font-size="20" font-weight="900" fill="#991b1b">NIPPON</text>
                <text x="252" y="280" text-anchor="middle" font-family="Inter, Arial, sans-serif" font-size="13" font-weight="800" fill="#78350f">KAYU &amp; BESI • 1KG</text>
                <!-- Angled Paintbrush -->
                <g transform="rotate(22 415 250)">
                  <rect x="385" y="120" width="48" height="72" rx="4" fill="#fde68a" stroke="#d97706" stroke-width="3"/>
                  <rect x="385" y="120" width="48" height="24" fill="#f59e0b"/>
                  <rect x="383" y="192" width="52" height="22" fill="#cbd5e1" stroke="#64748b" stroke-width="2"/>
                  <path d="M392 214 H426 L418 345 Q409 362 400 345 Z" fill="#b45309" stroke="#78350f" stroke-width="3"/>
                </g>
ART,
            ],

            // ==================== PERKAKAS ====================
            'paku-beton-5cm-1-dus' => [
                '#1e293b', '#0f172a', '#f59e0b', 'PAKU & PERKAKAS',
                <<<'ART'
                <!-- Concrete Nails Box + Hardened Steel Nails -->
                <rect x="160" y="200" width="220" height="175" rx="16" fill="#b45309" stroke="#fbbf24" stroke-width="4"/>
                <rect x="180" y="230" width="180" height="85" rx="8" fill="#fef3c7"/>
                <text x="270" y="268" text-anchor="middle" font-family="Inter, Arial, sans-serif" font-size="20" font-weight="900" fill="#0f172a">PAKU BETON</text>
                <text x="270" y="296" text-anchor="middle" font-family="Inter, Arial, sans-serif" font-size="16" font-weight="800" fill="#b45309">5 CM • 1 DUS (1 KG)</text>
                <!-- Steel Nails Illustration -->
                <g transform="rotate(-28 415 230)">
                  <rect x="388" y="120" width="34" height="10" rx="3" fill="#e2e8f0"/>
                  <polygon points="399,130 411,130 411,310 405,335 399,310" fill="#cbd5e1" stroke="#64748b" stroke-width="2"/>
                </g>
                <g transform="rotate(18 430 250)">
                  <rect x="418" y="135" width="34" height="10" rx="3" fill="#f8fafc"/>
                  <polygon points="429,145 441,145 441,320 435,345 429,320" fill="#e2e8f0" stroke="#475569" stroke-width="2"/>
                </g>
ART,
            ],

            'palu-konde-1kg' => [
                '#1e1b4b', '#312e81', '#fbbf24', 'PERKAKAS & ALAT',
                <<<'ART'
                <!-- Ball-Peen Hammer (Palu Konde 1kg) -->
                <g transform="rotate(-32 300 255)">
                  <!-- Hickory Wooden Handle -->
                  <rect x="282" y="175" width="36" height="230" rx="14" fill="#d97706" stroke="#92400e" stroke-width="4"/>
                  <!-- Forged Steel Head + Ball Peen -->
                  <rect x="195" y="125" width="165" height="58" rx="12" fill="#94a3b8" stroke="#475569" stroke-width="4"/>
                  <circle cx="385" cy="154" r="26" fill="#94a3b8" stroke="#475569" stroke-width="4"/>
                  <rect x="180" y="132" width="22" height="44" rx="4" fill="#e2e8f0" stroke="#475569" stroke-width="3"/>
                </g>
ART,
            ],

            'meteran-roll-5m-stanley' => [
                '#422006', '#1e293b', '#facc15', 'PERKAKAS & ALAT',
                <<<'ART'
                <!-- Extended Measuring Tape Blade -->
                <rect x="310" y="305" width="205" height="36" fill="#facc15" stroke="#ca8a04" stroke-width="3"/>
                <line x1="335" y1="305" x2="335" y2="322" stroke="#0f172a" stroke-width="3"/>
                <line x1="360" y1="305" x2="360" y2="328" stroke="#0f172a" stroke-width="3"/>
                <line x1="385" y1="305" x2="385" y2="322" stroke="#0f172a" stroke-width="3"/>
                <line x1="410" y1="305" x2="410" y2="328" stroke="#0f172a" stroke-width="3"/>
                <line x1="435" y1="305" x2="435" y2="322" stroke="#0f172a" stroke-width="3"/>
                <line x1="460" y1="305" x2="460" y2="328" stroke="#0f172a" stroke-width="3"/>
                <line x1="485" y1="305" x2="485" y2="322" stroke="#0f172a" stroke-width="3"/>
                <rect x="510" y="298" width="12" height="52" fill="#94a3b8" stroke="#475569" stroke-width="2"/>
                <!-- Stanley Yellow & Black Case -->
                <rect x="135" y="140" width="210" height="205" rx="42" fill="#facc15" stroke="#0f172a" stroke-width="8"/>
                <circle cx="240" cy="242" r="64" fill="#0f172a"/>
                <text x="240" y="236" text-anchor="middle" font-family="Inter, Arial, sans-serif" font-size="18" font-weight="900" fill="#facc15">STANLEY</text>
                <text x="240" y="266" text-anchor="middle" font-family="Inter, Arial, sans-serif" font-size="24" font-weight="900" fill="#ffffff">5M</text>
ART,
            ],

            'bor-tangan-listrik-13mm' => [
                '#0f2540', '#115e59', '#f97316', 'PERKAKAS & ALAT',
                <<<'ART'
                <!-- Electric Impact Drill 13mm -->
                <!-- Drill Bit & Chuck -->
                <rect x="430" y="203" width="95" height="10" fill="#cbd5e1" stroke="#64748b" stroke-width="2"/>
                <polygon points="375,186 430,196 430,220 375,230" fill="#475569" stroke="#1e293b" stroke-width="3"/>
                <!-- Drill Motor Housing -->
                <rect x="175" y="160" width="205" height="96" rx="22" fill="#0d9488" stroke="#134e4a" stroke-width="4"/>
                <rect x="205" y="182" width="75" height="10" rx="5" fill="#134e4a"/>
                <rect x="205" y="200" width="75" height="10" rx="5" fill="#134e4a"/>
                <rect x="205" y="218" width="75" height="10" rx="5" fill="#134e4a"/>
                <!-- Pistol Grip & Trigger -->
                <path d="M205 252 H275 L252 380 H182 Z" fill="#0d9488" stroke="#134e4a" stroke-width="4"/>
                <rect x="275" y="262" width="24" height="34" rx="6" fill="#f97316"/>
                <text x="330" y="216" text-anchor="middle" font-family="Inter, Arial, sans-serif" font-size="16" font-weight="900" fill="#ffffff">500W</text>
ART,
            ],

            // ==================== LISTRIK ====================
            'kabel-nym-2x1-5mm-50m' => [
                '#1e293b', '#0f172a', '#f59e0b', 'INSTALASI LISTRIK',
                <<<'ART'
                <!-- Coiled White NYM Cable Roll -->
                <circle cx="290" cy="250" r="125" fill="none" stroke="#f8fafc" stroke-width="26"/>
                <circle cx="290" cy="250" r="95" fill="none" stroke="#e2e8f0" stroke-width="24"/>
                <circle cx="290" cy="250" r="67" fill="none" stroke="#cbd5e1" stroke-width="20"/>
                <!-- Exposed Copper Twin Cores -->
                <rect x="380" y="135" width="90" height="22" rx="10" fill="#f8fafc" stroke="#94a3b8" stroke-width="3"/>
                <line x1="470" y1="141" x2="515" y2="132" stroke="#2563eb" stroke-width="7" stroke-linecap="round"/>
                <line x1="470" y1="151" x2="515" y2="160" stroke="#92400e" stroke-width="7" stroke-linecap="round"/>
                <circle cx="520" cy="131" r="5" fill="#f59e0b"/>
                <circle cx="520" cy="161" r="5" fill="#f59e0b"/>
                <text x="290" y="246" text-anchor="middle" font-family="Inter, Arial, sans-serif" font-size="18" font-weight="900" fill="#fbbf24">NYM 2x1.5</text>
                <text x="290" y="270" text-anchor="middle" font-family="Inter, Arial, sans-serif" font-size="14" font-weight="800" fill="#f8fafc">50 METER • SNI</text>
ART,
            ],

            'saklar-tunggal-broco' => [
                '#0f2540', '#334155', '#38bdf8', 'INSTALASI LISTRIK',
                <<<'ART'
                <!-- Broco Single Wall Switch -->
                <rect x="175" y="125" width="250" height="250" rx="28" fill="#f8fafc" stroke="#cbd5e1" stroke-width="6"/>
                <rect x="215" y="165" width="170" height="170" rx="16" fill="#e2e8f0" stroke="#94a3b8" stroke-width="4"/>
                <rect x="242" y="185" width="116" height="130" rx="10" fill="#ffffff" stroke="#64748b" stroke-width="3"/>
                <line x1="252" y1="250" x2="348" y2="250" stroke="#cbd5e1" stroke-width="3"/>
                <rect x="288" y="205" width="24" height="6" rx="3" fill="#0284c7"/>
                <text x="300" y="360" text-anchor="middle" font-family="Inter, Arial, sans-serif" font-size="13" font-weight="800" fill="#64748b" letter-spacing="2">BROCO</text>
ART,
            ],

            'lampu-led-philips-12w' => [
                '#1e1b4b', '#1d4ed8', '#fde047', 'INSTALASI LISTRIK',
                <<<'ART'
                <!-- Glowing Philips LED Bulb 12W -->
                <circle cx="300" cy="215" r="130" fill="#fde047" fill-opacity="0.18"/>
                <circle cx="300" cy="200" r="82" fill="#ffffff" stroke="#e2e8f0" stroke-width="4"/>
                <polygon points="242,255 358,255 338,332 262,332" fill="#e2e8f0" stroke="#cbd5e1" stroke-width="3"/>
                <text x="300" y="300" text-anchor="middle" font-family="Inter, Arial, sans-serif" font-size="15" font-weight="900" fill="#1d4ed8">PHILIPS 12W</text>
                <!-- E27 Screw Base -->
                <rect x="268" y="332" width="64" height="44" rx="10" fill="#94a3b8" stroke="#475569" stroke-width="3"/>
                <line x1="268" y1="346" x2="332" y2="346" stroke="#475569" stroke-width="3"/>
                <line x1="268" y1="360" x2="332" y2="360" stroke="#475569" stroke-width="3"/>
                <path d="M282 376 Q300 395 318 376 Z" fill="#1e293b"/>
ART,
            ],

            // ==================== BAJA & ATAP ====================
            'baja-ringan-c75-0-75mm-6m' => [
                '#0f172a', '#1e3a5f', '#38bdf8', 'BAJA & ATAP',
                <<<'ART'
                <!-- Galvalume C75 Channel Profile -->
                <g transform="rotate(-14 300 250)">
                  <polygon points="120,220 440,160 475,250 155,310" fill="#cbd5e1" stroke="#64748b" stroke-width="4"/>
                  <polygon points="120,220 440,160 455,188 135,248" fill="#f8fafc" stroke="#64748b" stroke-width="3"/>
                  <polygon points="155,310 475,250 490,278 170,338" fill="#94a3b8" stroke="#475569" stroke-width="3"/>
                  <text x="305" y="255" text-anchor="middle" font-family="Inter, Arial, sans-serif" font-size="22" font-weight="900" fill="#0f172a" letter-spacing="2">C75 • 0.75MM</text>
                </g>
ART,
            ],

            'reng-baja-ringan-0-45mm-6m' => [
                '#1e293b', '#334155', '#60a5fa', 'BAJA & ATAP',
                <<<'ART'
                <!-- Trapezoidal Roof Batten (Reng Baja Ringan) -->
                <polygon points="130,305 180,305 225,185 375,185 420,305 470,305 470,328 402,328 360,215 240,215 198,328 130,328" fill="#e2e8f0" stroke="#64748b" stroke-width="5" stroke-linejoin="round"/>
                <text x="300" y="275" text-anchor="middle" font-family="Inter, Arial, sans-serif" font-size="20" font-weight="900" fill="#38bdf8" letter-spacing="1.5">RENG 0.45MM • 6M</text>
ART,
            ],

            'genteng-metal-pasir-0-30mm-1m' => [
                '#450a0a', '#7f1d1d', '#f59e0b', 'BAJA & ATAP',
                <<<'ART'
                <!-- Corrugated Stone-Coated Metal Roof Tile -->
                <path d="M125 200 Q170 150 215 200 T305 200 T395 200 T485 200 L465 345 Q420 295 375 345 T285 345 T195 345 T105 345 Z" fill="#b91c1c" stroke="#fca5a5" stroke-width="5"/>
                <path d="M115 268 Q160 218 205 268 T295 268 T385 268 T475 268" fill="none" stroke="#7f1d1d" stroke-width="7"/>
                <text x="300" y="250" text-anchor="middle" font-family="Inter, Arial, sans-serif" font-size="18" font-weight="900" fill="#fef2f2" letter-spacing="2">METAL PASIR 0.30MM</text>
ART,
            ],

            'besi-beton-polos-8mm-12m' => [
                '#090d16', '#1e293b', '#f59e0b', 'BAJA & ATAP',
                <<<'ART'
                <!-- Bundle of Steel Rebar Rods -->
                <g transform="rotate(-16 300 250)">
                  <rect x="115" y="195" width="360" height="24" rx="12" fill="#94a3b8" stroke="#475569" stroke-width="3"/>
                  <rect x="125" y="228" width="360" height="24" rx="12" fill="#cbd5e1" stroke="#475569" stroke-width="3"/>
                  <rect x="110" y="261" width="360" height="24" rx="12" fill="#64748b" stroke="#334155" stroke-width="3"/>
                  <rect x="130" y="294" width="360" height="24" rx="12" fill="#94a3b8" stroke="#475569" stroke-width="3"/>
                  <rect x="260" y="185" width="75" height="145" rx="10" fill="#f59e0b"/>
                  <text x="297" y="252" text-anchor="middle" font-family="Inter, Arial, sans-serif" font-size="18" font-weight="900" fill="#0f172a">SNI</text>
                  <text x="297" y="276" text-anchor="middle" font-family="Inter, Arial, sans-serif" font-size="15" font-weight="900" fill="#0f172a">Ø 8MM</text>
                </g>
ART,
            ],

            default => [
                '#0f2540', '#1e3a5f', '#f59e0b', strtoupper($categorySlug),
                '<rect x="210" y="160" width="180" height="180" rx="24" fill="#f59e0b"/>',
            ],
        };
    }
}
