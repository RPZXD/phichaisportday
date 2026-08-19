<?php
/**
 * Teacher Certificate Management & Canva Template Studio
 * Phichai Games 2026 Certificate Generator (Gold, Silver, Bronze)
 */
$start_no = isset($start_no) ? intval($start_no) : 4329;
$year = isset($year) ? htmlspecialchars($year) : '2569';
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบจัดทำเกียรติบัตรเหรียญรางวัล (Canva Template) - SportDay</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600;700;800;900&family=Sarabun:wght@300;400;500;600;700;800&family=Charm:wght@400;700&family=Mali:wght@400;600;700&family=Itim&display=swap" rel="stylesheet">
    <!-- Tailwind CSS v4 Browser CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- Font Awesome v6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/style.css">
    <style>
        body {
            font-family: 'Kanit', sans-serif;
        }

        .cert-preview-box {
            container-type: inline-size;
            position: relative;
            width: 100%;
            aspect-ratio: 1.414 / 1;
            background-color: #ffffff;
            background-image: url('assets/เกียรติบัตรกีฬาสี2569.png');
            background-size: 100% 100%;
            background-repeat: no-repeat;
            background-position: center;
            border-radius: 12px;
            overflow: hidden;
            user-select: none;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6);
            transition: all 0.3s ease;
            font-family: 'Sarabun', sans-serif;
        }

        /* Preview overlay styles with Sarabun font & Container Query Units (cqw) */
        .preview-no-text {
            font-family: 'Sarabun', sans-serif;
            position: absolute;
            top: 5.1%;
            left: 83.2%;
            font-size: 2.0cqw;
            font-weight: 700;
            color: #3b0764;
            letter-spacing: 0.05cqw;
            line-height: 1.4;
            white-space: nowrap;
            overflow: visible;
        }

        .preview-name-text {
            font-family: 'Sarabun', sans-serif;
            position: absolute;
            top: 36.3%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 60%;
            text-align: center;
            font-size: 3.1cqw;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: 0.05cqw;
            line-height: 1.45;
            padding: 0.3cqw 0;
            text-shadow: 0 0.15cqw 0.35cqw rgba(0, 0, 0, 0.35);
            white-space: nowrap;
            overflow: visible;
        }

        .preview-award-text {
            font-family: 'Sarabun', sans-serif;
            position: absolute;
            top: 45.6%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 85%;
            text-align: center;
            font-size: 2.15cqw;
            font-weight: 700;
            color: #3b0764;
            letter-spacing: 0.02cqw;
            line-height: 1.4;
            white-space: nowrap;
            overflow: visible;
        }

        .preview-sport-text {
            font-family: 'Sarabun', sans-serif;
            position: absolute;
            top: 50.6%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 85%;
            text-align: center;
            font-size: 1.85cqw;
            font-weight: 700;
            color: #3b0764;
            letter-spacing: 0.02cqw;
            line-height: 1.4;
            white-space: nowrap;
            overflow: visible;
        }
    </style>
</head>
<body class="bg-[#05070f] text-slate-100 min-h-screen relative overflow-x-hidden">

<?php 
require_once __DIR__ . '/components/ambient_orbs.php'; 
require_once __DIR__ . '/components/header.php'; 
?>

