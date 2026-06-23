<?php
/**
 * Leaderboard Row Component
 * Expected variables:
 * - $row: array representation of house data
 * - $presenter: SportPresenter helper object
 * - $rank: current rank index (integer)
 * - $maxPoints: maximum points for scaling the graph bar (integer)
 * - $isCompact: (optional boolean) compact layout indicator
 */
$isCompact = isset($isCompact) && $isCompact;
$houseNameTh = $presenter->getHouseNameTh($row['house_name']);
$pct = ($maxPoints > 0) ? ($row['total_points'] / $maxPoints) * 100 : 0;
$houseStyle = $presenter->getHouseStyle($row['color_code']);
?>

<?php if ($isCompact): ?>
    <!-- Compact layout (Dashboard widgets) -->
    <div class="flex justify-between items-center p-3.5 rounded-xl border border-white/5 bg-white/[0.01] hover:bg-white/[0.03] hover:border-white/10 hover:translate-x-1 border-l-4 border-l-[var(--house-color)] transition-all duration-200" <?= $houseStyle ?>>
        <div class="flex items-center gap-3">
            <!-- Rank index -->
            <div class="text-base font-black w-5 text-center font-heading <?= ($rank === 1) ? 'text-yellow-400' : 'text-slate-400' ?>">
                <?php if ($rank === 1): ?>
                    <i class="fa-solid fa-crown animate-crown text-xs"></i>
                <?php else: ?>
                    <?= $rank ?>
                <?php endif; ?>
            </div>
            <!-- Name and Medals -->
            <div>
                <div class="font-bold text-white text-xs sm:text-sm mb-0.5 font-heading tracking-wide"><?= htmlspecialchars($houseNameTh) ?></div>
                <div class="flex flex-wrap gap-1 select-none">
                    <span class="inline-flex bg-yellow-500/10 text-yellow-400 border border-yellow-500/20 text-[8px] font-black px-1.5 py-0.5 rounded-full">ทอง: <?= $row['gold_count'] ?></span>
                    <span class="inline-flex bg-slate-300/10 text-slate-300 border border-slate-300/20 text-[8px] font-black px-1.5 py-0.5 rounded-full">เงิน: <?= $row['silver_count'] ?></span>
                    <span class="inline-flex bg-orange-500/10 text-orange-400 border border-orange-500/20 text-[8px] font-black px-1.5 py-0.5 rounded-full">ทองแดง: <?= $row['bronze_count'] ?></span>
                </div>
            </div>
        </div>
        <!-- Points -->
        <div class="text-sm font-black text-indigo-400 shrink-0 ml-2 font-heading tracking-wider"><?= htmlspecialchars($row['total_points']) ?> คะแนน</div>
    </div>
<?php else: ?>
    <!-- Full-sized layout (Scoreboards / Landing) -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-5 rounded-2xl bg-white/[0.01] hover:bg-slate-900/60 border-l-6 border-[var(--house-color)] border border-white/5 hover:border-white/12 transition-all duration-300 relative group overflow-hidden" <?= $houseStyle ?>>
        <!-- Ambient hover gradient overlay -->
        <div class="absolute inset-0 bg-gradient-to-r from-[var(--house-color)]/3 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>

        <!-- Rank & Name Details -->
        <div class="flex items-center gap-4 relative z-10">
            <!-- Large Rank -->
            <div class="text-2xl sm:text-3xl font-black w-8 text-center font-heading">
                <?php if ($rank === 1): ?>
                    <span class="text-yellow-500 drop-shadow-[0_0_8px_rgba(234,179,8,0.55)]"><i class="fa-solid fa-crown animate-crown"></i></span>
                <?php else: ?>
                    <span class="text-slate-400"><?= $rank ?></span>
                <?php endif; ?>
            </div>
            
            <!-- Name, Medals and Progress bar -->
            <div>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <span class="text-lg sm:text-xl font-bold text-white font-heading tracking-wide"><?= htmlspecialchars($houseNameTh) ?></span>
                    <div class="flex flex-wrap gap-1 select-none">
                        <span class="inline-flex bg-yellow-500/10 text-yellow-400 border border-yellow-500/20 text-[9px] font-black px-2.5 py-0.5 rounded-full shadow-sm"><i class="fa-solid fa-medal mr-1"></i>ทอง: <?= $row['gold_count'] ?></span>
                        <span class="inline-flex bg-slate-300/10 text-slate-300 border border-slate-300/20 text-[9px] font-black px-2.5 py-0.5 rounded-full shadow-sm"><i class="fa-solid fa-medal mr-1"></i>เงิน: <?= $row['silver_count'] ?></span>
                        <span class="inline-flex bg-orange-500/10 text-orange-400 border border-orange-500/20 text-[9px] font-black px-2.5 py-0.5 rounded-full shadow-sm"><i class="fa-solid fa-medal mr-1"></i>ทองแดง: <?= $row['bronze_count'] ?></span>
                    </div>
                </div>
                <!-- Interactive Point Progress Bar -->
                <div class="bg-white/5 border border-white/5 rounded-full h-2 w-48 sm:w-64 overflow-hidden relative">
                    <div class="h-full rounded-full bg-[var(--house-color)] shadow-[0_0_12px_rgba(var(--house-color-rgb),0.6)] transition-all duration-1000 group-hover:brightness-110" style="width: <?= $pct ?>%;"></div>
                </div>
            </div>
        </div>

        <!-- Points display -->
        <div class="text-2xl sm:text-3xl font-black text-right text-indigo-400 font-heading shrink-0 relative z-10 tracking-wider">
            <?= htmlspecialchars($row['total_points']) ?>
            <span class="text-xs text-slate-400 font-semibold block sm:inline sm:text-sm sm:ml-1">คะแนน</span>
        </div>
    </div>
<?php endif; ?>
