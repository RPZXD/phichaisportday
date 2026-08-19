<?php
/**
 * Modern High-Performance Portal Hub (Landing Page)
 */
$pageTitle = "Pichai Game 2026 - ระบบการแข่งขันกีฬาสีโรงเรียนพิชัย";
$activeRoute = "landing";
$leaderboard = $leaderboard ?? [];
$matches = $matches ?? [];
$sports = $sports ?? [];
$certificatesList = $certificatesList ?? [];
$presenter = $presenter ?? new SportPresenter();

// Quick statistics
$totalMatches = count($matches);
$completedMatches = 0;
foreach ($matches as $m) {
    if ($m['status'] === 'Completed') $completedMatches++;
}
$totalMedals = 0;
foreach ($leaderboard as $row) {
    $totalMedals += ($row['gold_count'] ?? 0) + ($row['silver_count'] ?? 0) + ($row['bronze_count'] ?? 0);
}
$topHouse = !empty($leaderboard) ? $leaderboard[0] : null;
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
            background: rgba(13, 17, 33, 0.55);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.06);
        }
        
        .glass-panel:hover {
            border-color: rgba(255, 255, 255, 0.15);
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-10px) rotate(1deg); }
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        /* Centerpiece & Rings */
        .ring-glow-1 {
            border-color: transparent;
            border-top-color: #2dd4bf;
            border-right-color: #6366f1;
            filter: drop-shadow(0 0 15px rgba(20,184,166,0.35));
        }
        .ring-glow-2 {
            border-color: transparent;
            border-bottom-color: #a855f7;
            border-left-color: #f59e0b;
            filter: drop-shadow(0 0 20px rgba(168,85,247,0.35));
        }
        .ring-glow-3 {
            border-color: transparent;
            border-top-color: #ef4444;
            border-bottom-color: #10b981;
            filter: drop-shadow(0 0 15px rgba(239,68,68,0.35));
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 font-body antialiased selection:bg-amber-500 selection:text-slate-950 min-h-screen flex flex-col justify-between overflow-x-hidden">

    <!-- Header Navigation -->
    <?php include __DIR__ . '/components/header.php'; ?>

    <!-- Mobile Drawer Menu -->
    <div id="drawer-backdrop" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300"></div>
    <aside id="drawer-menu" class="fixed top-0 right-0 z-50 h-full w-80 bg-slate-900 border-l border-slate-800 p-6 shadow-2xl transform translate-x-full transition-transform duration-300 ease-out overflow-y-auto">
        <div class="flex items-center justify-between pb-6 border-b border-slate-800">
            <h2 class="font-heading text-xl font-bold text-white flex items-center gap-2">
                <span class="w-2 h-6 bg-red-500 rounded-full"></span> เมนูหลัก (Menu)
            </h2>
            <button id="drawer-close" class="p-2 text-slate-400 hover:text-white bg-slate-800 rounded-lg cursor-pointer">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <nav class="mt-6 space-y-2 text-sm">
            <a href="index.php?route=landing" class="flex items-center gap-3 px-4 py-3 text-slate-200 hover:bg-slate-800 rounded-xl font-medium transition">
                <i class="fa-solid fa-house text-red-400"></i> หน้าแรก (Home)
            </a>
            <a href="index.php?route=leaderboard" class="flex items-center gap-3 px-4 py-3 text-slate-200 hover:bg-slate-800 rounded-xl font-medium transition">
                <i class="fa-solid fa-trophy text-amber-400"></i> ตารางคะแนนรวม (Scoreboard)
            </a>
            <a href="index.php?route=certificates" class="flex items-center gap-3 px-4 py-3 text-amber-300 bg-amber-500/10 hover:bg-amber-500/20 rounded-xl font-bold transition">
                <i class="fa-solid fa-award text-amber-400"></i> ค้นหาเกียรติบัตร (Certificates)
            </a>
            <a href="index.php?route=schedule" class="flex items-center gap-3 px-4 py-3 text-slate-200 hover:bg-slate-800 rounded-xl font-medium transition">
                <i class="fa-solid fa-calendar-day text-blue-400"></i> ตารางแข่งขันสด (Schedule)
            </a>
            <a href="index.php?route=brackets" class="flex items-center gap-3 px-4 py-3 text-slate-200 hover:bg-slate-800 rounded-xl font-medium transition">
                <i class="fa-solid fa-sitemap text-teal-400"></i> สายการแข่งขัน (Brackets)
            </a>
            <a href="index.php?route=houses" class="flex items-center gap-3 px-4 py-3 text-slate-200 hover:bg-slate-800 rounded-xl font-medium transition">
                <i class="fa-solid fa-flag text-emerald-400"></i> คณะสีทั้งหมด (House Teams)
            </a>
            <a href="index.php?route=login" class="flex items-center gap-3 px-4 py-3 text-white bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl font-bold transition mt-4">
                <i class="fa-solid fa-sign-in"></i> เข้าสู่ระบบ (Sign In)
            </a>
        </nav>
    </aside>

    <main class="flex-grow">
        <!-- ========================================== -->
        <!-- HERO SECTION -->
        <!-- ========================================== -->
        <section class="relative pt-12 pb-16 md:pt-16 md:pb-24 overflow-hidden">
            <!-- Background Orbs -->
            <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] md:w-[700px] md:h-[700px] bg-gradient-to-tr from-teal-500/10 via-indigo-500/15 to-amber-500/10 rounded-full blur-[120px] pointer-events-none"></div>

            <div class="max-w-5xl mx-auto px-4 text-center relative z-10">
                
                <!-- 3D Centerpiece Graphic -->
                <div class="relative w-56 h-56 md:w-80 md:h-80 mx-auto mb-6 flex items-center justify-center select-none animate-float">
                    <div class="absolute inset-4 rounded-full border-2 animate-[spin_6s_linear_infinite] filter blur-[0.5px] ring-glow-1"></div>
                    <div class="absolute inset-8 rounded-full border-2 animate-[spin_8s_linear_infinite_reverse] filter blur-[1px] ring-glow-2"></div>
                    <div class="absolute inset-12 rounded-full border animate-[spin_4s_linear_infinite] filter blur-[0.5px] ring-glow-3"></div>

                    <div class="relative z-10 w-36 h-36 md:w-52 md:h-52 flex flex-col items-center justify-center">
                        <svg viewBox="0 0 100 100" class="w-full h-full filter drop-shadow-[0_0_20px_rgba(20,184,166,0.7)]">
                            <defs>
                                <linearGradient id="neonGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" stop-color="#14b8a6" />
                                    <stop offset="100%" stop-color="#6366f1" />
                                </linearGradient>
                            </defs>
                            <ellipse cx="50" cy="50" rx="44" ry="16" stroke="url(#neonGradient)" stroke-width="1.8" stroke-dasharray="4,6" fill="none" class="animate-[spin_6s_linear_infinite]" style="transform: rotate(35deg); transform-origin: 50px 50px;" />
                            <ellipse cx="50" cy="50" rx="44" ry="16" stroke="#fbbf24" stroke-width="1.2" stroke-dasharray="3,8" fill="none" class="animate-[spin_4s_linear_infinite_reverse]" style="transform: rotate(-45deg); transform-origin: 50px 50px;" />
                            <image href="assets/logo.png" x="5" y="5" width="90" height="90" />
                        </svg>
                    </div>
                </div>

                <!-- Badge -->
                <span class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-500/15 to-purple-500/15 text-indigo-300 border border-indigo-500/30 text-xs font-bold px-4 py-1.5 rounded-full uppercase mb-6 shadow-[0_0_15px_rgba(99,102,241,0.15)]">
                    <i class="fa-solid fa-sparkles text-yellow-400 animate-spin-slow"></i>
                    <span class="tracking-wider font-heading">โรงเรียนพิชัย อุตรดิตถ์</span>
                </span>
                
                <!-- Main Title -->
                <h1 class="text-4xl sm:text-5xl md:text-7xl font-extrabold tracking-tight leading-[1.15] mb-6 bg-gradient-to-r from-white via-slate-100 to-slate-400 bg-clip-text text-transparent font-heading drop-shadow-[0_4px_12px_rgba(0,0,0,0.5)]">
                    การแข่งขันกีฬาสีโรงเรียน<br class="hidden sm:block"> 
                    <span class="bg-gradient-to-r from-yellow-200 via-amber-400 to-orange-500 bg-clip-text text-transparent drop-shadow-[0_0_15px_rgba(245,158,11,0.3)]">ประจำปี 2569</span>
                </h1>
                
                <p class="text-slate-400 text-sm md:text-base max-w-2xl mx-auto mb-10 leading-relaxed font-medium">
                    ศูนย์รวมข้อมูลการแข่งขันกีฬาสีพิชัยเกมส์ 2569 ตรวจสอบตารางคะแนนรวม ตารางการแข่งขันสด ผังสายแข่ง และค้นหาดาวน์โหลดเกียรติบัตรเหรียญรางวัลของนักเรียนได้ทันที
                </p>
                
                <!-- Hero Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3.5 justify-center items-center relative z-20">
                    <a href="index.php?route=certificates" class="w-full sm:w-auto bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-slate-950 font-bold px-8 py-3.5 rounded-2xl shadow-xl shadow-orange-500/25 transition-all duration-300 hover:scale-105 flex items-center justify-center gap-2 text-sm cursor-pointer">
                        <i class="fa-solid fa-award text-base text-slate-950"></i> ค้นหาเกียรติบัตรเหรียญรางวัล
                    </a>

                    <a href="index.php?route=leaderboard" class="w-full sm:w-auto bg-slate-900/90 hover:bg-slate-800 text-white font-bold px-7 py-3.5 rounded-2xl border border-white/10 hover:border-white/20 transition-all duration-300 hover:scale-105 flex items-center justify-center gap-2 text-sm shadow-lg cursor-pointer">
                        <i class="fa-solid fa-ranking-star text-yellow-400"></i> ตารางคะแนนสะสม
                    </a>
                    
                    <a href="index.php?route=schedule" class="w-full sm:w-auto bg-white/5 hover:bg-white/10 text-slate-300 hover:text-white font-bold px-6 py-3.5 rounded-2xl border border-white/10 hover:border-white/20 transition-all duration-300 hover:scale-105 flex items-center justify-center gap-2 text-sm cursor-pointer">
                        <i class="fa-solid fa-calendar-day text-blue-400"></i> ตารางแข่งขันสด
                    </a>
                </div>

            </div>
        </section>

        <!-- ========================================== -->
        <!-- KEY METRICS STATS STRIP -->
        <!-- ========================================== -->
        <section class="max-w-6xl mx-auto px-4 mb-16 relative z-10">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                
                <div class="glass-panel rounded-2xl p-5 text-center transition-all duration-300 hover:-translate-y-1">
                    <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">แมตช์แข่งขันทั้งหมด</div>
                    <div class="text-2xl md:text-3xl font-black bg-gradient-to-r from-blue-400 to-indigo-500 bg-clip-text text-transparent font-heading">
                        <?= $totalMatches ?>
                    </div>
                </div>

                <div class="glass-panel rounded-2xl p-5 text-center transition-all duration-300 hover:-translate-y-1">
                    <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">เสร็จสิ้นแล้ว</div>
                    <div class="text-2xl md:text-3xl font-black bg-gradient-to-r from-emerald-400 to-teal-500 bg-clip-text text-transparent font-heading">
                        <?= $completedMatches ?>
                    </div>
                </div>

                <div class="glass-panel rounded-2xl p-5 text-center transition-all duration-300 hover:-translate-y-1">
                    <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">เหรียญรางวัลรวม</div>
                    <div class="text-2xl md:text-3xl font-black bg-gradient-to-r from-amber-400 to-orange-500 bg-clip-text text-transparent font-heading">
                        <?= $totalMedals ?>
                    </div>
                </div>

                <div class="glass-panel rounded-2xl p-5 text-center transition-all duration-300 hover:-translate-y-1">
                    <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">เกียรติบัตรพร้อมพิมพ์</div>
                    <div class="text-2xl md:text-3xl font-black bg-gradient-to-r from-yellow-300 to-amber-500 bg-clip-text text-transparent font-heading">
                        <?= count($certificatesList) ?>
                    </div>
                </div>

            </div>
        </section>

        <!-- ========================================== -->
        <!-- NAVIGATION HUB CARDS (SECTIONS EXPLORER) -->
        <!-- ========================================== -->
        <section class="max-w-6xl mx-auto px-4 pb-20 relative z-10">
            <div class="text-center mb-12">
                <span class="text-xs uppercase tracking-widest text-indigo-400 font-bold block mb-2">Portal Directory</span>
                <h2 class="text-2xl md:text-4xl font-black font-heading text-white">เลือกดูข้อมูลการแข่งขัน</h2>
                <p class="text-slate-400 text-xs sm:text-sm mt-1">คลิกเลือกเมนูที่ต้องการเพื่อเข้าสู่หน้ารายละเอียดเฉพาะ</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                <!-- 1. ค้นหาเกียรติบัตร Card -->
                <a href="index.php?route=certificates" class="group glass-panel rounded-3xl p-6 transition-all duration-300 hover:-translate-y-1.5 hover:border-amber-500/40 hover:shadow-2xl hover:shadow-amber-500/10 flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-amber-500/15 border border-amber-500/30 flex items-center justify-center text-amber-400 text-xl mb-4 group-hover:scale-110 group-hover:bg-amber-500 group-hover:text-slate-950 transition-all duration-300">
                            <i class="fa-solid fa-award"></i>
                        </div>
                        <span class="text-[10px] uppercase font-bold tracking-widest text-amber-400 bg-amber-500/10 border border-amber-500/20 px-2.5 py-0.5 rounded-full mb-2 inline-block">
                            ดาวน์โหลด & พิมพ์
                        </span>
                        <h3 class="text-xl font-bold text-white group-hover:text-amber-300 transition-colors font-heading mb-2">
                            ค้นหาเกียรติบัตรเหรียญรางวัล
                        </h3>
                        <p class="text-slate-400 text-xs leading-relaxed">
                            ค้นหาเกียรติบัตรผู้ชนะเหรียญทอง เหรียญเงิน เหรียญทองแดง ตามชื่อ-สกุล หรือชนิดกีฬา พร้อมพรีวิวสดและสั่งพิมพ์ A4
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-white/5 flex items-center justify-between text-xs font-bold text-amber-400 group-hover:translate-x-1 transition-transform">
                        <span>เข้าสู่หน้าค้นหาเกียรติบัตร</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </div>
                </a>

                <!-- 2. ตารางคะแนนรวม Card -->
                <a href="index.php?route=leaderboard" class="group glass-panel rounded-3xl p-6 transition-all duration-300 hover:-translate-y-1.5 hover:border-yellow-500/40 hover:shadow-2xl hover:shadow-yellow-500/10 flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-yellow-500/15 border border-yellow-500/30 flex items-center justify-center text-yellow-400 text-xl mb-4 group-hover:scale-110 group-hover:bg-yellow-500 group-hover:text-slate-950 transition-all duration-300">
                            <i class="fa-solid fa-ranking-star"></i>
                        </div>
                        <span class="text-[10px] uppercase font-bold tracking-widest text-yellow-400 bg-yellow-500/10 border border-yellow-500/20 px-2.5 py-0.5 rounded-full mb-2 inline-block">
                            Live Standings
                        </span>
                        <h3 class="text-xl font-bold text-white group-hover:text-yellow-300 transition-colors font-heading mb-2">
                            ตารางคะแนนและอันดับเหรียญ
                        </h3>
                        <p class="text-slate-400 text-xs leading-relaxed">
                            ตารางคะแนนรวมสะสม อันดับเหรียญทอง เหรียญเงิน เหรียญทองแดง และกราฟโพเดียม 3 คณะสีผู้นำ
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-white/5 flex items-center justify-between text-xs font-bold text-yellow-400 group-hover:translate-x-1 transition-transform">
                        <span>ดูตารางคะแนนสะสม</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </div>
                </a>

                <!-- 3. ตารางแข่งขันสด Card -->
                <a href="index.php?route=schedule" class="group glass-panel rounded-3xl p-6 transition-all duration-300 hover:-translate-y-1.5 hover:border-blue-500/40 hover:shadow-2xl hover:shadow-blue-500/10 flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-blue-500/15 border border-blue-500/30 flex items-center justify-center text-blue-400 text-xl mb-4 group-hover:scale-110 group-hover:bg-blue-500 group-hover:text-slate-950 transition-all duration-300">
                            <i class="fa-solid fa-calendar-day"></i>
                        </div>
                        <span class="text-[10px] uppercase font-bold tracking-widest text-blue-400 bg-blue-500/10 border border-blue-500/20 px-2.5 py-0.5 rounded-full mb-2 inline-block">
                            Real-time Matches
                        </span>
                        <h3 class="text-xl font-bold text-white group-hover:text-blue-300 transition-colors font-heading mb-2">
                            ตารางแข่งขันสด & ผลคะแนน
                        </h3>
                        <p class="text-slate-400 text-xs leading-relaxed">
                            รายงานผลการแข่งขันสดติดขอบสนาม แยกหมวดหมู่ตามชนิดกีฬา พร้อมสกอร์และเหรียญรางวัล
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-white/5 flex items-center justify-between text-xs font-bold text-blue-400 group-hover:translate-x-1 transition-transform">
                        <span>ดูตารางแข่งขันสด</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </div>
                </a>

                <!-- 4. สายการแข่งขัน Card -->
                <a href="index.php?route=brackets" class="group glass-panel rounded-3xl p-6 transition-all duration-300 hover:-translate-y-1.5 hover:border-teal-500/40 hover:shadow-2xl hover:shadow-teal-500/10 flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-teal-500/15 border border-teal-500/30 flex items-center justify-center text-teal-400 text-xl mb-4 group-hover:scale-110 group-hover:bg-teal-500 group-hover:text-slate-950 transition-all duration-300">
                            <i class="fa-solid fa-sitemap"></i>
                        </div>
                        <span class="text-[10px] uppercase font-bold tracking-widest text-teal-400 bg-teal-500/10 border border-teal-500/20 px-2.5 py-0.5 rounded-full mb-2 inline-block">
                            Tournament Tree
                        </span>
                        <h3 class="text-xl font-bold text-white group-hover:text-teal-300 transition-colors font-heading mb-2">
                            สายการแข่งขัน (Brackets)
                        </h3>
                        <p class="text-slate-400 text-xs leading-relaxed">
                            ผังการประกบคู่และผลการแข่งขันแบบทัวร์นาเมนต์แพ้คัดออก เลื่อนดูได้ทุกชนิดกีฬา
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-white/5 flex items-center justify-between text-xs font-bold text-teal-400 group-hover:translate-x-1 transition-transform">
                        <span>ดูผังสายการแข่งขัน</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </div>
                </a>

                <!-- 5. คณะสีทั้งหมด Card -->
                <a href="index.php?route=houses" class="group glass-panel rounded-3xl p-6 transition-all duration-300 hover:-translate-y-1.5 hover:border-emerald-500/40 hover:shadow-2xl hover:shadow-emerald-500/10 flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-emerald-500/15 border border-emerald-500/30 flex items-center justify-center text-emerald-400 text-xl mb-4 group-hover:scale-110 group-hover:bg-emerald-500 group-hover:text-slate-950 transition-all duration-300">
                            <i class="fa-solid fa-flag"></i>
                        </div>
                        <span class="text-[10px] uppercase font-bold tracking-widest text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-0.5 rounded-full mb-2 inline-block">
                            House Teams
                        </span>
                        <h3 class="text-xl font-bold text-white group-hover:text-emerald-300 transition-colors font-heading mb-2">
                            คณะสีที่ร่วมประชันชัย
                        </h3>
                        <p class="text-slate-400 text-xs leading-relaxed">
                            ข้อมูล 6 คณะสี สัญลักษณ์ประจำสี สีประจำคณะ และสรุปยอดเหรียญรางวัลของแต่ละทีม
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-white/5 flex items-center justify-between text-xs font-bold text-emerald-400 group-hover:translate-x-1 transition-transform">
                        <span>ดูรายชื่อคณะสี</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </div>
                </a>

                <!-- 6. เข้าสู่ระบบนักกีฬา / อาจารย์ Card -->
                <a href="index.php?route=login" class="group glass-panel rounded-3xl p-6 transition-all duration-300 hover:-translate-y-1.5 hover:border-indigo-500/40 hover:shadow-2xl hover:shadow-indigo-500/10 flex flex-col justify-between bg-gradient-to-b from-indigo-950/20 to-slate-900/50">
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-indigo-500/15 border border-indigo-500/30 flex items-center justify-center text-indigo-400 text-xl mb-4 group-hover:scale-110 group-hover:bg-indigo-500 group-hover:text-white transition-all duration-300">
                            <i class="fa-solid fa-user-shield"></i>
                        </div>
                        <span class="text-[10px] uppercase font-bold tracking-widest text-indigo-400 bg-indigo-500/10 border border-indigo-500/20 px-2.5 py-0.5 rounded-full mb-2 inline-block">
                            Portal Login
                        </span>
                        <h3 class="text-xl font-bold text-white group-hover:text-indigo-300 transition-colors font-heading mb-2">
                            เข้าสู่ระบบนักเรียน / อาจารย์
                        </h3>
                        <p class="text-slate-400 text-xs leading-relaxed">
                            ระบบบันทึกผลการแข่งขัน ลงทะเบียนนักกีฬา และระบบจัดการเกียรติบัตรสำหรับครูผู้ดูแล
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-white/5 flex items-center justify-between text-xs font-bold text-indigo-400 group-hover:translate-x-1 transition-transform">
                        <span>เข้าสู่ระบบ</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </div>
                </a>

            </div>
        </section>
    </main>

    <!-- Mobile Bottom Nav -->
    <nav class="fixed bottom-0 left-0 right-0 z-40 bg-slate-950/90 backdrop-blur-lg border-t border-slate-800 px-4 py-2 md:hidden shadow-2xl">
        <div class="flex items-center justify-around">
            <a href="index.php?route=landing" class="flex flex-col items-center gap-1 text-red-500 font-medium text-[10px]">
                <i class="fa-solid fa-house text-lg"></i>
                <span>หน้าแรก</span>
            </a>
            <a href="index.php?route=leaderboard" class="flex flex-col items-center gap-1 text-slate-400 hover:text-amber-400 font-medium text-[10px] transition">
                <i class="fa-solid fa-trophy text-lg"></i>
                <span>คะแนน</span>
            </a>
            <a href="index.php?route=certificates" class="flex flex-col items-center gap-1 text-amber-400 font-bold text-[10px] transition">
                <i class="fa-solid fa-award text-lg"></i>
                <span>เกียรติบัตร</span>
            </a>
            <a href="index.php?route=schedule" class="flex flex-col items-center gap-1 text-slate-400 hover:text-blue-400 font-medium text-[10px] transition">
                <i class="fa-solid fa-calendar-day text-lg"></i>
                <span>ตารางแข่ง</span>
            </a>
            <a href="index.php?route=brackets" class="flex flex-col items-center gap-1 text-slate-400 hover:text-teal-400 font-medium text-[10px] transition">
                <i class="fa-solid fa-sitemap text-lg"></i>
                <span>สายแข่ง</span>
            </a>
        </div>
    </nav>

    <!-- Drawer Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const drawerToggle = document.getElementById('drawer-toggle');
            const drawerClose = document.getElementById('drawer-close');
            const drawerMenu = document.getElementById('drawer-menu');
            const drawerBackdrop = document.getElementById('drawer-backdrop');

            function openDrawer() {
                if (drawerMenu && drawerBackdrop) {
                    drawerMenu.classList.remove('translate-x-full');
                    drawerBackdrop.classList.remove('opacity-0', 'pointer-events-none');
                }
            }

            function closeDrawer() {
                if (drawerMenu && drawerBackdrop) {
                    drawerMenu.classList.add('translate-x-full');
                    drawerBackdrop.classList.add('opacity-0', 'pointer-events-none');
                }
            }

            if (drawerToggle) drawerToggle.addEventListener('click', openDrawer);
            if (drawerClose) drawerClose.addEventListener('click', closeDrawer);
            if (drawerBackdrop) drawerBackdrop.addEventListener('click', closeDrawer);
        });
    </script>

    <?php include __DIR__ . '/components/footer.php'; ?>
</body>
</html>
