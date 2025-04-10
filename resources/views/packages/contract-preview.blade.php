<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @font-face {
            font-family: 'DejaVu Sans';
            font-style: normal;
            font-weight: normal;
            src: url('{{ public_path('fonts/DejaVuSans.ttf') }}') format('truetype');
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
        }
        .contract-preview {
            padding: 20px;
            font-size: 14px;
        }
        .contract-section {
            margin-bottom: 20px;
        }
        .contract-section h5 {
            color: #333;
            margin-bottom: 15px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
        .vehicle-details {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
        }
        .text-center {
            text-align: center;
        }
        .mb-4 {
            margin-bottom: 1.5rem;
        }
        .mt-3 {
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <div class="contract-preview">
        <div class="text-center mb-4">
            <h4>VEGAASİST YOL YARDIM KAPSAMI</h4>
            <p class="text-muted">Sözleşme No: {{ $package->contract_number }}</p>
        </div>

        <div class="contract-section mb-4">
            <h5>1. KAPSAM</h5>
            <p>1. Yol Yardım Premium Paketi; hususi kullanım otomobil, hususi kullanım (5 Koltuklu) kamyonet, Kiralık araç (rent a car), minibüs, (9+1 yolcu), açık-kapalı kasa kamyonet, panelvan tipi (3500 kg aşmamış) araçları kapsar. Motosiklet, çekici, kamyon, otobüs, ticari taksi,dolmuş, ambulans, iş makinası,Off-Road, traktör ve ticari amaçla kullanılan araçlar bu paketin hizmet kapsamı dışındadır.</p>
            <p>2. Türkiye genelinde asistans hizmeti kapsamında sunulan hizmetlere ………………… numaralı çağrı merkezi üzerinden 7/24 ulaşım sağlanacaktır.Sağlanan hizmetlerin kapsam ve şartlarına www.vegaasist.com.tr den ulaşabilirsiniz.</p>
            <p>3. Sözleşme, başlangıç tarihinden itibaren 14 gün içerisinde iptali talep edilmemesi durumunda (satılır ise plaka değişikliği haricinde) sözleşme iptali yapılmayacaktır.</p>
            <p>4. Sözleşme aracın tesciline göre trafik sigortası ile birlikte tanzim edilen araçlarda (uzun yol hariç) 7 gün; trafik sigortası ile birlikte yapılmayan sonradan tek poliçelerde 15 gün bekleme süresine tabiidir.Yenileme poliçelerinde herhangi bir bekleme süresi yoktur.</p>
        </div>

        <div class="contract-section mb-4">
            <h5>2. HİZMETLER</h5>
            <p>1. Kaza veya arıza sonucu aracın hareketsiz kalması durumunda, aracın olayın gerçekleştiği yere en yakın yetkili servis veya oto sanayi sitesine olay başına azami 2500 TL limit dahilinde, kaza durumunda yılda en fazla 2 (iki), arıza durumunda ise yılda 1 (bir) defa olmak üzere aracın çekilmesi sağlanacaktır. Hizmet alıcısının talebi doğrultusunda aracın başka bir yerdeki servise veya sanayi sitesine çekilmesi durumunda ise oluşacak fark hizmet alıcısı tarafından ödenecektir.</p>
            <p>2. Aracın kaza durumunda sözleşmeye bağlı olarak; devrilme veya şarampole yuvarlanma nedeniyle hareketsiz kalması durumunda, mümkün olabilen durumlarda aracın kurtarılması ve sonrasında çekilmesi veya yoluna devam etmesi için uygun bir yere konulması adına verilecek olan gerekli organizasyon azami 1.500 TL limitle yılda 1 (bir) defa sağlanacaktır.</p>
        </div>

        <div class="contract-section mb-4">
            <h5>3. HİZMET SÖZLEŞMESİNE DAİR ÖNEMLİ HUSUSLAR</h5>
            <p>1. Aracın, hizmeti alanın talebi doğrultusunda olay yerine EN YAKIN yetkili servis veya özel servise çekilmesi sağlanacak başka bir oto tamir servisine çekilmesi talepleri karşılanmayacaktır. Başka bir servise götürülmek istenmesi dahilinde çıkacak fark hizmet alıcısına aittir.</p>
            <p>2. Hizmet alıcısı, hizmet sağlayan firmaya gerekli evrak, belge ve dokümanları (Araç ruhsatı, Sigorta Poliçesi, Ehliyet Belgesi, Sözleşme örneği, olay yeri resimleri, olay yeri videoları ve tüm ilgili belgeleri) sunmakla yükümlüdür.</p>
            <p>3. Çekme veya kurtarma, hizmet sağlayıcılarının güvenle ve emniyetle ulaşabileceği karayolları üzerinde verilmektedir. Aracın, kapalı otoparklarda olması halinde, İçişleri bakanlığı tarafından terör bölgesi ve özel güvenlik bölgesi ilan edilen il/ilçelerde ve grev, kargaşalık gibi halk hareketlerinin olması durumunda hizmet verilmeyecektir.</p>
            <p>4. Aracın bagaj ve yükünden dolayı çekim yapılamaması durumunda aracın boşaltılmasından veya araç boşaltma ücretinden hizmet veren firma (VEGA ASSIST) sorumlu değildir. Arızalı aracın yükü ile beraber çekimi için talep edilen ücret farkı, hizmet alıcısı tarafından ödenecektir.</p>
            <p>5. Arızalı veya kazalı aracın çekiciye yüklenmesi, kurtarılması ve tamirhaneye çekimi esnasında oluşacak hasarlar Çekici/Kurtarıcı ve/veya hizmet alıcısı sorumluluğundadır. Arızalı veya kazalı aracın çekiciye yüklenmesi, kurtarılması ve tamirhaneye çekimi esnasında oluşacak hasarlar ve ne ad altında olursa olsun oluşabilecek / oluşan hasarlardan hiç bir şekilde VEGA ASSIST sorumlu değildir.</p>
            <p>6. Hizmet alan aracın hareketsiz kalması veya çalışmaması haricinde yürümesine etki etmeyen arızalar ile karlı ve yağışlı havalarda yolda ilerleyemiyor olması mekanik arıza olarak değerlendirilmeyecek ve hizmet verilmeyecektir.</p>
            <p>7. Bölgedeki anlaşmalı hizmet sağlayıcıların imkân ve yeterlilikleri kapsamında, fiziki, coğrafi ve iklim koşullarının elverdiği ölçüde hizmet veya yol yardım organizasyonu gerçekleştirilecektir. Hizmet sağlayıcısının belirteceği nedenlerden dolayı organizasyonun yapılamaması ve hizmet alıcısının kendi imkânları ile yapacağı çekim maliyetleri sözleşme limitleri dahilinde hizmet vermekle yükümlü firma (VEGA ASSIST) onayı doğrultusunda hizmet alıcısına ödenecektir.</p>
            <p>8. Hizmet vermekle yükümlü firma (VEGA ASSIST) tarafından, hizmet alıcısının yanlış, gerçeğe aykırı beyanında veya art niyetli davranışının tespit edilmesi durumunda sözleşme tek taraflı olarak feshedilebilir.</p>
        </div>

        <div class="contract-section">
            <h5>4. TARAFLAR</h5>
            <p><strong>HİZMET VEREN:</strong> VEGA ASİST YARDIM ve DESTEK HİZMETLERİ</p>
            <p><strong>HİZMET ALAN:</strong> {{ $package->customer->name }}</p>
            <div class="vehicle-details mt-3">
                <h6>Araç Bilgileri:</h6>
                <p>Plaka: {{ $package->plate_city }} {{ $package->plate_letters }} {{ $package->plate_numbers }}</p>
            </div>
        </div>

        <div class="contract-section">
            <h5>5. SÖZLEŞME SÜRESİ VE BEDELİ</h5>
            <p><strong>Başlangıç Tarihi:</strong> {{ $package->start_date->format('d.m.Y') }}</p>
            <p><strong>Bitiş Tarihi:</strong> {{ $package->end_date->format('d.m.Y') }}</p>
            <p><strong>Hizmet Bedeli:</strong> {{ number_format($package->price, 2, ',', '.') }} ₺</p>
        </div>
    </div>
</body>
</html> 