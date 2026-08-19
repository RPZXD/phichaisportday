<?php
/**
 * Dedicated Public Match Schedule & Live Timeline Page
 */
$pageTitle = "ตารางแข่งขันสดและผลคะแนน - Pichai Game 2026";
$activeRoute = "schedule";
$matches = $matches ?? [];
$matchResults = $matchResults ?? [];
$presenter = $presenter ?? new SportPresenter();

$mainCategories = [];
$totalCompletedMatches = 0;
if (!empty($matches)) {
    foreach ($matches as $m) {
        $mId = $m['id'];
        $hasResults = isset($matchResults[$mId]) && !empty($matchResults[$mId]);
        $isScored = ($m['status'] === 'Completed' || $m['status'] === 'Live' || $hasResults || $m['winner_house_id'] !== null);
        
        if ($isScored) {
            $totalCompletedMatches++;
            $fullSportName = trim($m['sport_name']);
            $mainCat = explode(' ', $fullSportName)[0];
            
            if (!isset($mainCategories[$mainCat])) {
                $mainCategories[$mainCat] = [
                    'title' => $mainCat,
                    'matches' => []
                ];
            }
            $mainCategories[$mainCat]['matches'][] = $m;
        }
    }
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

    <main class="flex-grow pt-24 pb-20 max-w-5xl mx-auto px-4 w-full">
        <!-- Page Title & Header -->
        <div class="text-center mb-10">
            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-blue-500/10 border border-blue-500/25 text-blue-300 text-xs font-bold uppercase tracking-wider mb-2.5">
                <i class="fa-solid fa-calendar-day"></i> Live Match Timeline
            </div>
            <h1 class="text-3xl md:text-5xl font-black font-heading text-white flex items-center justify-center gap-3">
                <span class="w-3 h-8 bg-gradient-to-b from-blue-400 to-indigo-500 rounded-full"></span>
                ตารางแข่งขันสดและผลคะแนนตามเวลาจริง
            </h1>
            <p class="text-slate-400 text-xs sm:text-sm mt-2 max-w-xl mx-auto">
                รายงานสดติดขอบสนามพร้อมสถานะและผลการแข่งขันแบบเรียลไทม์
            </p>
        </div>

        <!-- Minimal Category Filter Tabs -->
        <div class="flex items-center gap-2 overflow-x-auto no-scrollbar pb-3 pt-1 select-none border-b border-slate-800/80 mb-6">
            <button onclick="filterScheduleGroup('all')" id="schedule-tab-all" class="schedule-tab-btn px-4 py-2 rounded-xl bg-blue-600 text-white font-bold text-xs whitespace-nowrap shadow-md cursor-pointer transition">
                ทั้งหมด (<?= $totalCompletedMatches ?> ผลการแข่ง)
            </button>
            <?php if (!empty($mainCategories)): ?>
                <?php foreach ($mainCategories as $catName => $group): ?>
                    <?php $catHash = md5($catName); ?>
                    <button onclick="filterScheduleGroup('<?= $catHash ?>')" id="schedule-tab-<?= $catHash ?>" class="schedule-tab-btn px-4 py-2 rounded-xl bg-slate-900 border border-slate-800 text-slate-400 hover:text-white hover:bg-slate-800 font-medium text-xs whitespace-nowrap cursor-pointer transition">
                        <?= htmlspecialchars($catName) ?> (<?= count($group['matches']) ?>)
                    </button>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Accordion Blocks Per Main Category -->
        <div class="space-y-3">
            <?php if (!empty($mainCategories)): ?>
                <?php foreach ($mainCategories as $catName => $group): 
                    $groupHash = md5($catName);
                    $matchCount = count($group['matches']);
                ?>
                    <div class="schedule-group-block rounded-2xl bg-slate-900/90 border border-slate-800/80 overflow-hidden shadow-lg" data-group-hash="<?= $groupHash ?>">
                        
                        <!-- Clean Header -->
                        <button onclick="toggleAccordion('acc-<?= $groupHash ?>')" class="w-full px-5 py-4 bg-slate-900 hover:bg-slate-850 flex items-center justify-between transition cursor-pointer select-none">
                            <div class="flex items-center gap-3">
                                <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                                <h3 class="font-heading font-bold text-base text-white"><?= htmlspecialchars($catName) ?></h3>
                                <span class="text-xs text-slate-500 font-medium">(<?= $matchCount ?> รายการ)</span>
                            </div>

                            <div class="flex items-center gap-2">
                                <i id="acc-icon-acc-<?= $groupHash ?>" class="fa-solid fa-chevron-down text-slate-500 text-xs transition-transform duration-300"></i>
                            </div>
                        </button>

                        <!-- Accordion Sub-Matches List -->
                        <div id="acc-<?= $groupHash ?>" class="p-4 space-y-2.5 border-t border-slate-800/60 bg-slate-950/60">
                            <?php foreach ($group['matches'] as $match): 
                                $mId = $match['id'];
                                $mResults = (isset($matchResults) && isset($matchResults[$mId])) ? $matchResults[$mId] : [];
                                $t1Name = !empty($match['team1_name']) ? $presenter->getHouseNameTh($match['team1_name']) : 'ทีม A';
                                $t2Name = !empty($match['team2_name']) ? $presenter->getHouseNameTh($match['team2_name']) : 'ทีม B';
                                $t1Color = $match['team1_color'] ?? '#64748b';
                                $t2Color = $match['team2_color'] ?? '#64748b';
                                $t1Score = isset($match['team1_score']) ? intval($match['team1_score']) : 0;
                                $t2Score = isset($match['team2_score']) ? intval($match['team2_score']) : 0;
                                $subName = htmlspecialchars($match['sport_name']);
                                $roundText = !empty($match['round_name']) ? htmlspecialchars($match['round_name']) : htmlspecialchars($match['category']);
                            ?>
                                <div class="rounded-xl bg-slate-900/90 border border-slate-800/60 p-3.5 hover:border-slate-700 transition">
                                    <div class="flex items-center justify-between border-b border-slate-800/60 pb-2 mb-2.5">
                                        <div class="flex items-center gap-2 min-w-0 pr-2">
                                            <?php if ($match['status'] === 'Live'): ?>
                                                <span class="px-2 py-0.5 rounded bg-red-500/20 text-red-400 font-mono font-bold text-[10px] border border-red-500/30 animate-pulse shrink-0">
                                                    LIVE
                                                </span>
                                            <?php elseif ($match['status'] === 'Completed'): ?>
                                                <span class="px-2 py-0.5 rounded bg-emerald-500/10 text-emerald-400 font-mono font-bold text-[10px] border border-emerald-500/20 shrink-0">
                                                    FT
                                                </span>
                                            <?php else: ?>
                                                <span class="px-2 py-0.5 rounded bg-slate-800 text-slate-400 font-mono text-[10px] shrink-0">
                                                    รอแข่ง
                                                </span>
                                            <?php endif; ?>
                                            <span class="text-xs text-white font-bold truncate"><?= $subName ?></span>
                                        </div>
                                        <span class="text-[11px] text-slate-400 font-medium shrink-0"><?= $roundText ?></span>
                                    </div>

                                    <div class="flex items-center justify-between px-1">
                                        <div class="flex items-center gap-2 max-w-[42%]">
                                            <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background-color: <?= $t1Color ?>"></span>
                                            <span class="font-heading font-bold text-xs text-slate-200 truncate" style="color: <?= $t1Color ?>"><?= htmlspecialchars($t1Name) ?></span>
                                        </div>

                                        <div class="font-mono text-center">
                                            <?php if ($match['status'] === 'Completed' || $match['status'] === 'Live'): ?>
                                                <div class="flex items-center gap-1 bg-slate-950 px-2.5 py-1 rounded-lg border border-slate-800 text-xs">
                                                    <span class="font-bold text-white"><?= $t1Score ?></span>
                                                    <span class="text-slate-600">-</span>
                                                    <span class="font-bold text-white"><?= $t2Score ?></span>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-[11px] text-slate-500 font-bold">VS</span>
                                            <?php endif; ?>
                                        </div>

                                        <div class="flex items-center gap-2 max-w-[42%] justify-end">
                                            <span class="font-heading font-bold text-xs text-slate-200 truncate text-right" style="color: <?= $t2Color ?>"><?= htmlspecialchars($t2Name) ?></span>
                                            <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background-color: <?= $t2Color ?>"></span>
                                        </div>
                                    </div>

                                    <?php if ($match['status'] === 'Completed' && !empty($mResults)): ?>
                                        <div class="mt-2.5 pt-2 border-t border-slate-800/60 flex flex-wrap items-center gap-1.5">
                                            <?php foreach ($mResults as $res): ?>
                                                <?php if (!empty($res['medal'])): 
                                                    $medalIcon = 'fa-solid fa-medal text-amber-400';
                                                    if ($res['medal'] === 'Silver') $medalIcon = 'fa-solid fa-medal text-slate-300';
                                                    elseif ($res['medal'] === 'Bronze') $medalIcon = 'fa-solid fa-medal text-amber-600';
                                                ?>
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-slate-950 text-[10px] text-slate-300 border border-slate-800">
                                                        <i class="<?= $medalIcon ?>"></i> <?= htmlspecialchars($presenter->getHouseNameTh($res['house_name'])) ?>
                                                    </span>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="rounded-2xl bg-slate-900/40 border border-slate-800 p-12 text-center text-slate-500 text-sm">
                    <i class="fa-solid fa-trophy text-3xl mb-3 text-slate-600 block"></i>
                    ยังไม่มีการลงคะแนนหรือผลการแข่งขันที่เสร็จสิ้นสมบูรณ์ในระบบ
                </div>
            <?php endif; ?>
        </div>
    </main>

    <script>
        function toggleAccordion(id) {
            const el = document.getElementById(id);
            const icon = document.getElementById('acc-icon-' + id);
            if (el) {
                if (el.classList.contains('hidden')) {
                    el.classList.remove('hidden');
                    if (icon) icon.classList.add('rotate-180');
                } else {
                    el.classList.add('hidden');
                    if (icon) icon.classList.remove('rotate-180');
                }
            }
        }

        function filterScheduleGroup(groupHash) {
            const buttons = document.querySelectorAll('.schedule-tab-btn');
            buttons.forEach(btn => {
                btn.className = 'schedule-tab-btn px-4 py-2 rounded-xl bg-slate-900 border border-slate-800 text-slate-400 hover:text-white hover:bg-slate-800 font-medium text-xs whitespace-nowrap cursor-pointer transition';
            });
            const activeBtn = document.getElementById('schedule-tab-' + groupHash);
            if (activeBtn) {
                activeBtn.className = 'schedule-tab-btn px-4 py-2 rounded-xl bg-blue-600 text-white font-bold text-xs whitespace-nowrap shadow-md cursor-pointer transition';
            }

            const blocks = document.querySelectorAll('.schedule-group-block');
            blocks.forEach(block => {
                if (groupHash === 'all' || block.getAttribute('data-group-hash') === groupHash) {
                    block.style.display = 'block';
                } else {
                    block.style.display = 'none';
                }
            });
        }
    </script>

    <?php include __DIR__ . '/components/footer.php'; ?>
</body>
</html>
