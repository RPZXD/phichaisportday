<?php
/**
 * House Card Component
 * Expected variables:
 * - $row: array representation of house data
 * - $presenter: SportPresenter helper object
 */
$iconClass = $presenter->getHouseIcon($row['house_name']);
$houseNameTh = $presenter->getHouseNameTh($row['house_name']);
$houseStyle = $presenter->getHouseStyle($row['color_code']);
?>
<div class="glass-card glass-glow-house rounded-[28px] p-6 text-center border border-[rgba(var(--house-color-rgb),0.15)] transition-all duration-300 relative group overflow-hidden" <?= $houseStyle ?>>
    <!-- Inner gradient glow overlay -->
    <div class="absolute inset-0 bg-gradient-to-br from-[var(--house-color)]/8 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>
    
    <!-- House Icon Container -->
    <div class="w-16 h-16 rounded-2xl bg-[rgba(var(--house-color-rgb),0.06)] border border-[rgba(var(--house-color-rgb),0.18)] text-[var(--house-color)] flex items-center justify-center mx-auto mb-6 text-2xl shadow-[0_4px_20px_rgba(var(--house-color-rgb),0.12)] group-hover:scale-110 group-hover:rotate-12 group-hover:bg-[rgba(var(--house-color-rgb),0.12)] transition-all duration-300">
        <i class="<?= $iconClass ?>"></i>
    </div>
    
    <!-- House details -->
    <h3 class="text-xl font-bold text-[var(--house-color)] mb-3 font-heading tracking-wide drop-shadow-[0_2px_4px_rgba(0,0,0,0.3)]">
        <?= htmlspecialchars($houseNameTh) ?>
    </h3>
    
    <span class="inline-flex items-center gap-1 bg-[rgba(var(--house-color-rgb),0.06)] text-[var(--house-color)] border border-[rgba(var(--house-color-rgb),0.18)] text-[9px] font-black px-3.5 py-1 rounded-full uppercase tracking-widest transition-colors group-hover:bg-[rgba(var(--house-color-rgb),0.12)]">
        <i class="fa-solid fa-circle-play text-[7px] text-[var(--house-color)]/70 animate-pulse"></i>
        ผู้ร่วมท้าชิง
    </span>
</div>
