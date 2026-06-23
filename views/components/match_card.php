<?php
/**
 * Match Card / Row Component
 * Expected variables:
 * - $match: array representation of match data
 * - $presenter: SportPresenter helper object
 * - $matchResults: (optional array) list of results indexed by match ID
 * - $isDashboardList: (optional boolean) compact view indicator
 */
$isDashboardList = isset($isDashboardList) && $isDashboardList;
$sportName = htmlspecialchars($match['sport_name']);
$category = htmlspecialchars($match['category']);
$eventDate = $presenter->formatDate($match['event_date']);
$status = $match['status'];
$matchId = $match['id'];

$results = (isset($matchResults) && isset($matchResults[$matchId])) ? $matchResults[$matchId] : [];
?>

<?php if ($isDashboardList): ?>
    <!-- Compact layout (Dashboard Sidebar Lists) -->
    <div class="bg-slate-900/40 backdrop-blur-md border border-white/5 rounded-xl p-4 flex justify-between items-center hover:translate-x-1.5 hover:border-white/10 hover:bg-slate-900/60 transition-all duration-300">
        <div class="min-w-0 pr-2">
            <strong class="block text-sm text-white font-bold truncate mb-0.5 font-heading tracking-wide"><?= $sportName ?></strong>
            <span class="text-[11px] text-slate-400 font-semibold block truncate">
                <?php if ($match['bracket_id'] !== null): ?>
                    <i class="fa-solid fa-trophy mr-1 text-indigo-400"></i><?= htmlspecialchars($match['round_name']) ?>
                <?php else: ?>
                    <i class="fa-solid fa-tags mr-1 text-indigo-400"></i><?= htmlspecialchars($match['category']) ?>
                <?php endif; ?>
            </span>
        </div>
        <div class="shrink-0 ml-2 select-none">
            <?php if ($status === 'Completed'): ?>
                <span class="inline-flex bg-green-500/10 text-green-400 border border-green-500/20 text-[10px] font-bold px-2.5 py-0.5 rounded-full shadow-sm">เสร็จสิ้น</span>
            <?php elseif ($status === 'Live'): ?>
                <span class="inline-flex bg-rose-500/10 text-rose-400 border border-rose-500/25 text-[10px] font-bold px-2.5 py-0.5 rounded-full items-center shadow-inner"><span class="live-pulse-glow mr-1"></span>กำลังแข่ง</span>
            <?php else: ?>
                <span class="inline-flex bg-slate-800 text-slate-400 border border-white/5 text-[10px] font-bold px-2.5 py-0.5 rounded-full">รอแข่ง</span>
            <?php endif; ?>
        </div>
    </div>
<?php else: ?>
    <!-- Full-sized layout (Landing page grids and schedules) -->
    <div class="glass-card rounded-2xl p-5 md:p-6 flex flex-col md:flex-row md:items-center justify-between gap-4 hover:border-white/12 transition-all duration-300">
        <div class="flex items-center gap-4">
            <!-- Icon indicator based on status -->
            <?php if ($status === 'Completed'): ?>
                <div class="bg-yellow-500/10 border border-yellow-500/20 p-3.5 rounded-2xl text-yellow-500 text-xl shadow-md shrink-0">
                    <i class="fa-solid fa-trophy"></i>
                </div>
            <?php elseif ($status === 'Live'): ?>
                <div class="bg-rose-500/10 border border-rose-500/20 p-3.5 rounded-2xl text-rose-400 text-xl shadow-md shrink-0 animate-pulse">
                    <i class="fa-solid fa-clock"></i>
                </div>
            <?php else: ?>
                <div class="bg-indigo-500/10 border border-indigo-500/20 p-3.5 rounded-2xl text-indigo-400 text-xl shadow-md shrink-0">
                    <i class="fa-solid fa-calendar-days"></i>
                </div>
            <?php endif; ?>

            <div>
                <h4 class="text-lg font-bold text-white mb-0.5 font-heading tracking-wide"><?= $sportName ?></h4>
                <span class="text-xs text-slate-400 block font-semibold leading-normal">
                    หมวดหมู่: <?= $category ?><?php if ($match['bracket_id'] !== null): ?> • รอบ: <?= htmlspecialchars($match['round_name']) ?><?php endif; ?>
                </span>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 shrink-0">
            <?php if ($status === 'Completed'): ?>
                <!-- Render medal standings if completed -->
                <?php if (!empty($results)): ?>
                    <div class="flex flex-wrap gap-2">
                        <?php foreach ($results as $res): ?>
                            <?php if (!empty($res['medal'])): 
                                $medalIcon = 'fa-solid fa-medal text-yellow-500';
                                if ($res['medal'] === 'Silver') $medalIcon = 'fa-solid fa-medal text-slate-300';
                                elseif ($res['medal'] === 'Bronze') $medalIcon = 'fa-solid fa-medal text-orange-500';
                                $hNameTh = $presenter->getHouseNameTh($res['house_name']);
                            ?>
                                <div class="inline-flex bg-[rgba(var(--house-color-rgb),0.06)] text-[var(--house-color)] border border-[rgba(var(--house-color-rgb),0.18)] pl-2.5 pr-3.5 py-1 items-center gap-1.5 rounded-full text-xs font-semibold shadow-sm hover:bg-[rgba(var(--house-color-rgb),0.12)] transition-colors duration-200" <?= $presenter->getHouseStyle($res['color_code']) ?>>
                                    <i class="<?= $medalIcon ?>"></i>
                                    <span class="font-heading"><?= htmlspecialchars($hNameTh) ?></span>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <span class="text-slate-500 text-xs font-semibold">ไม่มีบันทึกผู้ชนะ</span>
                <?php endif; ?>
            <?php else: ?>
                <!-- Render status badge for Live/Scheduled -->
                <div class="select-none">
                    <?php if ($status === 'Live'): ?>
                        <span class="inline-flex bg-rose-500/10 text-rose-400 border border-rose-500/25 text-xs font-bold px-4 py-1.5 rounded-full items-center shadow-inner animate-float-badge"><span class="live-pulse-glow mr-1.5"></span>กำลังแข่ง</span>
                    <?php else: ?>
                        <span class="inline-flex bg-slate-800 text-slate-400 border border-white/5 text-xs font-bold px-4 py-1.5 rounded-full shadow-sm">รอการแข่งขัน</span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
