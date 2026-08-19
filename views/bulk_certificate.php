<?php
/**
 * Bulk Certificate Print View
 * Prints all medal winning certificates in sequence (A4 Landscape) starting from the configured number.
 */
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>พิมพ์เกียรติบัตรเหรียญรางวัลทั้งหมด (<?= count($winners) ?> ใบ) - Phichai Games 2026</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600;700;800;900&family=Sarabun:wght@300;400;500;600;700;800&family=Charm:wght@400;700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS v4 Browser CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- Font Awesome v6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Kanit', sans-serif;
            background-color: #0f172a;
            color: #1e293b;
            margin: 0;
            padding: 0;
        }

        .cert-page {
            container-type: inline-size;
            width: 297mm;
            height: 210mm;
            max-width: 100%;
            aspect-ratio: 1.414 / 1;
            background-color: #ffffff;
            background-image: url('assets/เกียรติบัตรกีฬาสี2569.png');
            background-size: 100% 100%;
            background-repeat: no-repeat;
            background-position: center;
            position: relative;
            box-sizing: border-box;
            overflow: hidden;
            margin: 0 auto 30px auto;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            page-break-after: always;
            page-break-inside: avoid;
            font-family: 'Sarabun', sans-serif;
        }

        /* Dynamic Text Overlays with Sarabun font & Container Query Units (cqw) */
        .cert-no-text {
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

        .cert-name-text {
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

        .cert-award-text {
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

        .cert-sport-text {
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

        @media print {
            body {
                background: transparent !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            .no-print {
                display: none !important;
            }

            .cert-page {
                margin: 0 !important;
                box-shadow: none !important;
                width: 100vw !important;
                height: 100vh !important;
                page-break-after: always !important;
                page-break-inside: avoid !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            @page {
                size: A4 landscape;
                margin: 0;
            }
        }
    </style>
</head>
<body class="p-4 sm:p-8 flex flex-col items-center">

<!-- Floating Action Bar for Print Controls -->
<div class="no-print w-full max-w-5xl bg-slate-900/90 backdrop-blur-xl border border-white/10 rounded-2xl p-4 flex flex-col sm:flex-row gap-4 items-center justify-between shadow-2xl mb-8 sticky top-4 z-50">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-amber-500/20 border border-amber-500/30 flex items-center justify-center text-amber-400">
            <i class="fa-solid fa-award text-xl"></i>
        </div>
        <div>
            <h1 class="text-white font-bold text-base">พิมพ์เกียรติบัตรเหรียญรางวัลทั้งหมด (<?= count($winners) ?> ใบ)</h1>
            <p class="text-slate-400 text-xs">เลขที่เริ่มต้น: <span class="text-amber-400 font-bold"><?= htmlspecialchars($start_no) ?>/<?= htmlspecialchars($year) ?></span> ถึง <span class="text-amber-400 font-bold"><?= htmlspecialchars($start_no + count($winners) - 1) ?>/<?= htmlspecialchars($year) ?></span></p>
        </div>
    </div>
    <div class="flex gap-2 w-full sm:w-auto">
        <button onclick="window.close()" class="w-1/2 sm:w-auto bg-white/10 hover:bg-white/20 text-white font-bold px-4 py-2.5 rounded-xl text-xs transition-colors cursor-pointer">
            <i class="fa-solid fa-xmark mr-1"></i> ปิดหน้านี้
        </button>
        <button onclick="window.print()" class="w-1/2 sm:w-auto bg-gradient-to-r from-amber-500 via-orange-500 to-amber-600 hover:from-amber-600 hover:to-orange-600 text-white font-bold px-6 py-2.5 rounded-xl text-xs flex items-center justify-center gap-2 shadow-lg shadow-orange-500/20 transition-all cursor-pointer">
            <i class="fa-solid fa-print text-sm"></i> สั่งพิมพ์เกียรติบัตรทั้งหมด
        </button>
    </div>
</div>

<?php if (empty($winners)): ?>
    <div class="bg-slate-900/60 border border-white/10 rounded-2xl p-12 text-center text-slate-400 max-w-md">
        <i class="fa-solid fa-circle-exclamation text-4xl text-amber-400 mb-3 block"></i>
        <h3 class="text-white font-bold text-lg mb-1">ไม่พบข้อมูลผู้ได้รับรางวัล</h3>
        <p class="text-xs">ยังไม่มีบันทึกผลรางวัลเหรียญทอง เหรียญเงิน หรือเหรียญทองแดงในระบบ</p>
    </div>
<?php else: ?>
    <?php foreach ($winners as $cert): ?>
        <div class="cert-page">
            
            <!-- 1. เลขที่เกียรติบัตร (Top Right next to "เลขที่") -->
            <div class="cert-no-text">
                <?= htmlspecialchars($cert['no']) ?>
            </div>

            <!-- 2. ชื่อ-สกุล นักเรียน (Inside Orange Brush Banner) -->
            <div class="cert-name-text">
                <?= htmlspecialchars($cert['name']) ?>
            </div>

            <!-- 3. รางวัลที่ได้รับ -->
            <div class="cert-award-text">
                <?= htmlspecialchars($cert['award']) ?>
            </div>

            <!-- 4. ชนิดกีฬาและหมวดหมู่ -->
            <div class="cert-sport-text">
                <?= htmlspecialchars($cert['sport']) ?>
            </div>

        </div>
    <?php endforeach; ?>
<?php endif; ?>

</body>
</html>
