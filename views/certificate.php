<?php
/**
 * Dynamic Student Certificate View
 * Loads settings from database and renders layout coordinates configured by the teacher.
 */
$award = CertificateModel::getAwardDetails($certificate['medal']);
$houseNameTh = $presenter->getHouseNameTh($certificate['house_name']);

$months = ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];
$event_time = strtotime($certificate['event_date']);
$day = date('j', $event_time);
$month = $months[date('n', $event_time) - 1];
$year = date('Y', $event_time) + 543;

// Format dynamic token replacements
$body_line_3 = str_replace(
    ['{sport_name}', '{category}', '{medal_name}', '{house_name}'],
    [htmlspecialchars($certificate['sport_name']), htmlspecialchars($certificate['category']), $award['name'], htmlspecialchars($houseNameTh)],
    $settings['body_pattern_3']
);

// Determine border and background classes
$bgStyleClass = 'bg-white text-slate-900 border-[20px] border-double';
$borderColor = $settings['border_color'] ?: '#d4af37';
$isModernTheme = false;

switch ($settings['bg_style']) {
    case 'emerald-premium':
        $bgStyleClass = 'bg-[#fcfdfa] text-slate-900 border-[20px] border-double';
        $isModernTheme = true;
        break;
    case 'royal-purple':
        $bgStyleClass = 'bg-[#faf9fc] text-slate-900 border-[20px] border-double';
        $isModernTheme = true;
        break;
    case 'minimal-blue':
        $bgStyleClass = 'bg-[#fafcfe] text-slate-900 border-[16px] border-solid';
        $isModernTheme = true;
        break;
    case 'classic-gold':
    default:
        $bgStyleClass = 'bg-white text-slate-900 border-[20px] border-double';
        $isModernTheme = false;
        break;
}

// Map selected font-style to CSS font-family and override Tailwind v4 theme variables
$fontFamily = "'Kanit', sans-serif";
switch ($settings['font_style']) {
    case 'Sarabun':
        $fontFamily = "'Sarabun', sans-serif";
        break;
    case 'Charm':
        $fontFamily = "'Charm', cursive";
        break;
    case 'Mali':
        $fontFamily = "'Mali', cursive";
        break;
    case 'Itim':
        $fontFamily = "'Itim', cursive";
        break;
    case 'Srisakdi':
        $fontFamily = "'Srisakdi', cursive";
        break;
    case 'Kanit':
    default:
        $fontFamily = "'Kanit', sans-serif";
        break;
}
$fontFamilyStyle = "font-family: $fontFamily; --font-heading: $fontFamily; --font-body: $fontFamily;";
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เกียรติบัตรรางวัล - <?= htmlspecialchars($certificate['student_name']) ?></title>
    <!-- Google Fonts for multiple choice customizer -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;700;900&family=Sarabun:wght@300;400;500;700;800&family=Charm:wght@400;700&family=Mali:wght@400;600;700&family=Itim&family=Srisakdi:wght@400;700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS v4 Browser CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- Font Awesome v6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/style.css">
    <style type="text/tailwindcss">
        @theme {
            --font-heading: 'Itim', cursive;
            --font-body: 'Mali', cursive;
        }

        /* Certificate Print configurations */
        @media print {
            body {
                background: #fff !important;
                color: #000 !important;
                background-image: none !important;
            }
            
            .no-print, .no-print-bar {
                display: none !important;
            }

            .certificate-container {
                box-shadow: none !important;
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 !important;
                aspect-ratio: 1.414 / 1 !important;
                page-break-after: avoid;
            }
        }
    </style>
</head>
<body class="text-slate-100 font-body min-h-screen p-4 sm:p-10 flex flex-col items-center justify-center relative bg-[#05070f]">

