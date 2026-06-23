<?php
/**
 * Global Header Navigation Component
 * Context-aware: Adjusts layout for guests, students, and teachers.
 */
$isLoggedIn = isset($_SESSION['user']);
$userRole = $isLoggedIn ? $_SESSION['user']['role'] : null;
$userName = $isLoggedIn ? $_SESSION['user']['name'] : null;

// Determine path prefixes for anchors based on whether we are on the landing page
$isOnLanding = isset($route) && $route === 'landing';
$pathPrefix = $isOnLanding ? '' : 'index.php';
?>
<header class="app-header sticky top-0 z-50 bg-[#070913]/70 backdrop-blur-xl border-b border-white/5 shadow-2xl transition-all duration-300">
    <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
        <!-- Brand Logo -->
        <a href="index.php" class="brand-logo text-xl md:text-2xl font-black flex items-center gap-2 hover:scale-105 transition-all duration-300 font-heading select-none">
            <i class="fa-solid fa-trophy text-[#d4af37] drop-shadow-[0_0_10px_rgba(212,175,55,0.5)]"></i>
            <span class="bg-gradient-to-r from-white via-slate-100 to-slate-400 bg-clip-text text-transparent">Phichai Game 2026</span>
        </a>

        <!-- Navigation / Action Controls -->
        <?php if ($isLoggedIn): ?>
            <!-- Logged-in State (Dashboard Mode) -->
            <div class="flex items-center gap-4">
                <?php if ($userRole === 'teacher'): ?>
                    <span class="hidden sm:inline-flex bg-gradient-to-r from-indigo-500/15 to-purple-600/15 text-indigo-300 border border-indigo-500/25 text-xs font-bold px-4 py-1.5 rounded-full shadow-md select-none animate-float-badge">
                        <i class="fa-solid fa-user-shield mr-1.5"></i>ระบบจัดการอาจารย์
                    </span>
                <?php else: ?>
                    <span class="hidden sm:inline-flex bg-gradient-to-r from-indigo-500/15 to-purple-600/15 text-indigo-300 border border-indigo-500/25 text-xs font-bold px-4 py-1.5 rounded-full shadow-md select-none animate-float-badge">
                        <i class="fa-solid fa-user-tag mr-1.5"></i>ระบบนักเรียนนักกีฬา
                    </span>
                <?php endif; ?>
                
                <div class="flex items-center gap-3 pl-3 border-l border-white/10">
                    <?php if ($userName): ?>
                        <span class="hidden md:inline text-xs text-slate-300 font-bold bg-white/5 border border-white/5 px-3.5 py-1.5 rounded-xl">
                            <i class="fa-regular fa-user mr-1.5 text-indigo-400"></i><?= htmlspecialchars($userName) ?>
                        </span>
                    <?php endif; ?>
                    <a href="index.php?route=login&action=logout" class="bg-rose-500/10 hover:bg-rose-500/20 text-rose-300 border border-rose-500/20 hover:border-rose-500/30 font-bold px-4 py-2 rounded-xl text-xs transition-all duration-200 cursor-pointer shadow-md">
                        <i class="fa-solid fa-arrow-right-from-bracket mr-1"></i>ออกจากระบบ
                    </a>
                </div>
            </div>
        <?php else: ?>
            <!-- Guest State (Landing Page Mode) -->
            <nav class="hidden lg:flex items-center gap-8 font-semibold">
                <a href="<?= $pathPrefix ?>#houses" class="text-slate-300 hover:text-white transition-colors duration-200 nav-link-glow">คณะสี</a>
                <a href="<?= $pathPrefix ?>#leaderboard" class="text-slate-300 hover:text-white transition-colors duration-200 nav-link-glow">ตารางคะแนน</a>
                <a href="<?= $pathPrefix ?>#brackets" class="text-slate-300 hover:text-white transition-colors duration-200 nav-link-glow">สายการแข่ง</a>
                <a href="<?= $pathPrefix ?>#results" class="text-slate-300 hover:text-white transition-colors duration-200 nav-link-glow">ผลการแข่ง</a>
                <a href="<?= $pathPrefix ?>#sports" class="text-slate-300 hover:text-white transition-colors duration-200 nav-link-glow">ชนิดกีฬา</a>
                <a href="index.php?route=login" class="bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-bold px-6 py-2 rounded-xl shadow-lg hover:shadow-indigo-500/30 transition-all duration-300 hover:-translate-y-0.5 select-none cursor-pointer">
                    <i class="fa-solid fa-sign-in mr-1.5"></i>เข้าสู่ระบบ
                </a>
            </nav>
            <!-- Mobile Sign In Button -->
            <a href="index.php?route=login" class="lg:hidden bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-bold px-4 py-2 rounded-xl text-xs shadow-md transition-transform active:scale-95">
                <i class="fa-solid fa-sign-in mr-1"></i>เข้าสู่ระบบ
            </a>
        <?php endif; ?>
    </div>
</header>
