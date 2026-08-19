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
        <!-- Brand Logo & Live Badge -->
        <a href="index.php" class="brand-logo text-xl md:text-2xl font-black flex items-center gap-2 hover:scale-105 transition-all duration-300 font-heading select-none">
            <i class="fa-solid fa-trophy text-[#d4af37] drop-shadow-[0_0_10px_rgba(212,175,55,0.5)]"></i>
            <span class="bg-gradient-to-r from-white via-slate-100 to-slate-400 bg-clip-text text-transparent">Phichai Game 2026</span>
            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-500/20 text-red-400 border border-red-500/30">
              <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-ping"></span>
              LIVE
            </span>
        </a>

        <!-- Navigation / Action Controls -->
        <?php if ($isLoggedIn): ?>
            <!-- Logged-in State (Dashboard Mode) -->
            <div class="flex items-center gap-4">
                <nav class="hidden md:flex items-center gap-4 mr-2 font-bold text-xs select-none">
                    <a href="index.php?route=landing" class="px-3 py-1.5 rounded-lg text-slate-300 hover:text-white hover:bg-white/5 transition-all <?= (isset($route) && $route === 'landing') ? 'bg-white/5 text-white' : '' ?>">
                        <i class="fa-solid fa-house mr-1 text-teal-400"></i>หน้าหลัก
                    </a>
                    <a href="index.php?route=dashboard" class="px-3 py-1.5 rounded-lg text-slate-300 hover:text-white hover:bg-white/5 transition-all <?= (!isset($route) || $route === 'dashboard') ? 'bg-white/5 text-white' : '' ?>">
                        <i class="fa-solid fa-gauge mr-1"></i>แดชบอร์ด
                    </a>
                    <?php if ($userRole === 'teacher'): ?>
                        <a href="index.php?route=teacher_certificate" class="px-3 py-1.5 rounded-lg text-slate-300 hover:text-white hover:bg-white/5 transition-all <?= (isset($route) && $route === 'teacher_certificate') ? 'bg-white/5 text-white' : '' ?>">
                            <i class="fa-solid fa-certificate mr-1 text-[#d4af37]"></i>ออกแบบเกียรติบัตร
                        </a>
                    <?php endif; ?>
                </nav>

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
            <!-- Guest State (Public Mode) -->
            <?php
            $curRoute = $activeRoute ?? ($route ?? 'landing');
            ?>
            <nav class="hidden lg:flex items-center gap-5 font-semibold text-xs select-none">
                <a href="index.php?route=landing" class="transition-colors duration-200 py-1 px-2.5 rounded-lg <?= ($curRoute === 'landing') ? 'text-white bg-white/10 font-bold' : 'text-slate-300 hover:text-white hover:bg-white/5' ?>">
                    หน้าแรก
                </a>
                <a href="index.php?route=leaderboard" class="transition-colors duration-200 py-1 px-2.5 rounded-lg <?= ($curRoute === 'leaderboard') ? 'text-amber-300 bg-amber-500/15 font-bold' : 'text-slate-300 hover:text-white hover:bg-white/5' ?>">
                    ตารางคะแนน
                </a>
                <a href="index.php?route=schedule" class="transition-colors duration-200 py-1 px-2.5 rounded-lg <?= ($curRoute === 'schedule') ? 'text-blue-300 bg-blue-500/15 font-bold' : 'text-slate-300 hover:text-white hover:bg-white/5' ?>">
                    ตารางแข่งสด
                </a>
                <a href="index.php?route=brackets" class="transition-colors duration-200 py-1 px-2.5 rounded-lg <?= ($curRoute === 'brackets') ? 'text-teal-300 bg-teal-500/15 font-bold' : 'text-slate-300 hover:text-white hover:bg-white/5' ?>">
                    สายการแข่ง
                </a>
                <a href="index.php?route=houses" class="transition-colors duration-200 py-1 px-2.5 rounded-lg <?= ($curRoute === 'houses') ? 'text-emerald-300 bg-emerald-500/15 font-bold' : 'text-slate-300 hover:text-white hover:bg-white/5' ?>">
                    คณะสี
                </a>
                <a href="index.php?route=certificates" class="py-1 px-3 rounded-lg flex items-center gap-1.5 font-bold shadow-sm transition-all <?= ($curRoute === 'certificates') ? 'bg-gradient-to-r from-amber-500 to-orange-600 text-white shadow-orange-500/20' : 'text-amber-300 hover:text-amber-200 hover:bg-amber-500/10 border border-amber-500/30' ?>">
                    <i class="fa-solid fa-award text-amber-400"></i>ค้นหาเกียรติบัตร
                </a>
                <a href="index.php?route=login" class="bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-bold px-4 py-2 rounded-xl shadow-lg hover:shadow-indigo-500/30 transition-all duration-300 hover:-translate-y-0.5 select-none cursor-pointer text-xs">
                    <i class="fa-solid fa-sign-in mr-1.5"></i>เข้าสู่ระบบ
                </a>
            </nav>
            <div class="flex items-center gap-2 lg:hidden">
                <!-- Mobile Sign In Button -->
                <a href="index.php?route=login" class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-bold px-3 py-2 rounded-xl text-xs shadow-md transition-transform active:scale-95">
                    <i class="fa-solid fa-sign-in mr-1"></i>เข้าสู่ระบบ
                </a>
                <!-- Mobile Menu Drawer Button -->
                <button id="drawer-toggle" aria-label="Toggle Menu" class="p-2 text-slate-300 hover:text-white bg-slate-900 border border-slate-800 rounded-xl hover:bg-slate-800 transition cursor-pointer">
                    <i class="fa-solid fa-bars text-base"></i>
                </button>
            </div>
        <?php endif; ?>
    </div>
</header>