<!-- Print Action Bar -->
<div class="no-print no-print-bar w-full max-w-4xl bg-slate-900/60 backdrop-blur-xl border border-white/5 rounded-2xl p-4 flex flex-col sm:flex-row gap-4 items-center justify-between shadow-lg mb-8 animate-slide-down">
    <div class="flex items-center gap-2 text-[#d4af37] font-bold font-heading text-lg">
        <i class="fa-solid fa-file-certificate text-xl"></i>
        <span>ระบบพิมพ์เกียรติบัตรเหรียญรางวัล - SportDay</span>
    </div>
    <div class="flex gap-2 w-full sm:w-auto">
        <button onclick="window.close()" class="w-1/2 sm:w-auto bg-white/5 hover:bg-white/10 text-white border border-white/5 font-bold px-4 py-2 rounded-xl text-xs transition-colors duration-200 cursor-pointer">
            ปิดหน้าต่าง
        </button>
        <button onclick="window.print()" class="w-1/2 sm:w-auto bg-gradient-to-r from-amber-500 to-yellow-600 hover:from-amber-600 hover:to-yellow-700 text-white font-bold px-5 py-2 rounded-xl text-xs flex items-center justify-center gap-1.5 shadow-md shadow-amber-500/10 hover:shadow-amber-500/20 transition-all duration-200 cursor-pointer">
            <i class="fa-solid fa-print"></i>
            สั่งพิมพ์เกียรติบัตร
        </button>
    </div>
</div>

