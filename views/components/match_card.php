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
    <div class="glass-card bg-slate-900/90 border border-slate-800 rounded-2xl p-5 flex flex-col md:flex-row md:items-center justify-between gap-4 hover:border-slate-700 transition-all shadow-xl">
        <div class="flex items-center gap-3.5">
            <!-- Status Icon -->
            <?php if ($status === 'Completed'): ?>
                <div class="bg-amber-500/10 border border-amber-500/20 w-12 h-12 rounded-xl flex items-center justify-center text-amber-400 text-xl shadow-md shrink-0">
                    <i class="fa-solid fa-trophy"></i>
                </div>
            <?php elseif ($status === 'Live'): ?>
                <div class="bg-rose-500/20 border border-rose-500/40 w-12 h-12 rounded-xl flex items-center justify-center text-rose-400 text-xl shadow-md shrink-0 animate-pulse">
                    <i class="fa-solid fa-bolt"></i>
                </div>
            <?php else: ?>
                <div class="bg-indigo-500/10 border border-indigo-500/20 w-12 h-12 rounded-xl flex items-center justify-center text-indigo-400 text-xl shadow-md shrink-0">
                    <i class="fa-solid fa-calendar-days"></i>
                </div>
            <?php endif; ?>

            <div>
                <div class="flex items-center gap-2">
                    <h4 class="text-base md:text-lg font-extrabold text-white font-heading tracking-wide mb-0.5"><?= $sportName ?></h4>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-slate-800 text-slate-300 border border-slate-700"><?= $category ?></span>
                </div>
                <span class="text-xs text-slate-400 block font-medium">
                    <?php if ($match['bracket_id'] !== null): ?>
                        รอบ: <span class="text-teal-400 font-semibold"><?= htmlspecialchars($match['round_name']) ?></span>
                    <?php else: ?>
                        วันที่: <span class="text-slate-300"><?= $eventDate ?></span>
                    <?php endif; ?>
                </span>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2.5 shrink-0 pt-2 md:pt-0 border-t md:border-t-0 border-slate-800">
            <?php if ($status === 'Completed'): ?>
                <!-- Clean & Readable Result Medals -->
                <?php if (!empty($results)): ?>
                    <div class="flex flex-wrap gap-2">
                        <?php foreach ($results as $res): ?>
                            <?php if (!empty($res['medal'])): 
                                $medalIcon = 'fa-solid fa-medal text-amber-400';
                                $badgeBg = 'bg-amber-500/10 border-amber-500/30 text-amber-300';
                                if ($res['medal'] === 'Silver') {
                                    $medalIcon = 'fa-solid fa-medal text-slate-300';
                                    $badgeBg = 'bg-slate-700/30 border-slate-500/40 text-slate-200';
                                } elseif ($res['medal'] === 'Bronze') {
                                    $medalIcon = 'fa-solid fa-medal text-amber-600';
                                    $badgeBg = 'bg-amber-800/20 border-amber-700/40 text-amber-400';
                                }
                                $hNameTh = $presenter->getHouseNameTh($res['house_name']);
                            ?>
                                <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold border shadow-sm <?= $badgeBg ?>">
                                    <i class="<?= $medalIcon ?> text-sm"></i>
                                    <span><?= htmlspecialchars($hNameTh) ?></span>
                                    <?php if (isset($res['score']) && $res['score'] !== null): ?>
                                        <span class="font-mono bg-slate-950/80 px-1.5 py-0.5 rounded text-[11px] text-white ml-1"><?= htmlspecialchars($res['score']) ?></span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                <?php elseif ($match['bracket_id'] !== null): ?>
                    <?php if ($match['winner_house_id'] !== null): 
                        $winnerName = ($match['winner_house_id'] == $match['team1_house_id']) ? $match['team1_name'] : $match['team2_name'];
                        $winnerColor = ($match['winner_house_id'] == $match['team1_house_id']) ? $match['team1_color'] : $match['team2_color'];
                    ?>
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl text-xs font-extrabold bg-slate-950 border border-slate-700 shadow-md">
                            <span class="text-amber-400">🏆 ผู้ชนะ:</span>
                            <span style="color: <?= $winnerColor ?>"><?= htmlspecialchars($presenter->getHouseNameTh($winnerName)) ?></span>
                            <span class="font-mono text-white bg-slate-800 px-2 py-0.5 rounded"><?= intval($match['team1_score']) ?> - <?= intval($match['team2_score']) ?></span>
                        </div>
                    <?php else: ?>
                        <span class="inline-flex items-center gap-1 font-bold text-amber-300 bg-amber-500/10 border border-amber-500/20 px-3 py-1 rounded-full text-xs">บาย (ไม่มีผู้ชนะ)</span>
                    <?php endif; ?>
                <?php else: ?>
                    <span class="text-emerald-400 text-xs font-bold bg-emerald-500/10 border border-emerald-500/20 px-3 py-1.5 rounded-xl">✓ เสร็จสิ้นการแข่งขัน</span>
                <?php endif; ?>
            <?php else: ?>
                <!-- Live / Scheduled badge -->
                <div class="select-none">
                    <?php if ($status === 'Live'): ?>
                        <span class="inline-flex bg-red-500/20 text-red-400 border border-red-500/30 text-xs font-bold px-3.5 py-1.5 rounded-xl items-center gap-1.5 animate-pulse">
                            <span class="w-2 h-2 rounded-full bg-red-500"></span> กำลังแข่งขัน
                        </span>
                    <?php else: ?>
                        <span class="inline-flex bg-slate-800 text-slate-400 border border-slate-700 text-xs font-bold px-3.5 py-1.5 rounded-xl">รอการแข่งขัน</span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

