<?php
/**
 * Podium Chart Component (Top 3 Houses)
 * Expected variables:
 * - $leaderboard: array of houses, sorted descending by points
 * - $presenter: SportPresenter helper object
 */

// Extract top 3 ranks
$top1 = isset($leaderboard[0]) ? $leaderboard[0] : null;
$top2 = isset($leaderboard[1]) ? $leaderboard[1] : null;
$top3 = isset($leaderboard[2]) ? $leaderboard[2] : null;

// Determine max points for scale
$maxPoints = ($top1 && $top1['total_points'] > 0) ? $top1['total_points'] : 1;

// Helper to render a podium column
function renderPodiumBar($house, $rank, $maxPoints, $presenter) {
    if (!$house) return '';
    
    $houseNameTh = $presenter->getHouseNameTh($house['house_name']);
    $points = $house['total_points'];
    $iconClass = $presenter->getHouseIcon($house['house_name']);
    $houseStyle = $presenter->getHouseStyle($house['color_code']);
    
    // Scale visual height of the bar (1st rank gets max height 160px, others scale proportionally down to min 60px)
    $basePct = $points / $maxPoints;
    $heightPx = 60 + ($basePct * 100); // 60px to 160px
    
    // Medal configurations
    $medalIcon = 'fa-solid fa-crown text-yellow-400 animate-crown';
    $medalLabel = 'อันดับ 1';
    $medalBg = 'from-yellow-400/20 to-amber-500/10 border-yellow-500/30 text-yellow-300';
    
    if ($rank === 2) {
        $medalIcon = 'fa-solid fa-medal text-slate-300';
        $medalLabel = 'อันดับ 2';
        $medalBg = 'from-slate-400/20 to-slate-500/10 border-slate-500/30 text-slate-200';
    } elseif ($rank === 3) {
        $medalIcon = 'fa-solid fa-medal text-orange-500';
        $medalLabel = 'อันดับ 3';
        $medalBg = 'from-orange-500/20 to-orange-600/10 border-orange-500/30 text-orange-300';
    }
    
    // Custom delay based on rank
    $delayClass = $rank === 1 ? 'delay-300' : ($rank === 2 ? 'delay-100' : 'delay-500');
    ?>
    <div class="flex flex-col items-center flex-1 min-w-[90px] group animate-hero-fade-in-up <?= $delayClass ?>" <?= $houseStyle ?>>
        <!-- House badge and name -->
        <div class="text-center mb-3">
            <!-- Icon -->
            <div class="w-10 h-10 rounded-xl bg-[rgba(var(--house-color-rgb),0.06)] border border-[rgba(var(--house-color-rgb),0.2)] text-[var(--house-color)] flex items-center justify-center mx-auto mb-1.5 text-base shadow-sm group-hover:scale-110 transition-transform duration-300">
                <i class="<?= $iconClass ?>"></i>
            </div>
            <!-- Name -->
            <div class="font-bold text-xs sm:text-sm text-white font-heading tracking-wide mb-0.5 truncate max-w-[100px]"><?= htmlspecialchars($houseNameTh) ?></div>
            <!-- Points badge -->
            <div class="inline-flex bg-white/5 border border-white/10 px-2 py-0.5 rounded-md text-[10px] font-black text-indigo-300 font-heading tracking-wider">
                <?= htmlspecialchars($points) ?> Pts
            </div>
        </div>
        
        <!-- Podium Bar -->
        <div class="w-16 sm:w-20 rounded-t-2xl relative overflow-hidden flex flex-col justify-end items-center border border-b-0 border-[rgba(var(--house-color-rgb),0.25)] group-hover:border-[rgba(var(--house-color-rgb),0.55)] transition-all duration-300 shadow-[0_-8px_30px_-10px_rgba(var(--house-color-rgb),0.2)] group-hover:shadow-[0_-8px_30px_-5px_rgba(var(--house-color-rgb),0.4)]" 
             style="height: <?= $heightPx ?>px; background: linear-gradient(180deg, rgba(var(--house-color-rgb), 0.25) 0%, rgba(var(--house-color-rgb), 0.05) 100%);">
            <!-- Glass reflection overlay -->
            <div class="absolute inset-0 bg-gradient-to-r from-white/5 via-transparent to-transparent pointer-events-none"></div>
            
            <!-- Floating light streak -->
            <div class="absolute inset-x-0 top-0 h-0.5 bg-gradient-to-r from-transparent via-[var(--house-color)] to-transparent opacity-80"></div>
            
            <!-- Rank Indicator Badge inside/above bar -->
            <div class="flex flex-col items-center justify-center p-2 mb-2 relative z-10 select-none">
                <div class="w-8 h-8 rounded-full bg-slate-950/80 border border-[rgba(var(--house-color-rgb),0.3)] flex items-center justify-center shadow-md">
                    <i class="<?= $medalIcon ?> text-sm"></i>
                </div>
                <span class="text-[9px] font-black tracking-widest uppercase text-slate-400 mt-1 font-heading"><?= $rank ?></span>
            </div>
        </div>
    </div>
    <?php
}
?>

<!-- Podium Container -->
<?php if ($top1): ?>
    <div class="mb-10 p-6 rounded-3xl bg-slate-900/35 border border-white/5 shadow-inner relative overflow-hidden select-none">
        <!-- Subtle Grid Background lines -->
        <div class="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.01)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.01)_1px,transparent_1px)] bg-[size:20px_20px] pointer-events-none"></div>
        
        <div class="flex justify-center items-end gap-3 sm:gap-6 max-w-sm mx-auto h-[240px] relative z-10 border-b border-white/10 pb-0.5">
            <!-- 2nd Place -->
            <?php renderPodiumBar($top2, 2, $maxPoints, $presenter); ?>
            
            <!-- 1st Place -->
            <?php renderPodiumBar($top1, 1, $maxPoints, $presenter); ?>
            
            <!-- 3rd Place -->
            <?php renderPodiumBar($top3, 3, $maxPoints, $presenter); ?>
        </div>
    </div>
<?php endif; ?>
