<html>
    <head>
        <style>
            @page {
                size: A4;
                margin: 1.5cm;
            }
            body {
                font-family: Arial, sans-serif;
                font-size: 12px;
                line-height: 1.4;
                margin: 0;
                padding: 0;
            }
            .container {
                width: 100%;
                max-width: 800px;
                margin: 0 auto;
            }
            .header {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            .logo-container {
                text-align: left;
            }
            .logo {
                width: 200px;
                height: 60px;
            }
            .contact-info {
                text-align: right;
                font-size: 12px;
            }
            .phone-number {
                font-weight: bold;
                color: #0066cc;
                font-size: 14px;
            }
            .document-title {
                text-align: center;
                font-size: 16px;
                font-weight: bold;
                margin: 15px 0;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 15px;
            }
            table, th, td {
                border: 2px solid #000;
                text-align: left;
                padding: 5px;
            }
            .section-title {
                font-weight: bold;
                font-size: 16px;
                padding: 8px;
                margin-top: 20px;
                margin-bottom: 0;
            }
            .terms {
                font-size: 10px;
                text-align: justify;
                margin-top: 20px;
            }
            .terms h3 {
                font-size: 12px;
                font-weight: bold;
                margin-top: 15px;
                margin-bottom: 5px;
                text-align: center;
            }
            .signature-section {
                margin-top: 40px;
                display: flex;
                justify-content: space-between;
            }
            .signature-box {
                width: 45%;
                border-top: 1px solid #000;
                padding-top: 5px;
                text-align: center;
            }
            .footer {
                margin-top: 30px;
                text-align: center;
                font-size: 10px;
                border-top: 1px solid #ccc;
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
                <div class='logo-container'>
                    <svg class='logo' width='200' height='60' viewBox='0 0 200 60' xmlns='http://www.w3.org/2000/svg'>
                      <!-- Background Shape -->
                      <defs>
                        <linearGradient id='logoGradient' x1='0%' y1='0%' x2='100%' y2='100%'>
                          <stop offset='0%' style='stop-color:#0066cc;stop-opacity:1' />
                          <stop offset='100%' style='stop-color:#0052a3;stop-opacity:1' />
                        </linearGradient>
                      </defs>
                      
                      <!-- V Letter Stylized -->
                      <path d='M30 10 L45 45 L60 10' 
                            stroke='url(#logoGradient)' 
                            stroke-width='4' 
                            fill='none' 
                            stroke-linecap='round'/>
                      
                      <!-- Star Symbol (representing Vega star) -->
                      <path d='M45 5 L47 2 L49 5 L52 3 L51 6 L54 7 L51 8 L52 11 L49 9 L47 12 L45 9 L42 11 L43 8 L40 7 L43 6 L42 3 Z' 
                            fill='#0066cc'/>
                      
                      <!-- Text Elements -->
                      <text x='70' y='30' 
                            font-family='Arial, sans-serif' 
                            font-size='24' 
                            font-weight='bold' 
                            fill='#0066cc'>VEGA</text>
                      
                      <text x='70' y='45' 
                            font-family='Arial, sans-serif' 
                            font-size='14' 
                            fill='#333333'>ASSIST</text>
                      
                      <!-- Decorative Line -->
                      <line x1='65' y1='33' x2='160' y2='33' 
                            stroke='#0066cc' 
                            stroke-width='0.5'/>
                    </svg>
                </div>
                <div class='contact-info'>
                    <p>Yol Yardım Hattı</p>
                    <p class='phone-number'>0850 123 45 67</p>
                </div>
            </div>
            <p class='section-title'>1. VEGA Paketi</p>
            <table>
                <tr>
                      <th>Sözleşme Numarası</th>
                      <td> {{ $package->contract_number }}</td>
                      <td colspan=3 >{{ $package->status }}</td>
                </tr>                        
                <tr>
                      <th width='40%'>Tanzim / Başlangıç/ Bitiş / Gün </th>
                      <td>{{ $package->payment_date->format('d.m.Y') }}</td>
                      <td>{{ $package->start_date->format('d.m.Y') }}</td>
                      <td>{{ $package->end_date->format('d.m.Y') }}</td>
                      <td>{{ $package->duration }}</td>
                      
                </tr>
                  <tr>
                        <th width='30%'>Net Prim / KDV / Brüt Prim  </th>
                        <td>{{ number_format($package->price - $package->price*0.20, 2, ',', '.') }} TL</td>
                        <td>{{ number_format($package->price*0.20, 2, ',', '.') }} TL</td>
                        <td colspan=2>{{ number_format($package->price, 2, ',', '.') }} TL</td>
                        
                  </tr>
            </table>

            <p class='section-title'>2. Sözleşme Tarafları</p>
            <table>
                <tr>
                    <th width='40%'>Hizmet Veren Şirket Unvanı:</th>
                    <td> Vega Asist </td>
                </tr>
<tr>
                    <th >Adresi:</th>
                    <td> Mustafa Kemal Mahallesi Cumhuriyet bulvarı No:13/B SİNCAN/ANKARA </td>
                </tr>
                <tr>
                    <th >Hizmet Alan:</th>
                    <td>{{ $package->getCustomerNameAttribute() }}</td>
                </tr>

              
                <tr>
                    <th>TCN / VKN</th>
                    <td>{{ $package->customer->identity_number }}</td>
                </tr>
                 <tr>
                     <th>İl: </th>
                     <td>{{ $package->plate_city }}</td>
                 </tr>
                <tr>
                    <th>Telefon / E-posta:</th>
                    <td>{{ $package->customer->phone }} / {{ $package->customer->email }}</td>
                </tr>
            </table>

            <p class='section-title'>3.Sözleşme Konusu Araç Bilgileri</p>
            <table>
                 <tr>
                     <th width='30%'>Marka:</th>
                     <td>{{ $package->vehicleBrand->name }}</td>
                 </tr>
                 <tr>
                     <th width='30%'>Model:</th>
                     <td>{{ $package->vehicleModel->name }}</td>
                 </tr>
                <tr>
                    <th width='30%'>Plaka No / Model Yılı: </th>
                    <td >  {{ $package->plate_letters }} {{ $package->plate_numbers }} / {{ $package->model_year }}</td>
                </tr>
            </table>

           

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

            <div class='signature-section'>
                <div class='signature-box'>
                    <p>VEGA ASSIST</p>
                    <p>Yetkili İmza</p>
                </div>
                <div class='signature-box'>
                    <p>MÜŞTERİ</p>
                    <p>Ad Soyad: Ahmet Yılmaz</p>
                    <p>İmza</p>
                </div>
            </div>

            <div class='footer'>
                <p>VEGA ASSIST</p>
                <p>info@vegaasist.com.tr | https://vegaasist.com.tr</p>
                <p class='no-print'>Bu belge elektronik ortamda oluşturulmuştur.</p>
            </div>
        </div>
    </body>
    </html>