<?php
/**
 * Dedicated Public Certificates Finder Page
 */
$pageTitle = "ค้นหาเกียรติบัตรเหรียญรางวัล - Pichai Game 2026";
$activeRoute = "certificates";
$certificatesList = $certificatesList ?? [];
$certSports = [];
if (!empty($certificatesList)) {
    foreach ($certificatesList as $c) {
        $sName = $c['sport_name'];
        if (!in_array($sName, $certSports)) {
            $certSports[] = $sName;
        }
    }
    sort($certSports);
}
?>
<!DOCTYPE html>
<html lang="th" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <!-- Tailwind CSS v4 Browser CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- Font Awesome v6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts: Sarabun, Itim, Mali -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Itim&family=Mali:wght@300;400;500;600;700&family=Sarabun:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <style type="text/tailwindcss">
        @theme {
            --font-heading: 'Itim', cursive;
            --font-body: 'Mali', cursive;
        }

        .cert-landing-preview-box {
            container-type: inline-size;
            position: relative;
            width: 100%;
            aspect-ratio: 1.414 / 1;
            background-color: #ffffff;
            background-image: url('assets/เกียรติบัตรกีฬาสี2569.png');
            background-size: 100% 100%;
            background-repeat: no-repeat;
            background-position: center;
            border-radius: 14px;
            overflow: hidden;
            user-select: none;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7);
            font-family: 'Sarabun', sans-serif;
        }
        .cert-landing-no-text {
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
        .cert-landing-name-text {
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
        .cert-landing-award-text {
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
        .cert-landing-sport-text {
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
<body class="bg-slate-950 text-slate-100 font-body antialiased selection:bg-amber-500 selection:text-slate-950 min-h-screen flex flex-col justify-between">

    <!-- Header Navigation -->
    <?php include __DIR__ . '/components/header.php'; ?>

    <main class="flex-grow pt-24 pb-20 max-w-6xl mx-auto px-4 w-full">
        <!-- Page Title & Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
            <div>
                <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-amber-500/10 border border-amber-500/25 text-amber-300 text-xs font-bold uppercase tracking-wider mb-2.5">
                    <i class="fa-solid fa-award"></i> Phichai Games 2026 Certificates
                </div>
                <h1 class="text-3xl md:text-4xl font-black font-heading text-white flex items-center gap-3">
                    <span class="w-3 h-8 bg-gradient-to-b from-amber-400 to-orange-500 rounded-full"></span>
                    ค้นหาเกียรติบัตรเหรียญรางวัล
                </h1>
                <p class="text-slate-400 text-xs sm:text-sm mt-1.5">
                    ค้นหาเกียรติบัตรของผู้ชนะเหรียญทอง เหรียญเงิน และเหรียญทองแดง จากชื่อ-นามสกุล, ประเภทรายการกีฬา, คณะสี หรือเลขที่เกียรติบัตร
                </p>
            </div>

            <div class="flex items-center gap-2">
                <span class="px-4 py-2 rounded-xl bg-slate-900 border border-slate-800 text-xs text-slate-300 shadow-md">
                    พบทั้งหมด <strong id="landing-cert-count" class="text-amber-400 font-bold"><?= count($certificatesList) ?></strong> รายการ
                </span>
            </div>
        </div>

        <!-- Search & Filter Controls Panel -->
        <div class="bg-slate-900/90 backdrop-blur-xl border border-white/10 rounded-2xl p-5 md:p-6 shadow-2xl mb-8">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                
                <!-- 1. Text Search Input -->
                <div class="md:col-span-5 relative">
                    <label class="block text-[11px] font-bold text-slate-400 mb-1.5">
                        <i class="fa-solid fa-magnifying-glass text-amber-400 mr-1"></i> ค้นหาจากชื่อ-สกุล หรือเลขที่
                    </label>
                    <div class="relative">
                        <input type="text" id="landing-cert-search" placeholder="พิมพ์ชื่อ, นามสกุล หรือเลขที่ (เช่น 4329)..." 
                               class="w-full bg-slate-950/80 border border-white/10 focus:border-amber-500/60 focus:bg-slate-950 rounded-xl py-2.5 pl-10 pr-10 text-white text-xs outline-none transition-all placeholder:text-slate-500"
                               oninput="filterLandingCertificates()">
                        <i class="fa-solid fa-user absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500 text-xs"></i>
                        <button type="button" id="clear-search-btn" class="hidden absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-white text-xs p-1" onclick="clearLandingSearch()">
                            <i class="fa-solid fa-circle-xmark"></i>
                        </button>
                    </div>
                </div>

                <!-- 2. Sport / Event Dropdown -->
                <div class="md:col-span-4">
                    <label class="block text-[11px] font-bold text-slate-400 mb-1.5">
                        <i class="fa-solid fa-volleyball text-indigo-400 mr-1"></i> เลือกประเภทรายการ / กีฬา
                    </label>
                    <select id="landing-cert-sport" class="w-full bg-slate-950/80 border border-white/10 focus:border-indigo-500/60 rounded-xl py-2.5 px-3 text-white text-xs outline-none transition-all cursor-pointer" onchange="filterLandingCertificates()">
                        <option value="">-- ทุกประเภทรายการ / กีฬา --</option>
                        <?php foreach ($certSports as $sp): ?>
                            <option value="<?= htmlspecialchars($sp) ?>"><?= htmlspecialchars($sp) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- 3. House Filter Dropdown -->
                <div class="md:col-span-3">
                    <label class="block text-[11px] font-bold text-slate-400 mb-1.5">
                        <i class="fa-solid fa-flag text-rose-400 mr-1"></i> คณะสี
                    </label>
                    <select id="landing-cert-house" class="w-full bg-slate-950/80 border border-white/10 focus:border-rose-500/60 rounded-xl py-2.5 px-3 text-white text-xs outline-none transition-all cursor-pointer" onchange="filterLandingCertificates()">
                        <option value="">-- ทุกคณะสี --</option>
                        <option value="แสด">คณะสีแสด</option>
                        <option value="ม่วง">คณะสีม่วง</option>
                        <option value="เขียว">คณะสีเขียว</option>
                        <option value="แดง">คณะสีแดง</option>
                        <option value="น้ำเงิน">คณะสีน้ำเงิน</option>
                    </select>
                </div>

            </div>

            <!-- 4. Medal Type Quick Filter Pills -->
            <div class="mt-4 pt-4 border-t border-white/5 flex flex-wrap items-center gap-2">
                <span class="text-[11px] font-bold text-slate-400 mr-1">ระดับเหรียญ:</span>
                <button type="button" class="landing-medal-btn px-3 py-1.5 rounded-xl bg-amber-500 text-slate-950 font-bold text-xs shadow-sm transition-all cursor-pointer active" data-medal="all" onclick="selectLandingMedal(this, 'all')">
                    ทั้งหมด
                </button>
                <button type="button" class="landing-medal-btn px-3 py-1.5 rounded-xl bg-slate-950/60 hover:bg-slate-800 border border-white/5 text-amber-300 font-bold text-xs transition-all cursor-pointer" data-medal="Gold" onclick="selectLandingMedal(this, 'Gold')">
                    🥇 เหรียญทอง (ชนะเลิศ)
                </button>
                <button type="button" class="landing-medal-btn px-3 py-1.5 rounded-xl bg-slate-950/60 hover:bg-slate-800 border border-white/5 text-slate-300 font-bold text-xs transition-all cursor-pointer" data-medal="Silver" onclick="selectLandingMedal(this, 'Silver')">
                    🥈 เหรียญเงิน (รองอันดับ 1)
                </button>
                <button type="button" class="landing-medal-btn px-3 py-1.5 rounded-xl bg-slate-950/60 hover:bg-slate-800 border border-white/5 text-amber-600 font-bold text-xs transition-all cursor-pointer" data-medal="Bronze" onclick="selectLandingMedal(this, 'Bronze')">
                    🥉 เหรียญทองแดง (รองอันดับ 2)
                </button>
            </div>
        </div>

        <!-- Certificates Cards Grid -->
        <div id="landing-cert-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            <?php if (empty($certificatesList)): ?>
                <div class="col-span-full py-16 text-center text-slate-500 bg-slate-900/40 border border-slate-800 rounded-2xl">
                    <i class="fa-solid fa-award text-3xl text-slate-600 mb-2 block"></i>
                    ยังไม่มีข้อมูลเกียรติบัตรผู้ได้รับเหรียญรางวัลในระบบ
                </div>
            <?php else: ?>
                <?php foreach ($certificatesList as $cert): 
                    $medalColor = $cert['medal'] === 'Gold' ? 'from-amber-500/20 to-yellow-600/10 border-amber-500/30 text-amber-400' : ($cert['medal'] === 'Silver' ? 'from-slate-400/20 to-slate-600/10 border-slate-400/30 text-slate-300' : 'from-amber-700/20 to-orange-800/10 border-amber-600/30 text-orange-400');
                    $medalIcon = $cert['medal'] === 'Gold' ? '🥇' : ($cert['medal'] === 'Silver' ? '🥈' : '🥉');
                ?>
                    <div class="landing-cert-card bg-gradient-to-b from-slate-900/90 to-slate-950/90 border border-white/10 hover:border-amber-500/40 rounded-2xl p-5 shadow-lg hover:shadow-amber-500/5 transition-all duration-300 flex flex-col justify-between group"
                         data-name="<?= htmlspecialchars($cert['name']) ?>"
                         data-no="<?= htmlspecialchars($cert['no']) ?>"
                         data-sport="<?= htmlspecialchars($cert['sport_name']) ?>"
                         data-sport-full="<?= htmlspecialchars($cert['sport']) ?>"
                         data-house="<?= htmlspecialchars($cert['house_name_th']) ?>"
                         data-medal="<?= htmlspecialchars($cert['medal']) ?>">
                        
                        <div>
                            <!-- Top Info: Running No + Medal Pill -->
                            <div class="flex items-center justify-between gap-2 mb-3">
                                <span class="inline-flex items-center gap-1 font-bold text-xs text-amber-400 bg-amber-500/10 border border-amber-500/20 px-2.5 py-1 rounded-lg">
                                    <i class="fa-solid fa-hashtag text-[10px]"></i> <?= htmlspecialchars($cert['no']) ?>
                                </span>

                                <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-gradient-to-r <?= $medalColor ?> border">
                                    <?= $medalIcon ?> <?= htmlspecialchars($cert['medal']) ?>
                                </span>
                            </div>

                            <!-- Student Full Name -->
                            <h3 class="text-base font-bold text-white group-hover:text-amber-300 transition-colors leading-tight mb-1">
                                <?= htmlspecialchars($cert['name']) ?>
                            </h3>

                            <!-- Class & House info -->
                            <p class="text-xs text-slate-400 flex items-center gap-1.5 mb-3">
                                <span>ม.<?= $cert['grade_level'] ?>/<?= $cert['room_number'] ?></span>
                                <span>•</span>
                                <span class="inline-flex items-center gap-1" style="color: <?= $cert['color_code'] ?>;">
                                    <span class="w-2 h-2 rounded-full" style="background-color: <?= $cert['color_code'] ?>;"></span>
                                    คณะสี<?= htmlspecialchars($cert['house_name_th']) ?>
                                </span>
                            </p>

                            <!-- Sport & Category details -->
                            <div class="p-2.5 rounded-xl bg-slate-950/70 border border-white/5 text-xs text-slate-300 mb-4">
                                <div class="font-bold text-white flex items-center gap-1 mb-0.5">
                                    <i class="fa-solid fa-trophy text-amber-400 text-[10px]"></i>
                                    <?= htmlspecialchars($cert['award']) ?>
                                </div>
                                <div class="text-[11px] text-slate-400">
                                    <?= htmlspecialchars($cert['sport']) ?>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center gap-2 pt-3 border-t border-white/5">
                            <button type="button" 
                                    onclick="openLandingCertModal('<?= htmlspecialchars($cert['no']) ?>', '<?= htmlspecialchars(addslashes($cert['name'])) ?>', '<?= htmlspecialchars(addslashes($cert['award'])) ?>', '<?= htmlspecialchars(addslashes($cert['sport'])) ?>', '<?= $cert['result_id'] ?>', '<?= $cert['student_id'] ?>')"
                                    class="flex-1 bg-gradient-to-r from-indigo-500/20 to-purple-600/20 hover:from-indigo-500/30 hover:to-purple-600/30 border border-indigo-500/30 hover:border-indigo-500/50 text-indigo-200 font-bold py-2 px-3 rounded-xl text-xs flex items-center justify-center gap-1.5 transition-all cursor-pointer">
                                <i class="fa-solid fa-eye text-indigo-400"></i> ดูตัวอย่าง
                            </button>
                            
                            <a href="index.php?route=public_certificate&result_id=<?= $cert['result_id'] ?>&student_id=<?= $cert['student_id'] ?>&cert_no=<?= urlencode($cert['no']) ?>" 
                               target="_blank" 
                               class="flex-1 bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white font-bold py-2 px-3 rounded-xl text-xs flex items-center justify-center gap-1.5 shadow-md shadow-orange-500/10 transition-all cursor-pointer">
                                <i class="fa-solid fa-print"></i> พิมพ์ใบนี้
                            </a>
                        </div>

                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Empty state for search filter -->
        <div id="landing-cert-no-result" class="hidden py-16 text-center text-slate-400 bg-slate-900/40 border border-slate-800 rounded-2xl">
            <i class="fa-solid fa-magnifying-glass text-3xl text-slate-600 mb-2 block"></i>
            <h4 class="text-white font-bold text-sm mb-1">ไม่พบข้อมูลเกียรติบัตรที่ตรงกับเงื่อนไข</h4>
            <p class="text-xs text-slate-500">ลองเปลี่ยนคำค้นหา หรือเลือก "ทั้งหมด" ในตัวกรอง</p>
        </div>

        <!-- Pagination Navigation Bar (6 items per page) -->
        <div id="landing-cert-pagination" class="mt-8 flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 border-t border-white/10">
            <div class="text-xs text-slate-400">
                แสดง <span id="cert-page-range" class="text-white font-bold">1 - 6</span> จากทั้งหมด <span id="cert-page-total" class="text-amber-400 font-bold"><?= count($certificatesList) ?></span> รายการ
            </div>

            <div class="flex items-center gap-1.5 flex-wrap justify-center" id="cert-pagination-buttons">
                <!-- Dynamic page buttons rendered via JavaScript -->
            </div>
        </div>
    </main>

    <!-- Modal Popup for Certificate Preview -->
    <div id="landing-cert-modal" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md hidden items-center justify-center p-4">
        <div class="relative w-full max-w-3xl bg-slate-900/95 border border-white/10 rounded-2xl shadow-2xl overflow-hidden p-5 flex flex-col gap-4 animate-scale-up max-h-[95vh] overflow-y-auto">
            
            <!-- Modal Top Bar -->
            <div class="flex items-center justify-between pb-3 border-b border-white/10">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-amber-500/20 border border-amber-500/30 flex items-center justify-center text-amber-400">
                        <i class="fa-solid fa-certificate"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-white">ตัวอย่างเกียรติบัตร (Certificate Preview)</h3>
                        <p class="text-[11px] text-slate-400">โรงเรียนพิชัย • Phichai Games 2026</p>
                    </div>
                </div>
                <button type="button" onclick="closeLandingCertModal()" class="w-8 h-8 rounded-lg bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white flex items-center justify-center transition-colors cursor-pointer">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <!-- Simulated Canva 2569 Certificate Canvas -->
            <div class="cert-landing-preview-box" id="modal-cert-canvas">
                <div class="cert-landing-no-text" id="modal-cert-no">4329/2569</div>
                <div class="cert-landing-name-text" id="modal-cert-name">กานต์พิชชา พูลฉ่ำ</div>
                <div class="cert-landing-award-text" id="modal-cert-award">รางวัลรองชนะเลิศ อันดับ 2 (เหรียญทองแดง)</div>
                <div class="cert-landing-sport-text" id="modal-cert-sport">กีฬาฟุตบอล 7 คน หญิงรวม ม.ต้น - ม.ปลาย (Team)</div>
            </div>

            <!-- Modal Bottom Actions -->
            <div class="flex items-center justify-between gap-3 pt-2">
                <button type="button" onclick="closeLandingCertModal()" class="bg-white/5 hover:bg-white/10 text-slate-300 font-bold px-4 py-2 rounded-xl text-xs transition-colors cursor-pointer">
                    ปิดหน้าต่าง
                </button>
                <a id="modal-cert-print-link" href="#" target="_blank" class="bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white font-bold px-6 py-2 rounded-xl text-xs flex items-center gap-1.5 shadow-lg shadow-orange-500/20 transition-all cursor-pointer">
                    <i class="fa-solid fa-print"></i>
                    พิมพ์เกียรติบัตรนี้ (A4)
                </a>
            </div>

        </div>
    </div>

    <script>
        const CERT_PAGE_SIZE = 6;
        let currentCertPage = 1;
        let currentLandingMedal = 'all';

        function selectLandingMedal(btn, medal) {
            currentLandingMedal = medal;
            document.querySelectorAll('.landing-medal-btn').forEach(b => {
                b.className = 'landing-medal-btn px-3 py-1.5 rounded-xl bg-slate-950/60 hover:bg-slate-800 border border-white/5 text-slate-300 font-bold text-xs transition-all cursor-pointer';
            });
            btn.className = 'landing-medal-btn px-3 py-1.5 rounded-xl bg-amber-500 text-slate-950 font-bold text-xs shadow-sm transition-all cursor-pointer active';
            filterLandingCertificates(true);
        }

        function clearLandingSearch() {
            document.getElementById('landing-cert-search').value = '';
            document.getElementById('clear-search-btn').classList.add('hidden');
            filterLandingCertificates(true);
        }

        function changeLandingCertPage(newPage, shouldScroll = true) {
            currentCertPage = newPage;
            filterLandingCertificates(false);
            if (shouldScroll) {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        }

        function filterLandingCertificates(resetPage = true) {
            if (resetPage) {
                currentCertPage = 1;
            }

            const searchInput = document.getElementById('landing-cert-search');
            const clearBtn = document.getElementById('clear-search-btn');
            const searchVal = (searchInput ? searchInput.value : '').toLowerCase().trim();
            
            if (clearBtn) {
                if (searchVal.length > 0) {
                    clearBtn.classList.remove('hidden');
                } else {
                    clearBtn.classList.add('hidden');
                }
            }

            const sportVal = (document.getElementById('landing-cert-sport') ? document.getElementById('landing-cert-sport').value : '').toLowerCase();
            const houseVal = (document.getElementById('landing-cert-house') ? document.getElementById('landing-cert-house').value : '').toLowerCase();
            
            const allCards = Array.from(document.querySelectorAll('.landing-cert-card'));
            
            // 1. Filter matching cards
            const matchingCards = allCards.filter(card => {
                const name = (card.getAttribute('data-name') || '').toLowerCase();
                const no = (card.getAttribute('data-no') || '').toLowerCase();
                const sport = (card.getAttribute('data-sport') || '').toLowerCase();
                const sportFull = (card.getAttribute('data-sport-full') || '').toLowerCase();
                const house = (card.getAttribute('data-house') || '').toLowerCase();
                const medal = card.getAttribute('data-medal') || '';

                let matchesSearch = !searchVal || name.includes(searchVal) || no.includes(searchVal) || sport.includes(searchVal) || sportFull.includes(searchVal) || house.includes(searchVal);
                let matchesSport = !sportVal || sport.includes(sportVal) || sportFull.includes(sportVal);
                let matchesHouse = !houseVal || house.includes(houseVal);
                let matchesMedal = (currentLandingMedal === 'all') || (medal === currentLandingMedal);

                return matchesSearch && matchesSport && matchesHouse && matchesMedal;
            });

            const totalMatching = matchingCards.length;
            const totalPages = Math.ceil(totalMatching / CERT_PAGE_SIZE) || 1;

            if (currentCertPage > totalPages) {
                currentCertPage = totalPages;
            }
            if (currentCertPage < 1) {
                currentCertPage = 1;
            }

            const startIndex = (currentCertPage - 1) * CERT_PAGE_SIZE;
            const endIndex = Math.min(startIndex + CERT_PAGE_SIZE, totalMatching);

            // 2. Hide all cards first, then show only the ones for current page
            allCards.forEach(card => card.style.display = 'none');
            matchingCards.slice(startIndex, endIndex).forEach(card => {
                card.style.display = 'flex';
            });

            // 3. Update counter badges
            const countBadge = document.getElementById('landing-cert-count');
            if (countBadge) countBadge.innerText = totalMatching;

            const totalSpan = document.getElementById('cert-page-total');
            if (totalSpan) totalSpan.innerText = totalMatching;

            const rangeSpan = document.getElementById('cert-page-range');
            if (rangeSpan) {
                rangeSpan.innerText = totalMatching > 0 ? `${startIndex + 1} - ${endIndex}` : '0 - 0';
            }

            // 4. Handle No Result State
            const noResult = document.getElementById('landing-cert-no-result');
            const paginationBox = document.getElementById('landing-cert-pagination');
            if (noResult) {
                if (totalMatching === 0 && allCards.length > 0) {
                    noResult.classList.remove('hidden');
                    if (paginationBox) paginationBox.classList.add('hidden');
                } else {
                    noResult.classList.add('hidden');
                    if (paginationBox) paginationBox.classList.remove('hidden');
                }
            }

            // 5. Render Pagination Controls
            renderCertPaginationButtons(totalPages, currentCertPage);
        }

        function renderCertPaginationButtons(totalPages, currentPage) {
            const container = document.getElementById('cert-pagination-buttons');
            if (!container) return;

            if (totalPages <= 1) {
                container.innerHTML = '';
                return;
            }

            let html = '';

            // Previous Button
            html += `<button type="button" onclick="changeLandingCertPage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''} class="w-8 h-8 rounded-xl bg-slate-900 border border-white/10 hover:bg-slate-800 text-slate-300 disabled:opacity-30 disabled:pointer-events-none flex items-center justify-center text-xs transition cursor-pointer" title="หน้าก่อนหน้า"><i class="fa-solid fa-chevron-left"></i></button>`;

            // Page Numbers Logic
            let pagesToShow = [];
            if (totalPages <= 7) {
                for (let i = 1; i <= totalPages; i++) pagesToShow.push(i);
            } else {
                if (currentPage <= 3) {
                    pagesToShow = [1, 2, 3, 4, '...', totalPages];
                } else if (currentPage >= totalPages - 2) {
                    pagesToShow = [1, '...', totalPages - 3, totalPages - 2, totalPages - 1, totalPages];
                } else {
                    pagesToShow = [1, '...', currentPage - 1, currentPage, currentPage + 1, '...', totalPages];
                }
            }

            pagesToShow.forEach(p => {
                if (p === '...') {
                    html += `<span class="px-1.5 text-slate-500 text-xs select-none">...</span>`;
                } else if (p === currentPage) {
                    html += `<button type="button" class="w-8 h-8 rounded-xl bg-gradient-to-r from-amber-500 to-orange-600 text-white font-bold text-xs shadow-md shadow-orange-500/20 select-none">${p}</button>`;
                } else {
                    html += `<button type="button" onclick="changeLandingCertPage(${p})" class="w-8 h-8 rounded-xl bg-slate-950/80 border border-white/10 hover:bg-slate-800 hover:text-white text-slate-300 font-medium text-xs transition cursor-pointer">${p}</button>`;
                }
            });

            // Next Button
            html += `<button type="button" onclick="changeLandingCertPage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''} class="w-8 h-8 rounded-xl bg-slate-900 border border-white/10 hover:bg-slate-800 text-slate-300 disabled:opacity-30 disabled:pointer-events-none flex items-center justify-center text-xs transition cursor-pointer" title="หน้าถัดไป"><i class="fa-solid fa-chevron-right"></i></button>`;

            container.innerHTML = html;
        }

        // Initialize pagination on DOM ready
        document.addEventListener('DOMContentLoaded', () => {
            filterLandingCertificates(true);
        });

        function openLandingCertModal(no, name, award, sport, resultId, studentId) {
            document.getElementById('modal-cert-no').innerText = no;
            document.getElementById('modal-cert-name').innerText = name;
            document.getElementById('modal-cert-award').innerText = award;
            document.getElementById('modal-cert-sport').innerText = sport;
            document.getElementById('modal-cert-print-link').href = 'index.php?route=public_certificate&result_id=' + resultId + '&student_id=' + studentId + '&cert_no=' + encodeURIComponent(no);
            
            const modal = document.getElementById('landing-cert-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeLandingCertModal() {
            const modal = document.getElementById('landing-cert-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>

    <?php include __DIR__ . '/components/footer.php'; ?>
</body>
</html>