<!-- Landscape A4 Certificate Card -->
<div class="certificate-container w-full max-w-4xl aspect-[1.414/1] <?= $bgStyleClass ?> rounded-sm relative shadow-2xl shadow-yellow-500/5 box-border overflow-hidden select-none"
     style="border-color: <?= $borderColor ?>; <?= $fontFamilyStyle ?>">
    
    <!-- Modern Background Graphics -->
    <div class="absolute inset-0 pointer-events-none z-0">
        <!-- Radial Soft Lighting -->
        <div class="absolute inset-0" style="background: radial-gradient(circle at center, rgba(255,255,255,0.08) 0%, rgba(0,0,0,0) 85%);"></div>
        <!-- Abstract Flowing Wave Bottom Left -->
        <svg class="absolute bottom-[-10%] left-[-10%] w-[60%] h-[60%] opacity-[0.06] text-current pointer-events-none transition-colors" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 400" fill="currentColor" style="color: <?= $borderColor ?>;">
            <path d="M0,200 C100,280 200,120 300,240 C350,300 380,350 400,400 L0,400 Z"></path>
        </svg>
        <!-- Abstract Flowing Wave Top Right -->
        <svg class="absolute top-[-10%] right-[-10%] w-[60%] h-[60%] opacity-[0.06] text-current pointer-events-none rotate-180 transition-colors" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 400" fill="currentColor" style="color: <?= $borderColor ?>;">
            <path d="M0,200 C100,280 200,120 300,240 C350,300 380,350 400,400 L0,400 Z"></path>
        </svg>
        <!-- Elegant Circular Mandala Center Watermark -->
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[70%] h-[70%] opacity-[0.03] flex items-center justify-center text-current transition-colors" style="color: <?= $borderColor ?>;">
            <svg class="w-full h-full" viewBox="0 0 200 200" fill="none" stroke="currentColor" stroke-width="0.5">
                <circle cx="100" cy="100" r="80" stroke-dasharray="1 3"/>
                <circle cx="100" cy="100" r="70"/>
                <circle cx="100" cy="100" r="60" stroke-dasharray="4 2"/>
                <path d="M100,10 L100,190 M10,100 L190,100 M36.36,36.36 L163.64,163.64 M36.36,163.64 L163.64,36.36"/>
                <polygon points="100,30 120,70 170,70 130,100 150,150 100,120 50,150 70,100 30,70 80,70"/>
            </svg>
        </div>
    </div>

    <!-- Flanking School & SportDay Logos -->
    <?php if ($settings['show_logos']): ?>
        <img src="assets/logo_phichai.png" alt="School Logo" class="absolute top-[35px] left-[50px] z-30 h-[55px] w-auto object-contain pointer-events-none logo-phichai">
        <img src="assets/logo.png" alt="SportDay Logo" class="absolute top-[35px] right-[50px] z-30 h-[55px] w-auto object-contain pointer-events-none logo-sportday">
    <?php endif; ?>

    <!-- Inner Border Line -->
    <div class="absolute inset-[10px] border-2 pointer-events-none z-10" style="border-color: <?= $borderColor ?>55;"></div>
    
    <!-- Ornate Corners or Modern Minimalist Geometric Corners -->
    <div class="absolute w-8 h-8 z-20 top-[15px] left-[15px] border-t-2 border-l-2" style="border-color: <?= $borderColor ?>;"></div>
    <?php if (!$isModernTheme): ?>
        <div class="absolute w-2.5 h-2.5 z-20 top-[20px] left-[20px] border-t border-l" style="border-color: <?= $borderColor ?>88;"></div>
    <?php endif; ?>

    <div class="absolute w-8 h-8 z-20 top-[15px] right-[15px] border-t-2 border-r-2" style="border-color: <?= $borderColor ?>;"></div>
    <?php if (!$isModernTheme): ?>
        <div class="absolute w-2.5 h-2.5 z-20 top-[20px] right-[20px] border-t border-r" style="border-color: <?= $borderColor ?>88;"></div>
    <?php endif; ?>

    <div class="absolute w-8 h-8 z-20 bottom-[15px] left-[15px] border-b-2 border-l-2" style="border-color: <?= $borderColor ?>;"></div>
    <?php if (!$isModernTheme): ?>
        <div class="absolute w-2.5 h-2.5 z-20 bottom-[20px] left-[20px] border-b border-l" style="border-color: <?= $borderColor ?>88;"></div>
    <?php endif; ?>

    <div class="absolute w-8 h-8 z-20 bottom-[15px] right-[15px] border-b-2 border-r-2" style="border-color: <?= $borderColor ?>;"></div>
    <?php if (!$isModernTheme): ?>
        <div class="absolute w-2.5 h-2.5 z-20 bottom-[20px] right-[20px] border-b border-r" style="border-color: <?= $borderColor ?>88;"></div>
    <?php endif; ?>

    <!-- 1. Header Text -->
    <div class="absolute left-0 right-0 text-center select-none font-heading uppercase" 
         style="top: <?= $layout['header_text']['top'] ?>%; font-size: <?= $layout['header_text']['fontSize'] ?>px; color: <?= $layout['header_text']['color'] ?>; font-weight: <?= $layout['header_text']['fontWeight'] === 'black' ? 900 : ($layout['header_text']['fontWeight'] === 'bold' ? 700 : 500) ?>;">
        <?= htmlspecialchars($settings['header_title']) ?>
    </div>

    <!-- 2. Main Title -->
    <div class="absolute left-0 right-0 text-center select-none font-heading tracking-wider uppercase" 
         style="top: <?= $layout['main_title']['top'] ?>%; font-size: <?= $layout['main_title']['fontSize'] ?>px; color: <?= $layout['main_title']['color'] ?>; font-weight: <?= $layout['main_title']['fontWeight'] === 'black' ? 900 : 700 ?>;">
        <?= htmlspecialchars($settings['cert_title']) ?>
    </div>

    <!-- 3. Prefix Text -->
    <div class="absolute left-0 right-0 text-center select-none text-slate-500 uppercase font-semibold" 
         style="top: <?= $layout['prefix_text']['top'] ?>%; font-size: <?= $layout['prefix_text']['fontSize'] ?>px; color: <?= $layout['prefix_text']['color'] ?>;">
        <?= htmlspecialchars($settings['body_pattern_1']) ?>
    </div>

    <!-- 4. Student Name -->
    <div class="absolute left-0 right-0 text-center select-none font-heading" 
         style="top: <?= $layout['student_name']['top'] ?>%; font-size: <?= $layout['student_name']['fontSize'] ?>px; color: <?= $layout['student_name']['color'] ?>; font-weight: 900;">
        <span class="border-b-2 border-dashed px-12 pb-1" style="border-color: <?= $borderColor ?>88;">
            <?= htmlspecialchars($certificate['student_name']) ?>
        </span>
    </div>

    <!-- 5. Body Line 1 (House name details) -->
    <div class="absolute left-0 right-0 text-center select-none leading-relaxed" 
         style="top: <?= $layout['body_line1']['top'] ?>%; font-size: <?= $layout['body_line1']['fontSize'] ?>px; color: <?= $layout['body_line1']['color'] ?>;">
        <?= htmlspecialchars($settings['body_pattern_2']) ?>
        <span class="font-black underline underline-offset-4" style="color: <?= htmlspecialchars($certificate['color_code']) ?>; decoration-color: <?= $borderColor ?>77;">
            คณะ<?= htmlspecialchars($houseNameTh) ?>
        </span>
    </div>

    <!-- 6. Medal Badge -->
    <div class="absolute left-0 right-0 text-center select-none" 
         style="top: <?= $layout['medal_badge']['top'] ?>%; font-size: <?= $layout['medal_badge']['fontSize'] ?>px;">
        <span class="inline-flex items-center gap-2 px-6 py-1.5 rounded-full font-black bg-gradient-to-r <?= $award['badge_bg'] ?> border text-[#8a6d1c] uppercase shadow-sm select-none"
              style="color: <?= $award['color'] ?>; border-color: <?= $borderColor ?>77;">
            <?= $award['emoji'] ?> <?= $award['name'] ?>
        </span>
    </div>

    <!-- 7. Body Line 2 (Sport name & Category) -->
    <div class="absolute left-0 right-0 text-center select-none leading-relaxed" 
         style="top: <?= $layout['body_line2']['top'] ?>%; font-size: <?= $layout['body_line2']['fontSize'] ?>px; color: <?= $layout['body_line2']['color'] ?>;">
        <?= $body_line_3 ?>
    </div>

    <!-- 8. Date Text -->
    <div class="absolute left-0 right-0 text-center select-none font-semibold" 
         style="top: <?= $layout['date_text']['top'] ?>%; font-size: <?= $layout['date_text']['fontSize'] ?>px; color: <?= $layout['date_text']['color'] ?>;">
        ให้ไว้ ณ วันที่ <?= $day ?> เดือน <?= $month ?> พ.ศ. <?= $year ?>
    </div>

    <!-- 9. Signatures Block -->
    <div class="absolute left-0 right-0 px-16 flex justify-between items-end font-semibold" 
         style="top: <?= $layout['signatures']['top'] ?>%; font-size: <?= $layout['signatures']['fontSize'] ?>px; color: <?= $layout['signatures']['color'] ?>;">
        <!-- Left Signature -->
        <div class="w-48 text-center shrink-0">
            <div class="w-full border-t border-slate-300 pt-2 text-slate-500 uppercase tracking-wider">
                <?= htmlspecialchars($settings['sig_left_title']) ?>
            </div>
        </div>
        
        <!-- Center spacing for Gold Seal -->
        <div class="w-24 h-24 select-none pointer-events-none"></div>

        <!-- Right Signature -->
        <div class="w-48 text-center shrink-0">
            <div class="w-full border-t border-slate-300 pt-2 text-slate-500 uppercase tracking-wider">
                <?= htmlspecialchars($settings['sig_right_title']) ?>
            </div>
        </div>
    </div>

    <!-- 10. Gold Seal -->
    <div class="absolute left-1/2 -translate-x-1/2 rounded-full bg-gradient-to-r from-amber-200 via-amber-300 to-[#d4af37] border-2 border-dashed border-amber-600 flex flex-col items-center justify-center text-[10px] font-black text-[#8a6d1c] shadow-lg shadow-yellow-500/20 -rotate-12 select-none"
         style="top: <?= $layout['seal']['top'] ?>%; width: <?= 80 * $layout['seal']['scale'] ?>px; height: <?= 80 * $layout['seal']['scale'] ?>px; transform: translateX(-50%) scale(<?= $layout['seal']['scale'] ?>);">
        <span class="text-[6px] tracking-widest text-[#8a6d1c]/80">★ ★ ★</span>
        <span class="my-0.5 text-[10px]">ชนะเลิศ</span>
        <span class="text-[7px] font-heading font-black tracking-wider">ตราประทับ</span>
        <div class="absolute bottom-[-15px] z-[-1] w-16 h-10 flex justify-between ribbon-tails">
            <div class="w-5 h-10 bg-[#d4af37] opacity-85 rotate-[20deg] -translate-x-[5px] [clip-path:polygon(0_0,100%_0,100%_100%,50%_80%,0_100%)]"></div>
            <div class="w-5 h-10 bg-[#d4af37] opacity-85 -rotate-[20deg] translate-x-[5px] [clip-path:polygon(0_0,100%_0,100%_100%,50%_80%,0_100%)]"></div>
        </div>
    </div>
</div>

<script>
    window.onload = function() {
        // Automatically trigger print popup slightly after page loads
        setTimeout(() => {
            window.print();
        }, 800);
    };
</script>

</body>
</html>
