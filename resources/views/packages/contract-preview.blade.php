<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Hizmet Sözleşmesi</title>
        <style>
            @font-face {
                font-family: 'DejaVu Sans';
                src: url('{{ public_path('fonts/DejaVuSans.ttf') }}') format('truetype');
                font-weight: normal;
            }
            @font-face {
                font-family: 'DejaVu Sans';
                src: url('{{ public_path('fonts/DejaVuSans-Bold.ttf') }}') format('truetype');
                font-weight: bold;
            }
            body {
                font-family: 'DejaVu Sans', sans-serif;
                line-height: 1.4;
                margin: 0;
                padding: 0;
                color: #333;
                font-size: 11px;
            }
            .container {
                max-width: 800px;
                margin: 0 auto;
                padding: 15px;
            }
            .header {
                text-align: center;
                padding: 15px 0;
                border-bottom: 2px solid #0066cc;
            }
            .logo-container {
                margin-bottom: 15px;
            }
            .logo-container img {
                max-width: 180px;
                height: auto;
            }
            .company-info {
                font-weight: bold;
                font-size: 12px;
                color: #333;
                margin-top: 10px;
            }
            .company-info p {
                margin: 3px 0;
                line-height: 1.3;
            }
            .contract-title {
                text-align: center;
                font-size: 18px;
                font-weight: bold;
                margin: 20px 0;
                color: #0066cc;
                text-transform: uppercase;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 15px;
                font-size: 11px;
            }
            table th, table td {
                border: 1px solid #ddd;
                padding: 6px 8px;
                text-align: left;
            }
            table th {
                background-color: #f5f5f5;
                font-weight: bold;
                color: #333;
            }
            .section-title {
                font-weight: bold;
                margin: 20px 0 10px;
                font-size: 14px;
                color: #0066cc;
                border-bottom: 1px solid #eee;
                padding-bottom: 3px;
            }
            .section-content {
                text-align: justify;
                margin-bottom: 15px;
            }
            .terms {
                margin-top: 25px;
            }
            .terms h3 {
                font-size: 14px;
                font-weight: bold;
                color: #0066cc;
                margin: 15px 0 8px;
            }
            .terms p {
                margin-bottom: 8px;
                text-align: justify;
            }
            .signature-section {
                margin-top: 40px;
                display: flex;
                justify-content: space-between;
            }
            .signature-box {
                width: 45%;
                text-align: center;
                font-size: 12px;
            }
            .signature-line {
                border-top: 1px solid #000;
                width: 180px;
                margin: 15px auto;
            }
            .footer {
                margin-top: 40px;
                text-align: center;
                font-size: 10px;
                color: #666;
                border-top: 1px solid #eee;
                padding-top: 10px;
            }
            @media print {
                .no-print { display: none; }
            }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <table>

                    <tr>
                        <th style="text-align: left;">
                            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/logo.png'))) }}" alt="Vega Asist Logo">
                        </th>
                        <th style="text-align: center;"> 
                            <p>Hizmet Sözleşmesi</p>
                            <p>MÜŞTERİ İLETİŞİM HATTI : 0850 123 45 67                            </p>
                            <p>www.vegaasist.com.tr</p>
                        </th>
                    </tr>
                </table>
            </div>


            <div class='section'>
                <h2 class='section-title'>1. VEGA Paketi</h2>
                <table>
                    <tr>
                        <th width="30%">Sözleşme Numarası</th>
                        <td>{{ $package->contract_number }}</td>
                        <td colspan="3">{{ $package->status }}</td>
                    </tr>
                    <tr>
                        <th>Tanzim / Başlangıç / Bitiş / Gün</th>
                        <td>{{ $package->payment_date->format('d.m.Y') }}</td>
                        <td>{{ $package->start_date->format('d.m.Y') }}</td>
                        <td>{{ $package->end_date->format('d.m.Y') }}</td>
                        <td>{{ $package->duration }}</td>
                    </tr>
                    <tr>
                        <th>Net Prim / KDV / Brüt Prim</th>
                        <td>{{ number_format($package->price - $package->price*0.20, 2, ',', '.') }} TL</td>
                        <td>{{ number_format($package->price*0.20, 2, ',', '.') }} TL</td>
                        <td colspan="2">{{ number_format($package->price, 2, ',', '.') }} TL</td>
                    </tr>
                </table>
            </div>

            <div class='section'>
                <h2 class='section-title'>2. Sözleşme Tarafları</h2>
                <table>
                    <tr>
                        <th width="30%">Hizmet Veren Şirket Unvanı:</th>
                        <td>Vega Asist</td>
                    </tr>
                    <tr>
                        <th>Adresi:</th>
                        <td>Mustafa Kemal Mahallesi Cumhuriyet bulvarı No:13/B SİNCAN/ANKARA</td>
                    </tr>
                    <tr>
                        <th>Hizmet Alan:</th>
                        <td>{{ $package->getCustomerNameAttribute() }}</td>
                    </tr>
                    <tr>
                        <th>TCN / VKN</th>
                        <td>{{ $package->customer->identity_number }}</td>
                    </tr>
                    <tr>
                        <th>İl:</th>
                        <td>{{ $package->plate_city }}</td>
                    </tr>
                    <tr>
                        <th>Telefon / E-posta:</th>
                        <td>{{ $package->customer->phone }} / {{ $package->customer->email }}</td>
                    </tr>
                </table>
            </div>

            <div class='section'>
                <h2 class='section-title'>3. Sözleşme Konusu Araç Bilgileri</h2>
                <table>
                    <tr>
                        <th width="30%">Marka:</th>
                        <td>{{ $package->vehicleBrand->name }}</td>
                    </tr>
                    <tr>
                        <th>Model:</th>
                        <td>{{ $package->vehicleModel->name }}</td>
                    </tr>
                    <tr>
                        <th>Plaka No / Model Yılı:</th>
                        <td>{{ $package->plate_number }} / {{ $package->model_year }}</td>
                    </tr>
                </table>
            </div>

            <div class='terms'>
                <h3>VEGA ASİST YOL YARDIM KAPSAMI</h3>
                <p><strong>1.</strong> Yol Yardım Premium Paketi; hususi kullanım otomobil, hususi kullanım (5 Koltuklu) kamyonet, Kiralık araç (rent a car), minibüs, 
                (9+1 yolcu), açık-kapalı kasa kamyonet, panelvan tipi (3500 kg aşmamış) araçları kapsar. Motosiklet, çekici, kamyon, otobüs, 
                ticari taksi, dolmuş, ambulans, iş makinası, Off-Road, traktör ve ticari amaçla kullanılan araçlar bu paketin hizmet kapsamı 
                dışındadır.</p>
                <p><strong>2.</strong> Türkiye genelinde asistans hizmeti kapsamında sunulan hizmetlere ………………… numaralı çağrı merkezi üzerinden 7/24 ulaşım 
                sağlanacaktır. Sağlanan hizmetlerin kapsam ve şartlarına www.vegaasist.com.tr den ulaşabilirsiniz.</p>
                <p><strong>3.</strong> Sözleşme, başlangıç tarihinden itibaren 14 gün içerisinde iptali talep edilmemesi durumunda (satılır ise plaka değişikliği 
                haricinde) sözleşme iptali yapılmayacaktır.</p>
                <p><strong>4.</strong> Sözleşme aracın tesciline göre trafik sigortası ile birlikte tanzim edilen araçlarda (uzun yol hariç) 7 gün; trafik sigortası ile 
                birlikte yapılmayan sonradan tek poliçelerde 15 gün bekleme süresine tabiidir. Yenileme poliçelerinde herhangi bir bekleme 
                süresi yoktur.</p>

                <h3>HİZMETLER</h3>
                <p><strong>1.</strong> Kaza veya arıza sonucu aracın hareketsiz kalması durumunda, aracın olayın gerçekleştiği yere en yakın yetkili servis veya oto 
                sanayi sitesine olay başına azami 2500 TL limit dahilinde, kaza durumunda yılda en fazla 2 (iki), arıza durumunda ise yılda 1 (bir) 
                defa olmak üzere aracın çekilmesi sağlanacaktır. Hizmet alıcısının talebi doğrultusunda aracın başka bir yerdeki servise veya 
                sanayi sitesine çekilmesi durumunda ise oluşacak fark hizmet alıcısı tarafından ödenecektir.</p>
                <p><strong>2.</strong> Aracın kaza durumunda sözleşmeye bağlı olarak; devrilme veya şarampole yuvarlanma nedeniyle hareketsiz kalması 
                durumunda, mümkün olabilen durumlarda aracın kurtarılması ve sonrasında çekilmesi veya yoluna devam etmesi için uygun bir 
                yere konulması adına verilecek olan gerekli organizasyon azami 1.500 TL limitle yılda 1 (bir) defa sağlanacaktır.</p>

                <h3>HİZMET SÖZLEŞMESİNE DAİR ÖNEMLİ HUSUSLAR</h3>
                <p><strong>1.</strong> Aracın, hizmeti alanın talebi doğrultusunda olay yerine EN YAKIN yetkili servis veya özel servise çekilmesi sağlanacak başka bir 
                oto tamir servisine çekilmesi talepleri karşılanmayacaktır. Başka bir servise götürülmek istenmesi dahilinde çıkacak fark hizmet 
                alıcısına aittir.</p>
                <p><strong>2.</strong> Hizmet alıcısı, hizmet sağlayan firmaya gerekli evrak, belge ve dokümanları (Araç ruhsatı, Sigorta Poliçesi, Ehliyet Belgesi, 
                Sözleşme örneği, olay yeri resimleri, olay yeri videoları ve tüm ilgili belgeleri) sunmakla yükümlüdür.</p>
                <p><strong>3.</strong> Çekme veya kurtarma, hizmet sağlayıcılarının güvenle ve emniyetle ulaşabileceği karayolları üzerinde verilmektedir. Aracın, 
                kapalı otoparklarda olması halinde, İçişleri bakanlığı tarafından terör bölgesi ve özel güvenlik bölgesi ilan edilen il/ilçelerde ve 
                grev, kargaşalık gibi halk hareketlerinin olması durumunda hizmet verilmeyecektir.</p>
                <p><strong>4.</strong> Aracın bagaj ve yükünden dolayı çekim yapılamaması durumunda aracın boşaltılmasından veya araç boşaltma ücretinden 
                hizmet veren firma (VEGA ASSIST) sorumlu değildir. Arızalı aracın yükü ile beraber çekimi için talep edilen ücret farkı, hizmet 
                alıcısı tarafından ödenecektir.</p>
                <p><strong>5.</strong> Arızalı veya kazalı aracın çekiciye yüklenmesi, kurtarılması ve tamirhaneye çekimi esnasında oluşacak hasarlar Çekici/Kurtarıcı 
                ve/veya hizmet alan sorumluluğundadır. Arızalı veya kazalı aracın çekiciye yüklenmesi, kurtarılması ve tamirhaneye çekimi 
                esnasında oluşacak hasarlar ve ne ad altında olursa olsun oluşabilecek / oluşan hasarlardan hiç bir şekilde VEGA ASSIST sorumlu 
                değildir.</p>
                <p><strong>6.</strong> Hizmet alan aracın hareketsiz kalması veya çalışmaması haricinde yürümesine etki etmeyen arızalar ile karlı ve yağışlı 
                havalarda yolda ilerleyemiyor olması mekanik arıza olarak değerlendirilmeyecek ve hizmet verilmeyecektir.</p>
                <p><strong>7.</strong> Bölgedeki anlaşmalı hizmet sağlayıcıların imkân ve yeterlilikleri kapsamında, fiziki, coğrafi ve iklim koşullarının elverdiği ölçüde 
                hizmet veya yol yardım organizasyonu gerçekleştirilecektir. Hizmet sağlayıcısının belirteceği nedenlerden dolayı organizasyonun 
                yapılamaması ve hizmet alıcısının kendi imkânları ile yapacağı çekim maliyetleri sözleşme limitleri dahilinde hizmet vermekle 
                yükümlü firma (VEGA ASSIST) onayı doğrultusunda hizmet alıcısına ödenecektir.</p>
                <p><strong>8.</strong> Hizmet vermekle yükümlü firma (VEGA ASSIST) tarafından, hizmet alıcısının yanlış, gerçeğe aykırı beyanında veya art niyetli 
                davranışının tespit edilmesi durumunda sözleşme tek taraflı olarak feshedilebilir.</p>
            </div>

            <div class='footer'>
                <p class='no-print'>Bu belge elektronik ortamda oluşturulmuştur.</p>
            </div>
        </div>
    </body>
</html>