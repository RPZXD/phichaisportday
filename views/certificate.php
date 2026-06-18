<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เกียรติบัตรรางวัล - <?= htmlspecialchars($certificate['student_name']) ?></title>
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
                font-family: 'Itim', sans-serif !important;
            }
            
            .no-print, .no-print-bar {
                display: none !important;
            }

            .certificate-container {
                border: 18px double #c5a02b !important;
                padding: 4rem !important;
                background-color: #fff !important;
                color: #000 !important;
                box-shadow: none !important;
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 !important;
                aspect-ratio: 1.414 / 1 !important;
                page-break-after: avoid;
                display: flex !important;
                flex-direction: column !important;
                align-items: center !important;
                justify-content: space-between !important;
            }
            
            .cert-name {
                color: #000 !important;
                border-bottom-color: #c5a02b !important;
            }
            
            .cert-logo {
                color: #725d19 !important;
            }
            
            .cert-title {
                color: #1e293b !important;
                border-bottom-color: rgba(197, 160, 43, 0.4) !important;
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
<div class="certificate-container w-full max-w-4xl aspect-[1.414/1] bg-white text-slate-900 border-[20px] border-double border-[#d4af37] rounded-sm p-6 sm:p-16 relative shadow-2xl shadow-yellow-500/5 flex flex-col items-center justify-between text-center box-border overflow-hidden select-none">
    
    <!-- Inner Border Line -->
    <div class="absolute inset-[10px] border-2 border-[#d4af37] pointer-events-none z-10"></div>
    
    <!-- Ornate Corners -->
    <div class="absolute w-10 h-10 border-5 border-[#d4af37] z-20 top-[20px] left-[20px] border-r-0 border-b-0"></div>
    <div class="absolute w-10 h-10 border-5 border-[#d4af37] z-20 top-[20px] right-[20px] border-l-0 border-b-0"></div>
    <div class="absolute w-10 h-10 border-5 border-[#d4af37] z-20 bottom-[20px] left-[20px] border-r-0 border-t-0"></div>
    <div class="absolute w-10 h-10 border-5 border-[#d4af37] z-20 bottom-[20px] right-[20px] border-l-0 border-t-0"></div>

    <!-- Trophy Watermark -->
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-80 h-80 opacity-[0.035] text-[#d4af37] pointer-events-none z-0">
        <i class="fa-solid fa-trophy text-[320px]"></i>
    </div>

    <!-- Content Wrapper -->
    <div class="relative z-30 w-full h-full flex flex-col items-center justify-between">
        
        <!-- Header -->
        <div class="mt-2">
            <div class="text-base sm:text-xl font-black tracking-widest text-[#8a6d1c] font-heading uppercase mb-1 flex items-center justify-center gap-1.5 cert-logo">
                🏆 การแข่งขันกีฬาสีโรงเรียน ประจำปี 2569
            </div>
            <div class="font-heading text-2xl sm:text-4xl font-black text-slate-800 tracking-wider uppercase border-b-3 border-double border-[#d4af37]/45 pb-1 w-fit mx-auto mt-2 cert-title">
                เกียรติบัตรเหรียญรางวัล
            </div>
        </div>

        <!-- Body -->
        <div class="my-4 max-w-2xl">
            <p class="text-slate-500 text-xs sm:text-sm tracking-widest font-semibold uppercase mb-2">เกียรติบัตรฉบับนี้ให้ไว้เพื่อแสดงว่า</p>
            <div class="font-heading text-3xl sm:text-5xl font-black text-slate-900 border-b-2 border-dashed border-[#d4af37]/50 w-fit mx-auto mb-4 px-6 sm:px-12 pb-1.5 cert-name">
                <?= htmlspecialchars($certificate['student_name']) ?>
            </div>
            
            <?php
                $houseNameTh = $presenter->getHouseNameTh($certificate['house_name']);

                $medalTh = $certificate['medal'];
                if ($certificate['medal'] === 'Gold') $medalTh = 'เหรียญทอง';
                elseif ($certificate['medal'] === 'Silver') $medalTh = 'เหรียญเงิน';
                elseif ($certificate['medal'] === 'Bronze') $medalTh = 'เหรียญทองแดง';
            ?>
            <p class="text-slate-600 text-sm sm:text-base leading-relaxed mb-3">
                ได้เข้าร่วมการแข่งขันและสร้างผลงานอันยอดเยี่ยมรุ่งโรจน์ในนามสังกัด
                <span class="font-black text-slate-900 underline decoration-[#d4af37]/40 underline-offset-4 cert-highlight" style="color: <?= htmlspecialchars($certificate['color_code']) ?>;">
                    คณะ<?= htmlspecialchars($houseNameTh) ?>
                </span>
            </p>

            <div class="text-slate-600 text-sm sm:text-base leading-relaxed">
                ได้รับรางวัลชนะเลิศอันดับเกียรติยศสูงสุด
                <div>
                    <span class="inline-flex items-center gap-2 px-6 py-1.5 rounded-full text-base sm:text-lg font-black bg-gradient-to-r from-amber-500/10 to-yellow-500/15 border border-[#d4af37]/45 text-[#8a6d1c] uppercase my-3 shadow-sm shadow-[#d4af37]/5 medal-badge-inline">
                        <?php 
                            if ($certificate['medal'] === 'Gold') echo '🥇 ';
                            elseif ($certificate['medal'] === 'Silver') echo '🥈 ';
                            elseif ($certificate['medal'] === 'Bronze') echo '🥉 ';
                        ?>
                        <?= htmlspecialchars($medalTh) ?>
                    </span>
                </div>
                <div>
                    ในประเภทกีฬา <span class="font-black text-slate-900 cert-highlight"><?= htmlspecialchars($certificate['sport_name']) ?></span> (หมวดหมู่: <?= htmlspecialchars($certificate['category']) ?>)
                </div>
            </div>
            
            <?php
                $months = ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];
                $event_time = strtotime($certificate['event_date']);
                $day = date('j', $event_time);
                $month = $months[date('n', $event_time) - 1];
                $year = date('Y', $event_time) + 543;
            ?>
            <p class="text-xs text-slate-500 font-semibold mt-4">
                ให้ไว้ ณ วันที่ <?= $day ?> เดือน <?= $month ?> พ.ศ. <?= $year ?>
            </p>
        </div>

        <!-- Footer Signatures & Seal -->
        <div class="flex justify-between items-end w-full px-4 sm:px-8 mt-2 cert-footer">
            
            <!-- Left Signature -->
            <div class="w-48 text-center shrink-0 signature-block">
                <div class="w-full border-t border-slate-300 pt-2 text-[11px] sm:text-xs font-semibold text-slate-500 uppercase tracking-wider signature-line">
                    ผู้อำนวยการจัดการแข่งขัน
                </div>
            </div>
            
            <!-- Gold Seal -->
            <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full bg-gradient-to-r from-amber-200 via-amber-300 to-[#d4af37] border-2 border-dashed border-amber-600 flex flex-col items-center justify-center text-[10px] font-black text-[#8a6d1c] shadow-lg shadow-yellow-500/20 relative -rotate-12 select-none gold-seal">
                <span class="text-[8px] tracking-widest text-[#8a6d1c]/80">★ ★ ★</span>
                <span class="my-0.5">ชนะเลิศ</span>
                <span class="text-[8px] font-heading font-black tracking-wider">ตราประทับ</span>
                <div class="absolute bottom-[-15px] z-[-1] w-16 h-10 flex justify-between ribbon-tails">
                    <div class="w-6 h-12 bg-[#d4af37] opacity-85 rotate-[20deg] -translate-x-[10px] -translate-y-[5px] [clip-path:polygon(0_0,100%_0,100%_100%,50%_80%,0_100%)] ribbon-tail-1"></div>
                    <div class="w-6 h-12 bg-[#d4af37] opacity-85 -rotate-[20deg] translate-x-[10px] -translate-y-[5px] [clip-path:polygon(0_0,100%_0,100%_100%,50%_80%,0_100%)] ribbon-tail-2"></div>
                </div>
            </div>

            <!-- Right Signature -->
            <div class="w-48 text-center shrink-0 signature-block">
                <div class="w-full border-t border-slate-300 pt-2 text-[11px] sm:text-xs font-semibold text-slate-500 uppercase tracking-wider signature-line">
                    ประธานสภากีฬาโรงเรียน
                </div>
            </div>
            
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
