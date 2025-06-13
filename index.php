<?php

$apiKey = '902FA1DC3145708BEF6A12B60803CE53';

function postTo17Track($url, $postData, $apiKey) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "17token: $apiKey",
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        return 'Curl error: ' . curl_error($ch);
    }

    curl_close($ch);

    return $response;
}

$trackinfo = [];
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $trackingNumber = $_POST['tracking_number'];
    
    //REGISTA TRACKING
    $registerUrl = 'https://api.17track.net/track/v2.2/register';
    $registerData = [
        [
            'number' => $trackingNumber
        ]
    ];
    postTo17Track($registerUrl, $registerData, $apiKey); // Não precisa exibir, só registrar

    //RECEBE TRACKING INFO
    $getUrl = 'https://api.17track.net/track/v2.2/gettrackinfo';
    $getData = [
        [
            'number' => $trackingNumber
        ]
    ];

    $getResponse = postTo17Track($getUrl, $getData, $apiKey);

    $responseArray = json_decode($getResponse, true);
    echo '<pre style="display:none">' . htmlspecialchars($getResponse) . '</pre>';
    
    if (isset($responseArray['data']['accepted'][0]['track_info']['tracking']['providers'][0]['events'])) {
        $trackinfo = $responseArray['data']['accepted'][0]['track_info']['tracking']['providers'][0]['events'];
    } else {
        $errorMessage = 'ainda sem info.';
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Tracking number</title>
    
    <style>
        body {
            font-family: 'makro_xmmedium';
            margin: 30px;
        }
        @media only screen and (max-width:769px){
            .timeline-event h3 {
                font-size: 10px!important;
            }
            .timeline-event {
                width: 100%;
                left: 0 !important;
            }
            .timeline::after {
                left: 20px;
            }
            .main-container {
                margin: 0 auto!important;
                margin-left: 0!important;
                margin-right: 0!important;
                max-width: 100%!important;
            }
            .timeline-event::after{
                top:70px!important;
            }
        }
        @font-face {
            font-family: "shapiro-55-mid-extd";
              src: url('https://cdn.shopify.com/s/files/1/0572/8760/6471/files/Shapiro_55_Middle_Extd.otf?v=1738600585') format("truetype");
          }
          @font-face {
              font-family: "shapiro-normal-text";
              src: url('https://cdn.shopify.com/s/files/1/0572/8760/6471/files/shapiro-normal-text.otf?v=1738317337') format("truetype");
          }
          @font-face {
              font-family: "shapiro-extra-ext";
              src: url('https://cdn.shopify.com/s/files/1/0572/8760/6471/files/shapiro-extra-bold--ext.otf?v=1738316560') format("truetype");
          }
          @font-face {
              font-family: "shapiro-75-bold-extd";
              src: url('https://cdn.shopify.com/s/files/1/0572/8760/6471/files/Shapiro_75_Heavy_Extd.otf?v=1739197796') format("truetype");
          }
          @font-face {
              font-family: "shapiro-65-light-extd";
              src: url('https://cdn.shopify.com/s/files/1/0572/8760/6471/files/Shapiro_65_Light_Heavy_Extd.otf?v=1738598869') format("truetype");
          }
          @font-face {
              font-family: "makro_xmregular";
              src: url('https://cdn.shopify.com/s/files/1/0572/8760/6471/files/shapiro-regular.otf?v=1666176319') format("truetype");
           }
          @font-face {
              font-family: "makro_xmmedium";
              src: url('https://cdn.shopify.com/s/files/1/0572/8760/6471/files/shapiro-regular_49318e76-2476-4342-868c-b9334aadffe2.otf?v=1666196896') format("truetype");
           }
          @font-face {
              font-family: "makro_xmbold";
              src: url('https://cdn.shopify.com/s/files/1/0572/8760/6471/files/shapiro-wide-bold_5824532a-ccec-48d5-8d3d-74cb0194addc.otf?v=1666174958') format("truetype");
           }
           @font-face {
              font-family: "shapiro-medium";
              src: url('https://cdn.shopify.com/s/files/1/0572/8760/6471/files/shapiro-medium.otf?v=1666198213') format("truetype");
           }
           @font-face {
              font-family: "shapiro-extra-bold";
              src: url('https://cdn.shopify.com/s/files/1/0572/8760/6471/files/shapiro-extra-bold.otf?v=1666687844') format("truetype");
           }
           @font-face {
              font-family: "impact_regular";
              src: url('https://cdn.shopify.com/s/files/1/0572/8760/6471/files/Impact.ttf?v=1730903734') format("truetype");
          }
        .timeline {
            position: relative;
            margin: 0 auto;
            padding: 0;
        }
        
        .timeline::after {
            content: '';
            position: absolute;
            width: 4px;
            background-color: #ddd;
            top: 0;
            bottom: 0;
            right: 0;
        }
        
        .timeline-event {
            padding: 10px 0;
            position: relative;
            background-color: #f9f9f9;
            border-radius: 6px;
            width: 100%;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .timeline-event::after {
            content: '';
            position: absolute;
            width: 15px;
            height: 15px;
            right: -10px;
            background-color: #000;
            border: 3px solid #FFF;
            top: 30px;
            border-radius: 50%;
            z-index: 1;
        }
        
        .timeline-event-left {
            left: 0;
        }
        
        .timeline-event-right {
            left: 50%;
        }
        
        .timeline-event h3 {
            margin: 0;
            font-size: 12px;
            color: #333;
            font-family: 'makro_xmbold';
            font-weight: normal;
            text-transform: uppercase;
        }
        
        .timeline-event p {
            margin: 5px 0;
            color: #555;
            font-size:12px;
        }
        .tracking-button{
            background:#000;
            color:#fff;
            border:none;
            font-family:'makro_xmbold';
            font-weight:normal!important;
            font-size:12px;
            text-transform:uppercase;
            letter-spacing:normal;
            padding:14px 60px;
            cursor:pointer;
        }
        input#tracking_number {
            border: 1px solid #000;
            padding:14px 10px;
        }
        .error {
            color: red;
        }
        .form-tracking{
            width: 100%;
            text-align: center;
            display: flex;
            flex-direction: column;
            max-width: 39%;
            margin: 0 auto;
            row-gap: 5px;
        }
        .timeline-event--content{
            padding:0 20px;
        }
        .top--container {
            display: flex;
            justify-content: center;
            padding: 15px;
            background: #000;
            color:#fff;
            border-radius: 6px;
            flex-direction: column-reverse;
        }
        form.form-tracking--test {
            display: flex;
            flex-direction: column;
            row-gap:10px;
            max-width:60%;
            margin:0 auto;
        }
        .top-map--container {
            display: flex;
            flex-direction: row-reverse;
            margin:20px 0;
        }
        
    </style>
</head>
<body>
    <div class="main-container">
        <form method="POST" style="width:100%; text-align:center" class="form-tracking--test">
            <input type="text" name="tracking_number" id="tracking_number" placeholder="Tracking" required>
            <button type="submit" class="tracking-button">track</button>
        </form>
    
        <?php if ($errorMessage): ?>
            <p class="error"><?php echo htmlspecialchars($errorMessage); ?></p>
        <?php endif; ?>
        
        <div class="top-map--container">
            
        <div class="top--container">
        <?php 
        
            $carrierLogos = [
                100001 => 'https://cdn.worldvectorlogo.com/logos/dhl-1.svg',
                170001 => 'https://cdn.worldvectorlogo.com/logos/dhl-1.svg',
                200001 => 'https://cdn.worldvectorlogo.com/logos/dhl-1.svg',
            ];
    
        
            $providerKey = null;
            $providerName = null;
            $carrierLogo = null;
            
            if (isset($responseArray['data']['accepted'][0]['track_info']['tracking']['providers'][0]['provider'])) {
                $provider = $responseArray['data']['accepted'][0]['track_info']['tracking']['providers'][0]['provider'];
                $providerKey = $provider['key'];
                $providerName = $provider['name'];
                
                if (isset($carrierLogos[$providerKey])) {
                    $carrierLogo = $carrierLogos[$providerKey];
                }
            }
    
        ?>
        <?php if ($providerName): ?>
            <div style="padding: 15px; text-align:center;">
                <?php if ($carrierLogo): ?>
                    <img src="<?php echo $carrierLogo; ?>" alt="<?php echo htmlspecialchars($providerName); ?>" style="max-height:50px; margin-bottom:10px;">
                <?php endif; ?>
                <p style="font-size:14px;"><?php echo htmlspecialchars($providerName); ?></p>
            </div>
        <?php endif; ?>
    
        <?php
        $latestStatus = null;
        if (isset($responseArray['data']['accepted'][0]['track_info']['latest_status'])) {
            $latestStatus = $responseArray['data']['accepted'][0]['track_info']['latest_status'];
        }
        
        $carrierName = null;
        if (isset($responseArray['data']['accepted'][0]['track_info']['tracking']['providers'][0]['provider']['name'])) {
            $carrierName = $responseArray['data']['accepted'][0]['track_info']['tracking']['providers'][0]['provider']['name'];
        }
        ?>
        
        <?php if ($carrierName || $latestStatus): ?>
            <div style="padding: 15px;width:20%;">
                <?php if ($latestStatus): ?>
                    <h2 style="font-family:'makro_xmbold'; text-transform:uppercase; font-size:14px; margin-top:10px;margin-bottom:0;">Status</h2>
                    <span><?php echo htmlspecialchars($latestStatus['status']); ?></span>
                    <?php if (!empty($latestStatus['sub_status_descr'])): ?>
                        <p><strong>Descrição:</strong> <?php echo htmlspecialchars($latestStatus['sub_status_descr']); ?></p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        </div>
        <?php
            $lastEvent = null;
            if (isset($responseArray['data']['accepted'][0]['track_info']['latest_event'])) {
                $lastEvent = $responseArray['data']['accepted'][0]['track_info']['latest_event'];
            }
            if ($lastEvent && isset($lastEvent['address'])) {
                $lastAddress = $lastEvent['address'];
                $mapLocation = trim($lastAddress['city'] . ', ' . $lastAddress['state'] . ', ' . $lastAddress['country']);
                $mapLocationEncoded = urlencode($mapLocation);
            }
        ?>
        <?php if (!empty($mapLocation)): ?>
            <div style="padding: 15px; background: #f0f0f0; border-radius: 6px; text-align:center; width:100%;">
                <iframe
                    width="100%"
                    height="300"
                    frameborder="0"
                    src="https://maps.google.com/maps?q=<?php echo $mapLocationEncoded; ?>&output=embed"
                    allowfullscreen>
                </iframe>
                <p style="font-size:12px; margin-top:5px;">Local: <?php echo htmlspecialchars($mapLocation); ?></p>
            </div>
        <?php endif; ?>
        </div>
        <?php if (!empty($trackinfo)): ?>
            <div class="timeline">
                <?php
                $side = true;
                foreach ($trackinfo as $event):
                    $side = !$side;
                ?>
                    <div class="timeline-event">
                        <div class="timeline-event--content">
                            <h3>
                                <?php
                                // Formatando a data
                                $dateObj = new DateTime($event['time_iso']);
                                echo $dateObj->format('M d, Y H:i');
                                ?>
                            </h3>
                            <p><strong><?php echo htmlspecialchars($event['description']); ?></strong></p>
                            <p>Local: <?php echo htmlspecialchars($event['location'] ?? 'N/A'); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>