<div class="max-w-7xl mx-auto px-4 py-8">
    
    <!-- Top Hero Banner: Canva Studio Actions -->
    <div class="bg-gradient-to-r from-slate-900/90 via-purple-950/40 to-slate-900/90 backdrop-blur-xl border border-purple-500/20 rounded-[28px] p-6 sm:p-8 shadow-2xl mb-8">
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-amber-500 via-orange-500 to-purple-600 p-0.5 shadow-lg shadow-orange-500/20 shrink-0">
                    <div class="w-full h-full bg-slate-950 rounded-[14px] flex items-center justify-center text-amber-400">
                        <i class="fa-solid fa-file-certificate text-2xl"></i>
                    </div>
                </div>
                <div>
                    <div class="flex items-center gap-2 mb-1 flex-wrap">
                        <h1 class="text-xl sm:text-2xl font-black text-white">ระบบเกียรติบัตรเหรียญรางวัล (Canva Template)</h1>
                        <span class="bg-gradient-to-r from-purple-500/20 to-pink-500/20 border border-purple-500/30 text-purple-300 text-[11px] font-bold px-3 py-0.5 rounded-full uppercase tracking-wider">
                            Phichai Games 2026
                        </span>
                    </div>
                    <p class="text-slate-400 text-xs sm:text-sm">
                        รายชื่อนักกีฬาผู้ชนะเหรียญทอง เหรียญเงิน และเหรียญทองแดง พร้อมรันเลขที่อัตโนมัติเริ่มต้นที่ <span class="text-amber-400 font-bold"><?= $start_no ?>/<?= $year ?></span>
                    </p>
                </div>
            </div>

            <!-- Quick Action Buttons -->
            <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
                <!-- Canva Template Link -->
                <a href="https://www.canva.com/design/DAHPa1EUDlE/B3iowDPmf7wshsalTF7g-A/edit" target="_blank" rel="noopener noreferrer" class="flex-1 sm:flex-initial bg-gradient-to-r from-sky-500/10 via-purple-500/15 to-pink-500/10 hover:from-sky-500/20 hover:to-pink-500/20 border border-purple-400/30 hover:border-purple-400/60 text-purple-200 font-bold px-4 py-3 rounded-2xl text-xs flex items-center justify-center gap-2 transition-all shadow-md group">
                    <i class="fa-solid fa-palette text-purple-400 group-hover:scale-110 transition-transform"></i>
                    <span>เปิดเทมเพลตใน Canva</span>
                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px] opacity-70"></i>
                </a>

                <!-- Export CSV for Canva Bulk Create -->
                <a href="index.php?route=teacher_certificate&action=export_canva_csv&start_no=<?= $start_no ?>&year=<?= $year ?>" class="flex-1 sm:flex-initial bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-bold px-5 py-3 rounded-2xl text-xs flex items-center justify-center gap-2 transition-all shadow-lg shadow-emerald-500/20 cursor-pointer">
                    <i class="fa-solid fa-file-csv text-sm"></i>
                    <span>ส่งออก CSV (Canva Bulk Create)</span>
                </a>

                <!-- Bulk Print All Certificates -->
                <a href="index.php?route=teacher_certificate&action=bulk_print&start_no=<?= $start_no ?>&year=<?= $year ?>" target="_blank" class="flex-1 sm:flex-initial bg-gradient-to-r from-amber-500 via-orange-500 to-amber-600 hover:from-amber-600 hover:to-orange-600 text-white font-bold px-5 py-3 rounded-2xl text-xs flex items-center justify-center gap-2 transition-all shadow-lg shadow-orange-500/20 cursor-pointer">
                    <i class="fa-solid fa-print text-sm"></i>
                    <span>พิมพ์เกียรติบัตรทั้งหมด (<?= count($winners) ?> ใบ)</span>
                </a>
            </div>
        </div>

        <!-- Number Configuration Form Bar -->
        <div class="mt-6 pt-5 border-t border-white/10 flex flex-col sm:flex-row items-center justify-between gap-4">
            <form action="index.php" method="GET" class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
                <input type="hidden" name="route" value="teacher_certificate">
                
                <span class="text-xs text-slate-300 font-bold flex items-center gap-1.5">
                    <i class="fa-solid fa-hashtag text-amber-400"></i> ตั้งค่าเลขที่เกียรติบัตร:
                </span>

                <div class="flex items-center gap-2 bg-slate-950/60 border border-white/10 rounded-xl px-3 py-1.5">
                    <span class="text-[11px] text-slate-400">เลขเริ่มต้น:</span>
                    <input type="number" name="start_no" id="config-start-no" value="<?= $start_no ?>" min="1" class="w-20 bg-transparent text-amber-400 font-bold text-xs outline-none border-b border-amber-500/40 focus:border-amber-400" onchange="updateLiveCertNo(this.value)">
                </div>

                <div class="flex items-center gap-2 bg-slate-950/60 border border-white/10 rounded-xl px-3 py-1.5">
                    <span class="text-[11px] text-slate-400">ปี พ.ศ.:</span>
                    <input type="text" name="year" id="config-year" value="<?= $year ?>" class="w-16 bg-transparent text-amber-400 font-bold text-xs outline-none border-b border-amber-500/40 focus:border-amber-400">
                </div>

                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-4 py-1.5 rounded-xl text-xs transition-colors cursor-pointer">
                    อัปเดตเลขที่
                </button>
            </form>

            <div class="text-xs text-slate-400 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                พบนักกีฬาเหรียญรางวัลทั้งหมด <span class="text-white font-bold"><?= count($winners) ?></span> รายการ
            </div>
        </div>
    </div>

    <!-- Main Grid: Left Controls & Right Preview -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left Panel: Canva Bulk Create Guide & Certificate Settings -->
        <div class="lg:col-span-5 flex flex-col gap-6">
            
            <!-- Canva Step-by-Step Tutorial Box -->
            <div class="bg-slate-900/60 backdrop-blur-xl border border-purple-500/20 rounded-[24px] p-6 shadow-lg">
                <h2 class="text-base font-bold flex items-center gap-2 text-white mb-3">
                    <i class="fa-solid fa-wand-magic-sparkles text-purple-400"></i>
                    วิธีนำเข้าข้อมูลทำเกียรติบัตรใน Canva
                </h2>
                
                <ol class="flex flex-col gap-3 text-xs text-slate-300">
                    <li class="flex items-start gap-2.5 p-2.5 rounded-xl bg-white/2 border border-white/5">
                        <span class="w-5 h-5 rounded-full bg-purple-500/20 text-purple-300 font-bold flex items-center justify-center shrink-0 text-[10px]">1</span>
                        <div>
                            <strong class="text-white block mb-0.5">กดปุ่ม "ส่งออก CSV (Canva Bulk Create)"</strong>
                            <span>ระบบจะดาวน์โหลดไฟล์ CSV ที่มีคอลัมน์ <code class="text-amber-300">no</code>, <code class="text-amber-300">name</code>, <code class="text-amber-300">award</code>, <code class="text-amber-300">sport</code> ครบถ้วน</span>
                        </div>
                    </li>
                    <li class="flex items-start gap-2.5 p-2.5 rounded-xl bg-white/2 border border-white/5">
                        <span class="w-5 h-5 rounded-full bg-purple-500/20 text-purple-300 font-bold flex items-center justify-center shrink-0 text-[10px]">2</span>
                        <div>
                            <strong class="text-white block mb-0.5">เปิดเทมเพลตใน Canva</strong>
                            <span>คลิกปุ่ม <span class="text-purple-300 font-semibold">"เปิดเทมเพลตใน Canva"</span> ด้านบน</span>
                        </div>
                    </li>
                    <li class="flex items-start gap-2.5 p-2.5 rounded-xl bg-white/2 border border-white/5">
                        <span class="w-5 h-5 rounded-full bg-purple-500/20 text-purple-300 font-bold flex items-center justify-center shrink-0 text-[10px]">3</span>
                        <div>
                            <strong class="text-white block mb-0.5">ใช้ฟังก์ชัน "สร้างเป็นชุด" (Bulk Create)</strong>
                            <span>ใน Canva ไปที่แถบซ้ายมือ เลือก <b>แอป (Apps)</b> &rarr; <b>สร้างเป็นชุด (Bulk Create)</b> &rarr; <b>อัปโหลดไฟล์ CSV</b></span>
                        </div>
                    </li>
                    <li class="flex items-start gap-2.5 p-2.5 rounded-xl bg-white/2 border border-white/5">
                        <span class="w-5 h-5 rounded-full bg-purple-500/20 text-purple-300 font-bold flex items-center justify-center shrink-0 text-[10px]">4</span>
                        <div>
                            <strong class="text-white block mb-0.5">เชื่อมโยงข้อมูล (Connect Data)</strong>
                            <span>คลิกขวาที่ข้อความใน Canva &rarr; เลือก <b>เชื่อมโยงข้อมูล</b>:
                                <ul class="list-disc pl-4 mt-1 space-y-0.5 text-slate-400">
                                    <li>เลขที่ &rarr; <code class="text-amber-300">{no}</code></li>
                                    <li>ชื่อนักเรียน &rarr; <code class="text-amber-300">{name}</code></li>
                                    <li>รางวัล &rarr; <code class="text-amber-300">{award}</code></li>
                                    <li>กีฬา &rarr; <code class="text-amber-300">{sport}</code></li>
                                </ul>
                            </span>
                        </div>
                    </li>
                    <li class="flex items-start gap-2.5 p-2.5 rounded-xl bg-white/2 border border-white/5">
                        <span class="w-5 h-5 rounded-full bg-purple-500/20 text-purple-300 font-bold flex items-center justify-center shrink-0 text-[10px]">5</span>
                        <div>
                            <strong class="text-white block mb-0.5">กด "สร้างงานออกแบบ" (Generate)</strong>
                            <span>Canva จะสร้างเกียรติบัตรของนักกีฬาทุกคนรวดเดียว พร้อมดาวน์โหลดเป็น PDF Print ความคมชัดสูงได้ทันที!</span>
                        </div>
                    </li>
                </ol>
            </div>

            <!-- Certificate Text Customizer Form -->
            <div class="bg-slate-900/60 backdrop-blur-xl border border-white/5 rounded-[24px] p-6 shadow-lg flex flex-col gap-5">
                <div class="flex items-center justify-between pb-3 border-b border-white/5">
                    <h2 class="text-base font-bold flex items-center gap-2 text-white">
                        <i class="fa-solid fa-pen-to-square text-indigo-400"></i>
                        ปรับแต่งข้อความเกียรติบัตร
                    </h2>
                    <span class="text-[10px] text-slate-400">Canva Preset 2569</span>
                </div>

                <form action="index.php?route=teacher_certificate&action=save_settings" method="POST" class="flex flex-col gap-4 text-xs">
                    <div>
                        <label class="text-[10px] text-slate-400 font-bold block mb-1">หัวข้อเกียรติบัตร (Header Title)</label>
                        <input type="text" name="header_title" value="<?= htmlspecialchars($settings['header_title'] ?? 'โรงเรียนพิชัย') ?>" class="w-full bg-white/3 border border-white/5 focus:border-indigo-500 focus:bg-white/5 rounded-xl py-2 px-3 text-white outline-none transition-all" oninput="document.getElementById('preview-header-text').innerText = this.value">
                    </div>

                    <div>
                        <label class="text-[10px] text-slate-400 font-bold block mb-1">ข้อความมอบเกียรติบัตร (Certificate Title)</label>
                        <input type="text" name="cert_title" value="<?= htmlspecialchars($settings['cert_title'] ?? 'ขอมอบเกียรติบัตรนี้ให้ไว้เพื่อแสดงว่า') ?>" class="w-full bg-white/3 border border-white/5 focus:border-indigo-500 focus:bg-white/5 rounded-xl py-2 px-3 text-white outline-none transition-all" oninput="document.getElementById('preview-cert-title').innerText = this.value">
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="text-[10px] text-slate-400 font-bold block mb-1">ชื่อผู้อำนวยการ (ลายเซ็น)</label>
                            <input type="text" name="sig_left_title" value="<?= htmlspecialchars($settings['sig_left_title'] ?? 'นางสาวรสสุคนธ์ วินชัยเหงา') ?>" class="w-full bg-white/3 border border-white/5 focus:border-indigo-500 focus:bg-white/5 rounded-xl py-2 px-3 text-white outline-none transition-all" oninput="document.getElementById('preview-director-name').innerText = this.value">
                        </div>
                        <div>
                            <label class="text-[10px] text-slate-400 font-bold block mb-1">ตำแหน่ง</label>
                            <input type="text" name="sig_right_title" value="<?= htmlspecialchars($settings['sig_right_title'] ?? 'ผู้อำนวยการโรงเรียนพิชัย') ?>" class="w-full bg-white/3 border border-white/5 focus:border-indigo-500 focus:bg-white/5 rounded-xl py-2 px-3 text-white outline-none transition-all" oninput="document.getElementById('preview-director-title').innerText = this.value">
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-bold py-2.5 rounded-xl text-xs flex items-center justify-center gap-1.5 shadow-lg shadow-indigo-500/10 cursor-pointer mt-2">
                        <i class="fa-solid fa-circle-check"></i>
                        บันทึกการตั้งค่า
                    </button>
                </form>
            </div>

        </div>

        <!-- Right Panel: Live Visual Preview Canvas & Athletes Table -->
        <div class="lg:col-span-7 flex flex-col gap-6">
            
            <!-- Live Preview Card header bar -->
            <div class="bg-slate-900/60 backdrop-blur-xl border border-white/5 rounded-2xl p-4 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-md">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-eye text-amber-400 animate-pulse"></i>
                    <span class="text-xs font-bold text-white">พรีวิวเกียรติบัตรสด (Real-time Canvas Preview)</span>
                </div>
                <div class="text-[11px] text-slate-400">
                    A4 Landscape • Canva 2569 Edition
                </div>
            </div>

            <!-- Simulated Canva Certificate Box with Background Image -->
            <div class="cert-preview-box" id="preview-canvas-box">
                
                <!-- 1. Certificate Running Number (Top Right next to "เลขที่") -->
                <div class="preview-no-text" id="preview-cert-no">
                    <?= !empty($winners) ? htmlspecialchars($winners[0]['no']) : htmlspecialchars($start_no . '/' . $year) ?>
                </div>

                <!-- 2. Student Name (Inside Orange Brush Banner) -->
                <div class="preview-name-text" id="preview-student-name">
                    <?= !empty($winners) ? htmlspecialchars($winners[0]['name']) : 'นาย สมศักดิ์ รักกีฬา' ?>
                </div>

                <!-- 3. Award Level -->
                <div class="preview-award-text" id="preview-award-text">
                    <?= !empty($winners) ? htmlspecialchars($winners[0]['award']) : 'รางวัลชนะเลิศ (เหรียญทอง)' ?>
                </div>

                <!-- 4. Sport Category -->
                <div class="preview-sport-text" id="preview-sport-text">
                    <?= !empty($winners) ? htmlspecialchars($winners[0]['sport']) : 'กีฬาฟุตบอล 7 คน (ชาย ม.ปลาย)' ?>
                </div>

            </div>

            <!-- List of Medal-Winning Students Table -->
            <div class="bg-slate-900/60 backdrop-blur-xl border border-white/5 rounded-[24px] p-6 shadow-lg">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4 pb-3 border-b border-white/5">
                    <div>
                        <h3 class="text-base font-bold flex items-center gap-2 text-white">
                            <i class="fa-solid fa-medal text-amber-400"></i>
                            รายชื่อนักกีฬาเหรียญรางวัล (<?= count($winners) ?> คน)
                        </h3>
                        <p class="text-[11px] text-slate-400 mt-0.5">คลิก "โหลดพรีวิว" เพื่อดูตัวอย่างใบประกาศ หรือคลิก "พิมพ์เกียรติบัตร" เพื่อพิมพ์รายบุคคล</p>
                    </div>
                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <input type="text" id="athlete-search-input" placeholder="ค้นหาชื่อ, กีฬา, สี..." class="w-full sm:w-48 bg-white/3 border border-white/5 focus:border-indigo-500 focus:bg-white/5 rounded-xl py-1.5 px-3 text-white text-xs outline-none transition-all" oninput="filterAthletes(this.value)">
                    </div>
                </div>
                
                <div class="overflow-y-auto max-h-96 pr-1">
                    <?php if (empty($winners)): ?>
                        <div class="text-center py-12 text-slate-500 text-xs">
                            <i class="fa-solid fa-trophy text-3xl text-slate-600 mb-2 block"></i>
                            ยังไม่มีบันทึกผลรางวัลเหรียญทอง เหรียญเงิน หรือเหรียญทองแดงในระบบ
                        </div>
                    <?php else: ?>
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="bg-white/2 border-b border-white/5 text-slate-400 font-bold">
                                    <th class="p-2.5 text-center w-24">เลขที่</th>
                                    <th class="p-2.5">ชื่อนักเรียนนักกีฬา</th>
                                    <th class="p-2.5">ประเภทกีฬา</th>
                                    <th class="p-2.5">ระดับรางวัล</th>
                                    <th class="p-2.5 text-center">ตัวเลือก</th>
                                </tr>
                            </thead>
                            <tbody id="athletes-list-body">
                                <?php foreach ($winners as $row): 
                                    $awardDetails = CertificateModel::getAwardDetails($row['medal']);
                                ?>
                                    <tr class="border-b border-white/[0.03] hover:bg-white/[0.02] transition-colors athlete-winner-row" data-search-name="<?= htmlspecialchars($row['name'] . ' ' . $row['house_name_th'] . ' ' . $row['sport_name'] . ' ' . $row['no']) ?>">
                                        <td class="p-2.5 text-center font-bold text-amber-400">
                                            <?= htmlspecialchars($row['no']) ?>
                                        </td>
                                        <td class="p-2.5">
                                            <strong class="text-white block"><?= htmlspecialchars($row['name']) ?></strong>
                                            <span class="text-[10px] text-slate-400">ม.<?= $row['grade_level'] ?>/<?= $row['room_number'] ?> • คณะ<?= htmlspecialchars($row['house_name_th']) ?></span>
                                        </td>
                                        <td class="p-2.5 text-slate-300">
                                            <span><?= htmlspecialchars($row['sport_name']) ?></span>
                                            <?php if (!empty($row['category'])): ?>
                                                <span class="block text-[10px] text-slate-500"><?= htmlspecialchars($row['category']) ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="p-2.5">
                                            <span class="inline-flex items-center gap-1 font-bold <?= $row['medal'] === 'Gold' ? 'text-amber-400' : ($row['medal'] === 'Silver' ? 'text-slate-300' : 'text-orange-400') ?>">
                                                <?= $awardDetails['emoji'] ?> <?= htmlspecialchars($row['award']) ?>
                                            </span>
                                        </td>
                                        <td class="p-2.5 text-center">
                                            <div class="flex items-center justify-center gap-1.5">
                                                <button type="button" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-2.5 py-1 rounded-lg text-[10px] transition-colors cursor-pointer" onclick="selectAthleteForPreview('<?= htmlspecialchars($row['no']) ?>', '<?= htmlspecialchars(addslashes($row['name'])) ?>', '<?= htmlspecialchars(addslashes($row['award'])) ?>', '<?= htmlspecialchars(addslashes($row['sport'])) ?>')">
                                                    <i class="fa-solid fa-eye mr-0.5"></i> พรีวิว
                                                </button>
                                                <a href="index.php?route=certificate&result_id=<?= $row['result_id'] ?>&student_id=<?= $row['student_id'] ?>&cert_no=<?= urlencode($row['no']) ?>" target="_blank" class="bg-amber-600 hover:bg-amber-700 text-white font-bold px-2.5 py-1 rounded-lg text-[10px] transition-colors cursor-pointer">
                                                    <i class="fa-solid fa-print mr-0.5"></i> พิมพ์
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</div>

<?php require_once __DIR__ . '/components/footer.php'; ?>

<script>
    function selectAthleteForPreview(certNo, name, award, sport) {
        document.getElementById('preview-cert-no').innerText = certNo;
        document.getElementById('preview-student-name').innerText = name;
        document.getElementById('preview-award-text').innerText = award;
        document.getElementById('preview-sport-text').innerText = sport;

        // Smooth scroll to preview canvas on mobile
        document.getElementById('preview-canvas-box').scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    function updateLiveCertNo(startVal) {
        const year = document.getElementById('config-year').value || '2569';
        document.getElementById('preview-cert-no').innerText = startVal + '/' + year;
    }

    function filterAthletes(search) {
        search = search.toLowerCase().trim();
        const rows = document.querySelectorAll('.athlete-winner-row');
        rows.forEach(row => {
            const content = row.getAttribute('data-search-name').toLowerCase();
            if (content.includes(search)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>

</body>
</html>
