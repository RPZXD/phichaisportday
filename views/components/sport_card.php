<?php
/**
 * Sport Card Component
 * Expected variables:
 * - $sport: array representation of sport data
 * - $presenter: SportPresenter helper object
 */
$sportName = htmlspecialchars($sport['sport_name']);
$category = htmlspecialchars($sport['category']);

// Dynamically match icons based on category and title (matching guidelines)
$sportIcon = 'fa-solid fa-circle-play';
$catLower = mb_strtolower($category, 'UTF-8');
$nameLower = mb_strtolower($sportName, 'UTF-8');

if (stripos($catLower, 'กรีฑา') !== false || stripos($nameLower, 'วิ่ง') !== false || stripos($catLower, 'ประเภทลู่') !== false) {
    $sportIcon = 'fa-solid fa-person-running';
} elseif (stripos($nameLower, 'ฟุตบอล') !== false || stripos($nameLower, 'ฟุตซอล') !== false) {
    $sportIcon = 'fa-solid fa-futbol';
} elseif (stripos($nameLower, 'บาสเกตบอล') !== false) {
    $sportIcon = 'fa-solid fa-basketball';
} elseif (stripos($nameLower, 'วอลเลย์บอล') !== false) {
    $sportIcon = 'fa-solid fa-volleyball';
} elseif (stripos($nameLower, 'เทเบิลเทนนิส') !== false || stripos($nameLower, 'ปิงปอง') !== false) {
    $sportIcon = 'fa-solid fa-table-tennis-paddle-ball';
} elseif (stripos($nameLower, 'แบดมินตัน') !== false) {
    $sportIcon = 'fa-solid fa-dumbbell';
} elseif (stripos($nameLower, 'เปตอง') !== false) {
    $sportIcon = 'fa-solid fa-circle animate-pulse';
}
?>
<div class="glass-card rounded-2xl p-5 hover:border-purple-500/30 hover:shadow-[0_16px_36px_-6px_rgba(168,85,247,0.12)] flex items-center gap-4 group cursor-pointer relative overflow-hidden">
    <!-- Inner gradient glow overlay -->
    <div class="absolute inset-0 bg-gradient-to-br from-purple-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>

    <!-- Icon Container -->
    <div class="w-12 h-12 rounded-xl bg-purple-500/10 border border-purple-500/20 text-purple-400 flex items-center justify-center text-xl shrink-0 group-hover:scale-110 group-hover:rotate-12 group-hover:bg-purple-500/20 transition-all duration-300 shadow-sm relative z-10">
        <i class="<?= $sportIcon ?>"></i>
    </div>
    
    <!-- Text Details -->
    <div class="min-w-0 relative z-10">
        <strong class="block text-white font-bold text-sm truncate font-heading group-hover:text-purple-300 transition-colors duration-200 tracking-wide">
            <?= $sportName ?>
        </strong>
        <span class="text-[11px] text-slate-400 block truncate font-semibold mt-0.5">
            หมวดหมู่: <?= $category ?>
        </span>
    </div>
</div>
