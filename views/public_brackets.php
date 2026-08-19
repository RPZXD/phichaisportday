<?php
/**
 * Dedicated Public Tournament Brackets Page
 */
$pageTitle = "สายการแข่งขัน (Tournament Brackets) - Pichai Game 2026";
$activeRoute = "brackets";
$active_brackets = $active_brackets ?? [];
$matchResults = $matchResults ?? [];
$presenter = $presenter ?? new SportPresenter();
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
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Itim&family=Mali:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <style type="text/tailwindcss">
        @theme {
            --font-heading: 'Itim', cursive;
            --font-body: 'Mali', cursive;
        }

        .glass-panel {
            background: rgba(13, 17, 33, 0.45);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 font-body antialiased selection:bg-amber-500 selection:text-slate-950 min-h-screen flex flex-col justify-between">

    <!-- Header Navigation -->
    <?php include __DIR__ . '/components/header.php'; ?>

    <main class="flex-grow pt-24 pb-20 max-w-6xl mx-auto px-4 w-full">
        <!-- Page Title & Header -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-teal-500/10 border border-teal-500/25 text-teal-300 text-xs font-bold uppercase tracking-wider mb-2.5">
                <i class="fa-solid fa-sitemap"></i> Bracket Slider
            </div>
            <h1 class="text-3xl md:text-5xl font-black font-heading text-white flex items-center justify-center gap-3">
                <span class="w-3 h-8 bg-gradient-to-b from-teal-400 to-emerald-500 rounded-full"></span>
                สายการแข่งขัน (Tournament Bracket)
            </h1>
            <p class="text-slate-400 text-xs sm:text-sm mt-2 max-w-xl mx-auto">
                ผังการประกบคู่และผลการแข่งขันแบบแพ้คัดออกของแต่ละชนิดกีฬา
            </p>
        </div>

        <?php if (empty($active_brackets)): ?>
            <div class="glass-panel rounded-3xl p-16 text-center text-slate-500 font-semibold max-w-4xl mx-auto">
                <i class="fa-solid fa-folder-open text-5xl mb-4 block text-slate-700"></i>
                ยังไม่มีการจัดตารางสายการแข่งขันสำหรับกีฬาประเภทใดในขณะนี้
            </div>
        <?php else: ?>
            <!-- Filter dropdown -->
            <div class="flex justify-center mb-8 px-4">
                <div class="flex items-center gap-3 bg-slate-900/80 border border-white/10 backdrop-blur-xl px-5 py-3 rounded-2xl max-w-md w-full shadow-lg">
                    <label for="bracket-filter" class="text-xs font-bold text-slate-400 whitespace-nowrap">
                        <i class="fa-solid fa-magnifying-glass mr-1.5 text-teal-400"></i>เลือกชนิดกีฬา:
                    </label>
                    <select id="bracket-filter" class="w-full bg-transparent border-0 text-xs font-bold text-white focus:outline-none cursor-pointer">
                        <?php 
                        $slide_index = 0;
                        foreach ($active_brackets as $sport_id => $sport_data) {
                            echo '<option class="bg-slate-950 text-white" value="' . $slide_index . '">' . htmlspecialchars($sport_data['sport_name']) . '</option>';
                            $slide_index++;
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="relative w-full overflow-hidden select-none">
                <!-- Navigation arrows -->
                <button class="absolute left-1 md:left-2 top-1/2 -translate-y-1/2 z-20 bg-slate-950/90 border border-white/10 hover:border-teal-500/50 hover:bg-slate-900 text-white rounded-full w-10 h-10 flex items-center justify-center cursor-pointer transition-all duration-300 active:scale-95 disabled:opacity-20 disabled:pointer-events-none shadow-xl" id="prev-bracket-btn">
                    <i class="fa-solid fa-chevron-left text-sm"></i>
                </button>
                <button class="absolute right-1 md:right-2 top-1/2 -translate-y-1/2 z-20 bg-slate-950/90 border border-white/10 hover:border-teal-500/50 hover:bg-slate-900 text-white rounded-full w-10 h-10 flex items-center justify-center cursor-pointer transition-all duration-300 active:scale-95 disabled:opacity-20 disabled:pointer-events-none shadow-xl" id="next-bracket-btn">
                    <i class="fa-solid fa-chevron-right text-sm"></i>
                </button>

                <!-- Slides track -->
                <div class="flex transition-transform duration-500 ease-in-out" id="bracket-slides-track">
                    <?php foreach ($active_brackets as $sport_id => $sport_data): 
                        $round_matches = [1 => [], 2 => [], 3 => []];
                        foreach ($sport_data['matches'] as $b) {
                            $round_matches[$b['round_number']][] = $b;
                        }
                    ?>
                        <!-- Slide Item -->
                        <div class="w-full shrink-0 px-8 md:px-16">
                            <div class="glass-panel rounded-3xl p-6 md:p-8 hover:border-white/8 transition-all duration-300 shadow-2xl">
                                <!-- Slide Header -->
                                <div class="flex items-center justify-between border-b border-white/5 pb-4 mb-6">
                                    <div>
                                        <h3 class="text-xl md:text-2xl font-bold text-white font-heading tracking-wide">
                                            <i class="fa-solid fa-trophy text-[#d4af37] mr-2 drop-shadow-[0_0_8px_rgba(212,175,55,0.3)]"></i>
                                            <?= htmlspecialchars($sport_data['sport_name']) ?>
                                        </h3>
                                        <span class="text-xs text-slate-400 font-semibold block mt-1">ประเภท: <?= htmlspecialchars($sport_data['sport_category']) ?></span>
                                    </div>
                                    <div class="text-[10px] uppercase font-bold tracking-widest text-teal-400 bg-teal-500/10 border border-teal-500/20 px-3.5 py-1.5 rounded-full">
                                        สายการแข่งขันทัวร์นาเมนต์
                                    </div>
                                </div>

                                <!-- Bracket Tree Grid -->
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative py-2">
                                    <!-- Round 1: Quarter-finals -->
                                    <div class="flex flex-col gap-6 justify-center">
                                        <h4 class="text-xs font-bold text-indigo-400 uppercase tracking-wider text-center border-b border-indigo-500/10 pb-2 font-heading">Quarter-finals</h4>
                                        <?php foreach ($round_matches[1] as $b): ?>
                                            <div class="bg-slate-900/60 border border-white/5 rounded-2xl p-4 flex flex-col gap-2 relative shadow-md hover:border-white/10 transition-all duration-300">
                                                <div class="text-[10px] text-slate-400 font-bold flex justify-between border-b border-white/5 pb-1">
                                                    <span>คู่ที่ <?= $b['match_order'] ?></span>
                                                    <?php if ($b['status'] === 'Completed'): ?>
                                                        <span class="text-green-400">เสร็จสิ้น</span>
                                                    <?php elseif ($b['status'] === 'Live'): ?>
                                                        <span class="text-rose-400">กำลังแข่ง</span>
                                                    <?php else: ?>
                                                        <span class="text-slate-500">รอแข่ง</span>
                                                    <?php endif; ?>
                                                </div>
                                                
                                                <!-- Team 1 -->
                                                <div class="flex justify-between items-center py-1 <?= ($b['winner_house_id'] !== null && $b['winner_house_id'] == $b['team1_house_id']) ? 'font-bold text-white' : 'text-slate-400' ?>">
                                                    <span class="flex items-center gap-2 text-xs truncate">
                                                        <span class="w-2 h-2 rounded-full shrink-0" style="background-color: <?= $b['team1_color'] ?: '#334155' ?>"></span>
                                                        <?= $b['team1_name'] ? htmlspecialchars($presenter->getHouseNameTh($b['team1_name'])) : 'TBD' ?>
                                                    </span>
                                                    <span class="text-xs font-black"><?= $b['team1_score'] !== null ? $b['team1_score'] : '-' ?></span>
                                                </div>

                                                <!-- Team 2 -->
                                                <div class="flex justify-between items-center py-1 <?= ($b['winner_house_id'] !== null && $b['winner_house_id'] == $b['team2_house_id']) ? 'font-bold text-white' : 'text-slate-400' ?>">
                                                    <span class="flex items-center gap-2 text-xs truncate">
                                                        <span class="w-2 h-2 rounded-full shrink-0" style="background-color: <?= $b['team2_color'] ?: '#334155' ?>"></span>
                                                        <?= $b['team2_name'] ? htmlspecialchars($presenter->getHouseNameTh($b['team2_name'])) : 'TBD' ?>
                                                    </span>
                                                    <span class="text-xs font-black"><?= $b['team2_score'] !== null ? $b['team2_score'] : '-' ?></span>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>

                                    <!-- Round 2: Semi-finals -->
                                    <div class="flex flex-col gap-6 justify-center">
                                        <h4 class="text-xs font-bold text-purple-400 uppercase tracking-wider text-center border-b border-purple-500/10 pb-2 font-heading">Semi-finals</h4>
                                        <?php foreach ($round_matches[2] as $b): ?>
                                            <div class="bg-slate-900/60 border border-white/5 rounded-2xl p-4 flex flex-col gap-2 relative shadow-md hover:border-white/10 transition-all duration-300">
                                                <div class="text-[10px] text-slate-400 font-bold flex justify-between border-b border-white/5 pb-1">
                                                    <span>คู่ที่ <?= $b['match_order'] ?></span>
                                                    <?php if ($b['status'] === 'Completed'): ?>
                                                        <span class="text-green-400">เสร็จสิ้น</span>
                                                    <?php elseif ($b['status'] === 'Live'): ?>
                                                        <span class="text-rose-400">กำลังแข่ง</span>
                                                    <?php else: ?>
                                                        <span class="text-slate-500">รอแข่ง</span>
                                                    <?php endif; ?>
                                                </div>
                                                
                                                <!-- Team 1 -->
                                                <div class="flex justify-between items-center py-1 <?= ($b['winner_house_id'] !== null && $b['winner_house_id'] == $b['team1_house_id']) ? 'font-bold text-white' : 'text-slate-400' ?>">
                                                    <span class="flex items-center gap-2 text-xs truncate">
                                                        <span class="w-2 h-2 rounded-full shrink-0" style="background-color: <?= $b['team1_color'] ?: '#334155' ?>"></span>
                                                        <?= $b['team1_name'] ? htmlspecialchars($presenter->getHouseNameTh($b['team1_name'])) : 'TBD' ?>
                                                        <span class="text-[8px] bg-teal-500/10 text-teal-400 px-1 py-0.2 rounded border border-teal-500/10 scale-90">BYE</span>
                                                    </span>
                                                    <span class="text-xs font-black"><?= $b['team1_score'] !== null ? $b['team1_score'] : '-' ?></span>
                                                </div>

                                                <!-- Team 2 -->
                                                <div class="flex justify-between items-center py-1 <?= ($b['winner_house_id'] !== null && $b['winner_house_id'] == $b['team2_house_id']) ? 'font-bold text-white' : 'text-slate-400' ?>">
                                                    <span class="flex items-center gap-2 text-xs truncate">
                                                        <span class="w-2 h-2 rounded-full shrink-0" style="background-color: <?= $b['team2_color'] ?: '#334155' ?>"></span>
                                                        <?= $b['team2_name'] ? htmlspecialchars($presenter->getHouseNameTh($b['team2_name'])) : 'รอผู้ชนะคู่ ' . ($b['match_order'] == 1 ? '1' : '2') ?>
                                                    </span>
                                                    <span class="text-xs font-black"><?= $b['team2_score'] !== null ? $b['team2_score'] : '-' ?></span>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>

                                    <!-- Round 3: Finals & Third-place -->
                                    <div class="flex flex-col gap-6 justify-center">
                                        <?php 
                                        $finals_match = null;
                                        $third_place_match = null;
                                        foreach ($round_matches[3] as $bm) {
                                            if ($bm['round_name'] === 'Finals') {
                                                $finals_match = $bm;
                                            } elseif ($bm['round_name'] === 'Third-place') {
                                                $third_place_match = $bm;
                                            }
                                        }
                                        ?>
                                        
                                        <!-- Finals -->
                                        <?php if ($finals_match): $b = $finals_match; ?>
                                            <h4 class="text-xs font-bold text-[#d4af37] uppercase tracking-wider text-center border-b border-[#d4af37]/10 pb-2 font-heading">Finals</h4>
                                            <div class="bg-slate-900/60 border border-[#d4af37]/20 rounded-2xl p-5 flex flex-col gap-3 relative shadow-md hover:border-[#d4af37]/40 transition-all duration-300 bg-gradient-to-b from-slate-900/60 to-yellow-500/2">
                                                <div class="text-[10px] text-slate-400 font-bold flex justify-between border-b border-white/5 pb-1">
                                                    <span>คู่ชิงชนะเลิศ</span>
                                                    <?php if ($b['status'] === 'Completed'): ?>
                                                        <span class="text-yellow-400 flex items-center gap-1"><i class="fa-solid fa-trophy text-xs animate-bounce"></i>ได้ผู้ชนะเลิศ</span>
                                                    <?php elseif ($b['status'] === 'Live'): ?>
                                                        <span class="text-rose-400">กำลังแข่ง</span>
                                                    <?php else: ?>
                                                        <span class="text-slate-500">รอแข่ง</span>
                                                    <?php endif; ?>
                                                </div>
                                                
                                                <!-- Team 1 -->
                                                <div class="flex justify-between items-center py-1.5 <?= ($b['winner_house_id'] !== null && $b['winner_house_id'] == $b['team1_house_id']) ? 'font-bold text-yellow-400' : 'text-slate-400' ?>">
                                                    <span class="flex items-center gap-2 text-xs truncate">
                                                        <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background-color: <?= $b['team1_color'] ?: '#334155' ?>"></span>
                                                        <?= $b['team1_name'] ? htmlspecialchars($presenter->getHouseNameTh($b['team1_name'])) : 'รอผู้ชนะรอบรอง 1' ?>
                                                    </span>
                                                    <span class="text-xs font-black"><?= $b['team1_score'] !== null ? $b['team1_score'] : '-' ?></span>
                                                </div>

                                                <!-- Team 2 -->
                                                <div class="flex justify-between items-center py-1.5 <?= ($b['winner_house_id'] !== null && $b['winner_house_id'] == $b['team2_house_id']) ? 'font-bold text-yellow-400' : 'text-slate-400' ?>">
                                                    <span class="flex items-center gap-2 text-xs truncate">
                                                        <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background-color: <?= $b['team2_color'] ?: '#334155' ?>"></span>
                                                        <?= $b['team2_name'] ? htmlspecialchars($presenter->getHouseNameTh($b['team2_name'])) : 'รอผู้ชนะรอบรอง 2' ?>
                                                    </span>
                                                    <span class="text-xs font-black"><?= $b['team2_score'] !== null ? $b['team2_score'] : '-' ?></span>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <!-- Third Place Playoff -->
                                        <?php if ($third_place_match): 
                                            $b = $third_place_match;
                                            $isJointThird = false;
                                            if (isset($matchResults[$b['match_id']])) {
                                                $bCount = 0;
                                                foreach ($matchResults[$b['match_id']] as $mRes) {
                                                    if (isset($mRes['medal']) && $mRes['medal'] === 'Bronze') $bCount++;
                                                }
                                                if ($bCount >= 2) $isJointThird = true;
                                            }
                                        ?>
                                            <h4 class="text-xs font-bold text-amber-600 uppercase tracking-wider text-center border-b border-amber-600/10 pt-4 pb-2 font-heading">Third-place (ชิงอันดับ 3)</h4>
                                            <div class="bg-slate-900/60 border border-amber-600/20 rounded-2xl p-5 flex flex-col gap-3 relative shadow-md hover:border-amber-600/40 transition-all duration-300 bg-gradient-to-b from-slate-900/60 to-amber-700/2">
                                                <div class="text-[10px] text-slate-400 font-bold flex justify-between border-b border-white/5 pb-1">
                                                    <span>คู่ชิงอันดับที่ 3</span>
                                                    <?php if ($b['status'] === 'Completed' && $isJointThird): ?>
                                                        <span class="text-amber-400 font-bold flex items-center gap-1"><i class="fa-solid fa-medal text-xs"></i>อันดับ 3 ร่วม (เหรียญทองแดงคู่)</span>
                                                    <?php elseif ($b['status'] === 'Completed' && $b['winner_house_id'] !== null): ?>
                                                        <span class="text-amber-500 flex items-center gap-1"><i class="fa-solid fa-medal text-xs"></i>ได้อันดับที่ 3</span>
                                                    <?php elseif ($b['status'] === 'Completed' && $b['winner_house_id'] === null): ?>
                                                        <span class="text-amber-400/80 flex items-center gap-1"><i class="fa-solid fa-ban text-xs"></i>บาย (ไม่มีที่ 3)</span>
                                                    <?php elseif ($b['status'] === 'Live'): ?>
                                                        <span class="text-rose-400">กำลังแข่ง</span>
                                                    <?php else: ?>
                                                        <span class="text-slate-500">รอแข่ง</span>
                                                    <?php endif; ?>
                                                </div>
                                                
                                                <?php if ($b['status'] === 'Completed' && $isJointThird): ?>
                                                    <div class="bg-amber-500/10 border border-amber-500/20 rounded-xl p-2.5 text-center text-xs text-amber-300 font-semibold flex items-center justify-center gap-1.5">
                                                        <i class="fa-solid fa-medal text-amber-400"></i> ได้อันดับที่ 3 ร่วมกันทั้ง 2 ทีม (รับเหรียญทองแดงคู่)
                                                    </div>
                                                <?php elseif ($b['status'] === 'Completed' && $b['winner_house_id'] === null): ?>
                                                    <div class="bg-amber-500/10 border border-amber-500/20 rounded-xl p-2.5 text-center text-xs text-amber-300 font-semibold flex items-center justify-center gap-1.5">
                                                        <i class="fa-solid fa-ban text-amber-400"></i> รายการนี้ไม่มีการชิงอันดับ 3 (ผลเป็น บาย)
                                                    </div>
                                                <?php endif; ?>

                                                <!-- Team 1 -->
                                                <div class="flex justify-between items-center py-1.5 <?= ($isJointThird || ($b['winner_house_id'] !== null && $b['winner_house_id'] == $b['team1_house_id'])) ? 'font-bold text-amber-500' : 'text-slate-400' ?>">
                                                    <span class="flex items-center gap-2 text-xs truncate">
                                                        <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background-color: <?= $b['team1_color'] ?: '#334155' ?>"></span>
                                                        <?= $b['team1_name'] ? htmlspecialchars($presenter->getHouseNameTh($b['team1_name'])) : 'รอผู้แพ้รอบรอง 1' ?>
                                                        <?php if ($isJointThird): ?><i class="fa-solid fa-medal text-amber-500 text-[10px]"></i><?php endif; ?>
                                                    </span>
                                                    <span class="text-xs font-black"><?= $b['team1_score'] !== null ? $b['team1_score'] : '-' ?></span>
                                                </div>

                                                <!-- Team 2 -->
                                                <div class="flex justify-between items-center py-1.5 <?= ($isJointThird || ($b['winner_house_id'] !== null && $b['winner_house_id'] == $b['team2_house_id'])) ? 'font-bold text-amber-500' : 'text-slate-400' ?>">
                                                    <span class="flex items-center gap-2 text-xs truncate">
                                                        <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background-color: <?= $b['team2_color'] ?: '#334155' ?>"></span>
                                                        <?= $b['team2_name'] ? htmlspecialchars($presenter->getHouseNameTh($b['team2_name'])) : 'รอผู้แพ้รอบรอง 2' ?>
                                                        <?php if ($isJointThird): ?><i class="fa-solid fa-medal text-amber-500 text-[10px]"></i><?php endif; ?>
                                                    </span>
                                                    <span class="text-xs font-black"><?= $b['team2_score'] !== null ? $b['team2_score'] : '-' ?></span>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Carousel navigation dots -->
                <div class="flex justify-center gap-2 mt-8" id="bracket-dots-container"></div>
            </div>
        <?php endif; ?>
    </main>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const track = document.getElementById('bracket-slides-track');
        const dotsContainer = document.getElementById('bracket-dots-container');
        const prevBtn = document.getElementById('prev-bracket-btn');
        const nextBtn = document.getElementById('next-bracket-btn');
        const filterSelect = document.getElementById('bracket-filter');
        
        if (!track) return;
        
        const slides = track.children;
        if (slides.length === 0) return;
        
        let currentIndex = 0;
        const totalSlides = slides.length;
        
        // Create dots
        for (let i = 0; i < totalSlides; i++) {
            const dot = document.createElement('button');
            dot.className = `w-2 h-2 rounded-full transition-all duration-300 ${i === 0 ? 'w-6 bg-teal-400' : 'bg-slate-700 hover:bg-slate-600'} cursor-pointer`;
            dot.setAttribute('aria-label', `Go to slide ${i + 1}`);
            dot.addEventListener('click', () => goToSlide(i));
            dotsContainer.appendChild(dot);
        }
        
        const dots = dotsContainer.children;
        
        function updateControls() {
            if (prevBtn) prevBtn.disabled = currentIndex === 0;
            if (nextBtn) nextBtn.disabled = currentIndex === totalSlides - 1;
            
            // Update select value
            if (filterSelect) {
                filterSelect.value = currentIndex;
            }
            
            // Update dots
            for (let i = 0; i < totalSlides; i++) {
                if (i === currentIndex) {
                    dots[i].className = 'w-6 h-2 bg-teal-400 rounded-full transition-all duration-300 cursor-pointer';
                } else {
                    dots[i].className = 'w-2 h-2 bg-slate-700 rounded-full hover:bg-slate-600 transition-all duration-300 cursor-pointer';
                }
            }
        }
        
        function goToSlide(index) {
            if (index < 0 || index >= totalSlides) return;
            currentIndex = index;
            track.style.transform = `translateX(-${currentIndex * 100}%)`;
            updateControls();
        }
        
        if (prevBtn) prevBtn.addEventListener('click', () => goToSlide(currentIndex - 1));
        if (nextBtn) nextBtn.addEventListener('click', () => goToSlide(currentIndex + 1));
        
        if (filterSelect) {
            filterSelect.addEventListener('change', (e) => {
                goToSlide(parseInt(e.target.value, 10));
            });
        }
        
        updateControls();
        
        // Resize handler to adjust slide widths
        window.addEventListener('resize', () => {
            track.style.transform = `translateX(-${currentIndex * 100}%)`;
        });
    });
    </script>

    <?php include __DIR__ . '/components/footer.php'; ?>
</body>
</html>
