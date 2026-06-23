<!DOCTYPE html>
<html lang="th" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pichai Game 2026 - ระบบการแข่งขันกีฬาสีโรงเรียนพิชัย</title>
    <!-- Tailwind CSS v4 Browser CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- Font Awesome v6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/style.css">
    <style type="text/tailwindcss">
        @theme {
            --font-heading: 'Itim', cursive;
            --font-body: 'Mali', cursive;
        }

        @keyframes pulseSlow {
            0%, 100% { transform: scale(1) translate(0px, 0px); opacity: 0.5; }
            50% { transform: scale(1.15) translate(15px, -20px); opacity: 0.8; }
        }
        @keyframes pulseSlowReverse {
            0%, 100% { transform: scale(1.1) translate(0px, 0px); opacity: 0.7; }
            50% { transform: scale(0.9) translate(-20px, 20px); opacity: 0.45; }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-12px) rotate(1.5deg); }
        }
        .animate-pulse-slow {
            animation: pulseSlow 10s ease-in-out infinite;
        }
        .animate-pulse-slow-reverse {
            animation: pulseSlowReverse 12s ease-in-out infinite;
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        
        /* Glassmorphism card utility */
        .glass-panel {
            background: rgba(13, 17, 33, 0.45);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .glass-panel:hover {
            border-color: rgba(255, 255, 255, 0.1);
        }

        /* Centerpiece Styles Transition */
        .style-switch-item {
            transition: all 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: absolute;
            inset: 0;
            opacity: 0;
            transform: scale(0.7) rotate(-25deg);
            pointer-events: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .style-switch-item.active {
            opacity: 1;
            transform: scale(1) rotate(0deg);
            pointer-events: auto;
        }

        /* Color overrides for container classes */
        .ring-glow-1 { transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1); }
        .ring-glow-2 { transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1); }
        .ring-glow-3 { transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1); }
        .radial-glow-back { transition: all 1s ease; }
        .central-emblem-box {
            transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
            backdrop-filter: none !important;
            -webkit-backdrop-filter: none !important;
        }
        .wordmark-text { transition: all 0.6s ease; }

        /* Football Theme (default) */
        .style-football .radial-glow-back {
            background: radial-gradient(circle, rgba(20,184,166,0.2) 0%, rgba(99,102,241,0.1) 50%, rgba(234,179,8,0.2) 100%);
        }
        .style-football .ring-glow-1 {
            border-color: transparent;
            border-top-color: #2dd4bf; /* teal-400 */
            border-right-color: #6366f1; /* indigo-500 */
            filter: drop-shadow(0 0 15px rgba(20,184,166,0.35));
        }
        .style-football .ring-glow-2 {
            border-color: transparent;
            border-bottom-color: #a855f7; /* purple-500 */
            border-left-color: #f59e0b; /* amber-500 */
            filter: drop-shadow(0 0 20px rgba(168,85,247,0.35));
        }
        .style-football .ring-glow-3 {
            border-color: transparent;
            border-top-color: #fbbf24; /* amber-400 */
            filter: drop-shadow(0 0 10px rgba(245,158,11,0.25));
        }
        .style-football .central-emblem-box {
            border-color: rgba(255,255,255,0.08);
            box-shadow: 0 0 50px rgba(20,184,166,0.15);
        }
        .style-football .central-emblem-box:hover {
            border-color: rgba(20,184,166,0.35);
        }
        .style-football .wordmark-text {
            background-image: linear-gradient(to right, #2dd4bf, #818cf8, #fcd34d);
        }

        /* Torch Theme */
        .style-torch .radial-glow-back {
            background: radial-gradient(circle, rgba(239,68,68,0.25) 0%, rgba(249,115,22,0.15) 50%, rgba(234,179,8,0.15) 100%);
        }
        .style-torch .ring-glow-1 {
            border-color: transparent;
            border-top-color: #fb923c; /* orange-400 */
            border-right-color: #ef4444; /* red-500 */
            filter: drop-shadow(0 0 15px rgba(249,115,22,0.45));
        }
        .style-torch .ring-glow-2 {
            border-color: transparent;
            border-bottom-color: #fbbf24; /* amber-400 */
            border-left-color: #f43f5e; /* rose-500 */
            filter: drop-shadow(0 0 20px rgba(245,158,11,0.45));
        }
        .style-torch .ring-glow-3 {
            border-color: transparent;
            border-top-color: #ef4444; /* red-500 */
            filter: drop-shadow(0 0 10px rgba(239,68,68,0.35));
        }
        .style-torch .central-emblem-box {
            border-color: rgba(255,255,255,0.08);
            box-shadow: 0 0 50px rgba(249,115,22,0.2);
        }
        .style-torch .central-emblem-box:hover {
            border-color: rgba(249,115,22,0.35);
        }
        .style-torch .wordmark-text {
            background-image: linear-gradient(to right, #f97316, #ef4444, #facc15);
        }

        /* Shield Theme (Hybrid Torch Shield) */
        .style-shield .radial-glow-back {
            background: radial-gradient(circle, rgba(236,72,153,0.25) 0%, rgba(168,85,247,0.15) 50%, rgba(249,115,22,0.2) 100%);
        }
        .style-shield .ring-glow-1 {
            border-color: transparent;
            border-top-color: #ec4899; /* pink-500 */
            border-right-color: #f97316; /* orange-500 */
            filter: drop-shadow(0 0 15px rgba(236,72,153,0.45));
        }
        .style-shield .ring-glow-2 {
            border-color: transparent;
            border-bottom-color: #a855f7; /* purple-500 */
            border-left-color: #fbbf24; /* amber-400 */
            filter: drop-shadow(0 0 20px rgba(168,85,247,0.45));
        }
        .style-shield .ring-glow-3 {
            border-color: transparent;
            border-top-color: #ec4899; /* pink-500 */
            filter: drop-shadow(0 0 10px rgba(236,72,153,0.35));
        }
        .style-shield .central-emblem-box {
            border-color: rgba(255,255,255,0.08);
            box-shadow: 0 0 50px rgba(236,72,153,0.2);
        }
        .style-shield .central-emblem-box:hover {
            border-color: rgba(236,72,153,0.35);
        }
        .style-shield .wordmark-text {
            background-image: linear-gradient(to right, #ec4899, #a855f7, #f97316);
        }
    </style>
</head>
<body class="text-slate-100 font-body min-h-screen relative overflow-x-hidden">

<?php include __DIR__ . '/components/ambient_orbs.php'; ?>
<?php include __DIR__ . '/components/header.php'; ?>

<!-- Hero Section -->
<div class="max-w-6xl mx-auto text-center px-4 py-12 md:py-20 relative z-10 select-none overflow-hidden rounded-[40px]">
    
    <div class="hero-gradient-glow"></div>
    
    <div class="absolute inset-0 pointer-events-none -z-5 overflow-hidden">
        <div class="absolute bottom-[-10px] left-[15%] w-3 h-3 bg-gradient-to-tr from-amber-500 to-yellow-300 rounded-full blur-[1px] shadow-[0_0_12px_#f59e0b] opacity-0 animate-[floatUp_7s_infinite_ease-in-out]"></div>
        <div class="absolute bottom-[-10px] left-[40%] w-2 h-2 bg-gradient-to-tr from-orange-500 to-yellow-400 rounded-full blur-[1px] shadow-[0_0_8px_#ea580c] opacity-0 animate-[floatUp_5s_infinite_ease-in-out_1s]"></div>
        <div class="absolute bottom-[-10px] left-[65%] w-4 h-4 bg-gradient-to-tr from-amber-600 to-orange-400 rounded-full blur-[2px] shadow-[0_0_15px_#d97706] opacity-0 animate-[floatUp_9s_infinite_ease-in-out_2s]"></div>
        <div class="absolute bottom-[-10px] left-[85%] w-2.5 h-2.5 bg-gradient-to-tr from-yellow-500 to-amber-300 rounded-full blur-[1px] shadow-[0_0_10px_#eab308] opacity-0 animate-[floatUp_6s_infinite_ease-in-out_3.5s]"></div>
        <div class="absolute bottom-[-10px] left-[25%] w-3.5 h-3.5 bg-gradient-to-tr from-rose-500 to-orange-400 rounded-full blur-[1.5px] shadow-[0_0_14px_#f43f5e] opacity-0 animate-[floatUp_8s_infinite_ease-in-out_1.5s]"></div>
        <div class="absolute bottom-[-10px] left-[55%] w-2 h-2 bg-gradient-to-tr from-amber-400 to-yellow-200 rounded-full blur-[1px] shadow-[0_0_8px_#fbbf24] opacity-0 animate-[floatUp_4.5s_infinite_ease-in-out_2.5s]"></div>
    </div>
    
    <div id="hero-centerpiece-container" class="relative w-full h-[480px] sm:h-[550px] md:h-[680px] mx-auto mb-4 flex items-center justify-center select-none overflow-visible animate-hero-fade-in-up delay-100 animate-float style-football z-0">
        <canvas id="hero-particles-canvas" class="absolute inset-0 w-full h-full pointer-events-none z-0"></canvas>

        <div class="absolute w-[400px] h-[400px] md:w-[650px] md:h-[650px] rounded-full blur-[90px] md:blur-[130px] animate-pulse radial-glow-back opacity-65"></div>

        <div class="absolute inset-0 rounded-full border border-dashed border-teal-500/10 animate-[spin_50s_linear_infinite]"></div>
        <div class="absolute inset-6 rounded-full border border-dashed border-amber-500/15 animate-[spin_35s_linear_infinite_reverse]"></div>
        <div class="absolute inset-12 rounded-full border border-white/5 animate-[spin_20s_linear_infinite]"></div>

        <div class="absolute inset-20 rounded-full border-2 animate-[spin_5s_linear_infinite] filter blur-[0.5px] ring-glow-1"></div>
        <div class="absolute inset-24 rounded-full border-2 animate-[spin_7s_linear_infinite_reverse] filter blur-[1px] ring-glow-2"></div>
        <div class="absolute inset-28 rounded-full border animate-[spin_3s_linear_infinite] filter blur-[0.5px] ring-glow-3"></div>

        <div class="relative z-10 w-80 h-80 md:w-[420px] md:h-[420px] flex flex-col items-center justify-center group hover:scale-105 transition-all duration-700 ease-out central-emblem-box">

            <div class="relative flex flex-col items-center justify-center select-none text-center p-2 w-full h-full">
                <div class="relative w-64 h-64 md:w-80 md:h-80 flex items-center justify-center overflow-visible">
                    
                    <div id="svg-hero-football" class="style-switch-item active w-full h-full">
                        <svg viewBox="0 0 100 100" class="w-full h-full filter drop-shadow-[0_0_25px_rgba(20,184,166,0.8)] group-hover:scale-105 transition-transform duration-500">
                            <defs>
                                <linearGradient id="neonGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" stop-color="#14b8a6" />
                                    <stop offset="100%" stop-color="#6366f1" />
                                </linearGradient>
                                <linearGradient id="ballGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" stop-color="#ffffff" />
                                    <stop offset="100%" stop-color="#cbd5e1" />
                                </linearGradient>
                            </defs>

                            <ellipse cx="50" cy="50" rx="44" ry="16" stroke="url(#neonGradient)" stroke-width="1.8" stroke-dasharray="4,6" fill="none" class="animate-[spin_6s_linear_infinite]" style="transform: rotate(35deg); transform-origin: 50px 50px;" />
                            <ellipse cx="50" cy="50" rx="44" ry="16" stroke="#fbbf24" stroke-width="1.2" stroke-dasharray="3,8" fill="none" class="animate-[spin_4s_linear_infinite_reverse]" style="transform: rotate(-45deg); transform-origin: 50px 50px;" />
                            <ellipse cx="50" cy="50" rx="46" ry="18" stroke="url(#neonGradient)" stroke-width="1" stroke-dasharray="2,10" fill="none" class="animate-[spin_10s_linear_infinite]" style="transform: rotate(90deg); transform-origin: 50px 50px;" />
                            
                            <circle cx="50" cy="50" r="42" stroke="url(#neonGradient)" stroke-width="1" stroke-dasharray="1,15" fill="none" class="animate-[spin_15s_linear_infinite]" style="transform-origin: 50px 50px;" />

                            <image href="assets/logo.png" x="0" y="0" width="100" height="100" />
                        </svg>
                    </div>
                    
                </div>
                <span class="text-base md:text-lg font-black tracking-[0.4em] text-white/95 uppercase font-heading mt-6 bg-gradient-to-r bg-clip-text text-transparent filter drop-shadow-[0_2px_4px_rgba(0,0,0,0.5)] wordmark-text">Phichai Game 2026</span>
            </div>
        </div>
    </div>

    <!-- Hero Style Switcher Panel -->


    <span class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-500/10 to-purple-500/10 text-indigo-300 border border-indigo-500/30 text-xs font-bold px-4 py-1.5 rounded-full uppercase mb-8 shadow-[0_0_15px_rgba(99,102,241,0.15)] relative overflow-hidden group animate-hero-fade-in-up delay-100 z-10">
        <span class="absolute inset-0 w-1/2 h-full bg-white/10 skew-x-12 -translate-x-full group-hover:animate-[shimmer_1.5s_ease-in-out_infinite]"></span>
        <i class="fa-solid fa-sparkles text-yellow-400 drop-shadow-[0_0_4px_rgba(234,179,8,0.6)] animate-spin-slow"></i> 
        <span class="tracking-wider font-heading">โรงเรียนพิชัย อุตรดิตถ์</span>
    </span>
    
    <h1 class="text-4xl sm:text-5xl md:text-7xl font-extrabold tracking-tight leading-[1.15] mb-8 bg-gradient-to-r from-white via-slate-200 to-slate-400 bg-[length:200%_auto] animate-[shine_5s_linear_infinite] bg-clip-text text-transparent font-heading drop-shadow-[0_4px_12px_rgba(0,0,0,0.5)] animate-hero-fade-in-up delay-300 z-10">
        การแข่งขันกีฬาสีโรงเรียน<br class="hidden sm:block"> 
        <span class="bg-gradient-to-r from-yellow-200 via-amber-400 to-orange-500 bg-clip-text text-transparent drop-shadow-[0_0_15px_rgba(245,158,11,0.2)]">ประจำปี 2569</span>
    </h1>
    
    <p class="text-slate-400 text-base md:text-lg max-w-2xl mx-auto mb-12 leading-relaxed font-medium animate-hero-fade-in-up delay-500 z-10">
        ร่วมเป็นส่วนหนึ่งของชัยชนะและเกียรติยศ ติดตามผลการแข่งขันแบบเรียลไทม์ ตารางคะแนนสรุปอันดับเหรียญทอง และดาวน์โหลดเกียรติบัตรเหรียญรางวัลของนักเรียนได้ทันที!
    </p>
    
    <div class="flex flex-col sm:flex-row gap-5 justify-center items-center relative z-20 animate-hero-fade-in-up delay-700">
        <div class="relative w-full sm:w-auto group">
            <div class="absolute -inset-0.5 bg-gradient-to-r from-indigo-500 via-purple-600 to-pink-500 rounded-2xl blur-md opacity-60 group-hover:opacity-100 transition duration-500 group-hover:duration-200 animate-pulse"></div>
            <a href="#leaderboard" class="relative w-full sm:w-auto block text-center bg-slate-950 text-white font-bold px-8 py-4 rounded-2xl transition-all duration-300 group-hover:bg-transparent group-hover:scale-[1.02]">
                <i class="fa-solid fa-ranking-star mr-2 text-yellow-400 group-hover:animate-bounce"></i>ดูตารางคะแนนสะสม
            </a>
        </div>
        
        <a href="index.php?route=login" class="w-full sm:w-auto bg-white/5 hover:bg-white/10 text-slate-200 hover:text-white font-bold px-8 py-4 rounded-2xl border border-white/10 hover:border-white/20 transition-all duration-300 hover:scale-[1.02] hover:shadow-[0_0_20px_rgba(255,255,255,0.05)] flex items-center justify-center gap-2">
            <i class="fa-solid fa-user-shield text-indigo-400"></i>เข้าสู่ระบบนักกีฬา / ผู้ดูแล
        </a>
    </div>
</div>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-6 max-w-5xl mx-auto px-4 mb-24 relative z-10">
    <div class="glass-panel rounded-3xl p-6 text-center hover:scale-102 hover:shadow-[0_12px_30px_rgba(99,102,241,0.08)] transition-all duration-300 relative overflow-hidden group select-none">
        <div class="absolute -right-4 -top-4 w-12 h-12 bg-indigo-500/5 rounded-full blur-lg group-hover:scale-150 transition-transform"></div>
        <div class="text-3xl md:text-4xl font-black bg-gradient-to-r from-indigo-400 to-purple-500 bg-clip-text text-transparent mb-1 font-heading"><?= count($leaderboard) ?></div>
        <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">คณะสีที่เข้าร่วม</div>
    </div>
    <div class="glass-panel rounded-3xl p-6 text-center hover:scale-102 hover:shadow-[0_12px_30px_rgba(99,102,241,0.08)] transition-all duration-300 relative overflow-hidden group select-none">
        <div class="absolute -right-4 -top-4 w-12 h-12 bg-indigo-500/5 rounded-full blur-lg group-hover:scale-150 transition-transform"></div>
        <div class="text-3xl md:text-4xl font-black bg-gradient-to-r from-indigo-400 to-purple-500 bg-clip-text text-transparent mb-1 font-heading"><?= count($matches) ?></div>
        <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">แมตช์แข่งขันทั้งหมด</div>
    </div>
    <div class="glass-panel rounded-3xl p-6 text-center hover:scale-102 hover:shadow-[0_12px_30px_rgba(99,102,241,0.08)] transition-all duration-300 relative overflow-hidden group select-none">
        <div class="absolute -right-4 -top-4 w-12 h-12 bg-indigo-500/5 rounded-full blur-lg group-hover:scale-150 transition-transform"></div>
        <div class="text-3xl md:text-4xl font-black bg-gradient-to-r from-indigo-400 to-purple-500 bg-clip-text text-transparent mb-1 font-heading">
            <?php
            $completed = 0;
            foreach ($matches as $m) {
                if ($m['status'] === 'Completed') $completed++;
            }
            echo $completed;
            ?>
        </div>
        <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">เสร็จสิ้นแล้ว</div>
    </div>
    <div class="glass-panel rounded-3xl p-6 text-center hover:scale-102 hover:shadow-[0_12px_30px_rgba(99,102,241,0.08)] transition-all duration-300 relative overflow-hidden group select-none">
        <div class="absolute -right-4 -top-4 w-12 h-12 bg-indigo-500/5 rounded-full blur-lg group-hover:scale-150 transition-transform"></div>
        <div class="text-3xl md:text-4xl font-black bg-gradient-to-r from-indigo-400 to-purple-500 bg-clip-text text-transparent mb-1 font-heading">
            <?php
            $medals = 0;
            foreach ($leaderboard as $row) {
                $medals += $row['gold_count'] + $row['silver_count'] + $row['bronze_count'];
            }
            echo $medals;
            ?>
        </div>
        <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">เหรียญรางวัลรวม</div>
    </div>
</div>

<!-- Houses Showcase Section -->
<section id="houses" class="py-20 bg-white/[0.01] border-t border-white/5 relative z-10">
    <div class="max-w-6xl mx-auto px-4">
        <div class="text-center mb-16">
            <span class="text-xs uppercase tracking-widest text-indigo-400 font-bold block mb-2">Competing Houses</span>
            <h2 class="text-3xl md:text-4xl font-black font-heading text-white">คณะสีที่ร่วมประชันชัย</h2>
            <p class="text-slate-400 text-sm mt-1">รายชื่อคณะสีและสัญลักษณ์ประจำสีในการแข่งขันกีฬาโรงเรียนพิชัย</p>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
            <?php foreach ($leaderboard as $row): ?>
                <?php include __DIR__ . '/components/house_card.php'; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Leaderboard Standings Section -->
<section id="leaderboard" class="py-20 max-w-4xl mx-auto px-4 relative z-10">
    <div class="text-center mb-16">
        <span class="text-xs uppercase tracking-widest text-indigo-400 font-bold block mb-2">Live Scoreboard</span>
        <h2 class="text-3xl md:text-4xl font-black font-heading text-white">ตารางคะแนนและอันดับเหรียญรางวัลล่าสุด</h2>
        <p class="text-slate-400 text-sm mt-1">สรุปอันดับเหรียญรางวัลสะสมสูงสุดของแต่ละคณะสีแบบเรียลไทม์</p>
    </div>
    
    <div class="glass-panel rounded-[32px] p-6 md:p-8 shadow-2xl relative overflow-hidden">
        <div class="absolute -right-20 -top-20 w-48 h-48 rounded-full bg-indigo-500/5 blur-3xl pointer-events-none"></div>
        
        <!-- Podium Chart of Top 3 Houses -->
        <?php include __DIR__ . '/components/podium_chart.php'; ?>
        
        <div class="flex flex-col gap-4">
            <?php 
                $rank = 1; 
                $maxPoints = 1;
                foreach ($leaderboard as $r) {
                    if ($r['total_points'] > $maxPoints) {
                        $maxPoints = $r['total_points'];
                    }
                }
                foreach ($leaderboard as $row):
                    include __DIR__ . '/components/leaderboard_row.php';
                    $rank++;
                endforeach;
            ?>
        </div>
    </div>
</section>



<!-- Bracket Section (สายการแข่งขันแบบแพ้คัดออก) -->
<section id="brackets" class="py-20 max-w-6xl mx-auto px-4 border-t border-white/5 relative z-10">
    <div class="text-center mb-16">
        <span class="text-xs uppercase tracking-widest text-teal-400 font-bold block mb-2">Bracket Slider</span>
        <h2 class="text-3xl md:text-4xl font-black font-heading text-white">สายการแข่งขัน (Tournament Bracket)</h2>
        <p class="text-slate-400 text-sm mt-1">ผังการประกบคู่และผลการแข่งขันแบบแพ้คัดออกของแต่ละชนิดกีฬา</p>
    </div>

    <?php if (empty($active_brackets)): ?>
        <div class="glass-panel rounded-3xl p-12 text-center text-slate-500 font-semibold max-w-4xl mx-auto">
            <i class="fa-solid fa-folder-open text-4xl mb-3 block text-slate-700"></i>
            ยังไม่มีการจัดตารางสายการแข่งขันสำหรับกีฬาประเภทใดในขณะนี้
        </div>
    <?php else: ?>
        <!-- Filter dropdown -->
        <div class="flex justify-center mb-8 px-4">
            <div class="flex items-center gap-3 bg-slate-900/60 border border-white/5 backdrop-blur-xl px-4 py-2.5 rounded-2xl max-w-sm w-full">
                <label for="bracket-filter" class="text-xs font-bold text-slate-400 whitespace-nowrap"><i class="fa-solid fa-magnifying-glass mr-1 text-teal-400"></i>ค้นหากีฬา:</label>
                <select id="bracket-filter" class="w-full bg-transparent border-0 text-xs font-bold text-white focus:outline-none cursor-pointer">
                    <?php 
                    $slide_index = 0;
                    foreach ($active_brackets as $sport_id => $sport_data) {
                        echo '<option class="bg-slate-950 text-white" value="' . $slide_index . '">' . htmlspecialchars($sport_data['sport_name']) . '</option>';
                        $slide_index++;
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="relative w-full overflow-hidden select-none">
            <!-- Navigation arrows -->
            <button class="absolute left-2 top-1/2 -translate-y-1/2 z-20 bg-slate-950/80 border border-white/10 hover:border-teal-500/50 hover:bg-slate-900 text-white rounded-full w-10 h-10 flex items-center justify-center cursor-pointer transition-all duration-300 active:scale-95 disabled:opacity-20 disabled:pointer-events-none shadow-lg" id="prev-bracket-btn">
                <i class="fa-solid fa-chevron-left text-sm"></i>
            </button>
            <button class="absolute right-2 top-1/2 -translate-y-1/2 z-20 bg-slate-950/80 border border-white/10 hover:border-teal-500/50 hover:bg-slate-900 text-white rounded-full w-10 h-10 flex items-center justify-center cursor-pointer transition-all duration-300 active:scale-95 disabled:opacity-20 disabled:pointer-events-none shadow-lg" id="next-bracket-btn">
                <i class="fa-solid fa-chevron-right text-sm"></i>
            </button>

            <!-- Slides track -->
            <div class="flex transition-transform duration-500 ease-in-out" id="bracket-slides-track">
                <?php foreach ($active_brackets as $sport_id => $sport_data): 
                    $round_matches = [1 => [], 2 => [], 3 => []];
                    foreach ($sport_data['matches'] as $b) {
                        $round_matches[$b['round_number']][] = $b;
                    }
                ?>
                    <!-- Slide Item -->
                    <div class="w-full shrink-0 px-12 md:px-16">
                        <div class="glass-panel rounded-3xl p-6 md:p-8 hover:border-white/8 transition-all duration-300">
                            <!-- Slide Header -->
                            <div class="flex items-center justify-between border-b border-white/5 pb-4 mb-6">
                                <div>
                                    <h3 class="text-xl font-bold text-white font-heading tracking-wide">
                                        <i class="fa-solid fa-trophy text-[#d4af37] mr-1.5 drop-shadow-[0_0_8px_rgba(212,175,55,0.3)]"></i>
                                        <?= htmlspecialchars($sport_data['sport_name']) ?>
                                    </h3>
                                    <span class="text-xs text-slate-400 font-semibold block mt-0.5">ประเภท: <?= htmlspecialchars($sport_data['sport_category']) ?></span>
                                </div>
                                <div class="text-[9px] uppercase font-bold tracking-widest text-teal-400 bg-teal-500/10 border border-teal-500/20 px-3 py-1 rounded-full">
                                    สายการแข่งขันทัวร์นาเมนต์
                                </div>
                            </div>

                            <!-- Bracket Tree Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative py-2">
                                <!-- Round 1: Quarter-finals -->
                                <div class="flex flex-col gap-6 justify-center">
                                    <h4 class="text-xs font-bold text-indigo-400 uppercase tracking-wider text-center border-b border-indigo-500/10 pb-2 font-heading">Quarter-finals</h4>
                                    <?php foreach ($round_matches[1] as $b): ?>
                                        <div class="bg-slate-900/60 border border-white/5 rounded-2xl p-4 flex flex-col gap-2 relative shadow-md hover:border-white/10 transition-all duration-300">
                                            <div class="text-[10px] text-slate-400 font-bold flex justify-between border-b border-white/5 pb-1">
                                                <span>คู่ที่ <?= $b['match_order'] ?></span>
                                                <?php if ($b['status'] === 'Completed'): ?>
                                                    <span class="text-green-400">เสร็จสิ้น</span>
                                                <?php elseif ($b['status'] === 'Live'): ?>
                                                    <span class="text-rose-400">กำลังแข่ง</span>
                                                <?php else: ?>
                                                    <span class="text-slate-500">รอแข่ง</span>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <!-- Team 1 -->
                                            <div class="flex justify-between items-center py-1 <?= ($b['winner_house_id'] !== null && $b['winner_house_id'] == $b['team1_house_id']) ? 'font-bold text-white' : 'text-slate-400' ?>">
                                                <span class="flex items-center gap-2 text-xs truncate">
                                                    <span class="w-2 h-2 rounded-full shrink-0" style="background-color: <?= $b['team1_color'] ?: '#334155' ?>"></span>
                                                    <?= $b['team1_name'] ? htmlspecialchars($presenter->getHouseNameTh($b['team1_name'])) : 'TBD' ?>
                                                </span>
                                                <span class="text-xs font-black"><?= $b['team1_score'] !== null ? $b['team1_score'] : '-' ?></span>
                                            </div>

                                            <!-- Team 2 -->
                                            <div class="flex justify-between items-center py-1 <?= ($b['winner_house_id'] !== null && $b['winner_house_id'] == $b['team2_house_id']) ? 'font-bold text-white' : 'text-slate-400' ?>">
                                                <span class="flex items-center gap-2 text-xs truncate">
                                                    <span class="w-2 h-2 rounded-full shrink-0" style="background-color: <?= $b['team2_color'] ?: '#334155' ?>"></span>
                                                    <?= $b['team2_name'] ? htmlspecialchars($presenter->getHouseNameTh($b['team2_name'])) : 'TBD' ?>
                                                </span>
                                                <span class="text-xs font-black"><?= $b['team2_score'] !== null ? $b['team2_score'] : '-' ?></span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <!-- Round 2: Semi-finals -->
                                <div class="flex flex-col gap-6 justify-center">
                                    <h4 class="text-xs font-bold text-purple-400 uppercase tracking-wider text-center border-b border-purple-500/10 pb-2 font-heading">Semi-finals</h4>
                                    <?php foreach ($round_matches[2] as $b): ?>
                                        <div class="bg-slate-900/60 border border-white/5 rounded-2xl p-4 flex flex-col gap-2 relative shadow-md hover:border-white/10 transition-all duration-300">
                                            <div class="text-[10px] text-slate-400 font-bold flex justify-between border-b border-white/5 pb-1">
                                                <span>คู่ที่ <?= $b['match_order'] ?></span>
                                                <?php if ($b['status'] === 'Completed'): ?>
                                                    <span class="text-green-400">เสร็จสิ้น</span>
                                                <?php elseif ($b['status'] === 'Live'): ?>
                                                    <span class="text-rose-400">กำลังแข่ง</span>
                                                <?php else: ?>
                                                    <span class="text-slate-500">รอแข่ง</span>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <!-- Team 1 -->
                                            <div class="flex justify-between items-center py-1 <?= ($b['winner_house_id'] !== null && $b['winner_house_id'] == $b['team1_house_id']) ? 'font-bold text-white' : 'text-slate-400' ?>">
                                                <span class="flex items-center gap-2 text-xs truncate">
                                                    <span class="w-2 h-2 rounded-full shrink-0" style="background-color: <?= $b['team1_color'] ?: '#334155' ?>"></span>
                                                    <?= $b['team1_name'] ? htmlspecialchars($presenter->getHouseNameTh($b['team1_name'])) : 'TBD' ?>
                                                    <span class="text-[8px] bg-teal-500/10 text-teal-400 px-1 py-0.2 rounded border border-teal-500/10 scale-90">BYE</span>
                                                </span>
                                                <span class="text-xs font-black"><?= $b['team1_score'] !== null ? $b['team1_score'] : '-' ?></span>
                                            </div>

                                            <!-- Team 2 -->
                                            <div class="flex justify-between items-center py-1 <?= ($b['winner_house_id'] !== null && $b['winner_house_id'] == $b['team2_house_id']) ? 'font-bold text-white' : 'text-slate-400' ?>">
                                                <span class="flex items-center gap-2 text-xs truncate">
                                                    <span class="w-2 h-2 rounded-full shrink-0" style="background-color: <?= $b['team2_color'] ?: '#334155' ?>"></span>
                                                    <?= $b['team2_name'] ? htmlspecialchars($presenter->getHouseNameTh($b['team2_name'])) : 'รอผู้ชนะคู่ ' . ($b['match_order'] == 1 ? '1' : '2') ?>
                                                </span>
                                                <span class="text-xs font-black"><?= $b['team2_score'] !== null ? $b['team2_score'] : '-' ?></span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <!-- Round 3: Finals & Third-place -->
                                <div class="flex flex-col gap-6 justify-center">
                                    <?php 
                                    $finals_match = null;
                                    $third_place_match = null;
                                    foreach ($round_matches[3] as $bm) {
                                        if ($bm['round_name'] === 'Finals') {
                                            $finals_match = $bm;
                                        } elseif ($bm['round_name'] === 'Third-place') {
                                            $third_place_match = $bm;
                                        }
                                    }
                                    ?>
                                    
                                    <!-- Finals -->
                                    <?php if ($finals_match): $b = $finals_match; ?>
                                        <h4 class="text-xs font-bold text-[#d4af37] uppercase tracking-wider text-center border-b border-[#d4af37]/10 pb-2 font-heading">Finals</h4>
                                        <div class="bg-slate-900/60 border border-[#d4af37]/20 rounded-2xl p-5 flex flex-col gap-3 relative shadow-md hover:border-[#d4af37]/40 transition-all duration-300 bg-gradient-to-b from-slate-900/60 to-yellow-500/2">
                                            <div class="text-[10px] text-slate-400 font-bold flex justify-between border-b border-white/5 pb-1">
                                                <span>คู่ชิงชนะเลิศ</span>
                                                <?php if ($b['status'] === 'Completed'): ?>
                                                    <span class="text-yellow-400 flex items-center gap-1"><i class="fa-solid fa-trophy text-xs animate-bounce"></i>ได้ผู้ชนะเลิศ</span>
                                                <?php elseif ($b['status'] === 'Live'): ?>
                                                    <span class="text-rose-400">กำลังแข่ง</span>
                                                <?php else: ?>
                                                    <span class="text-slate-500">รอแข่ง</span>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <!-- Team 1 -->
                                            <div class="flex justify-between items-center py-1.5 <?= ($b['winner_house_id'] !== null && $b['winner_house_id'] == $b['team1_house_id']) ? 'font-bold text-yellow-400' : 'text-slate-400' ?>">
                                                <span class="flex items-center gap-2 text-xs truncate">
                                                    <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background-color: <?= $b['team1_color'] ?: '#334155' ?>"></span>
                                                    <?= $b['team1_name'] ? htmlspecialchars($presenter->getHouseNameTh($b['team1_name'])) : 'รอผู้ชนะรอบรอง 1' ?>
                                                </span>
                                                <span class="text-xs font-black"><?= $b['team1_score'] !== null ? $b['team1_score'] : '-' ?></span>
                                            </div>

                                            <!-- Team 2 -->
                                            <div class="flex justify-between items-center py-1.5 <?= ($b['winner_house_id'] !== null && $b['winner_house_id'] == $b['team2_house_id']) ? 'font-bold text-yellow-400' : 'text-slate-400' ?>">
                                                <span class="flex items-center gap-2 text-xs truncate">
                                                    <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background-color: <?= $b['team2_color'] ?: '#334155' ?>"></span>
                                                    <?= $b['team2_name'] ? htmlspecialchars($presenter->getHouseNameTh($b['team2_name'])) : 'รอผู้ชนะรอบรอง 2' ?>
                                                </span>
                                                <span class="text-xs font-black"><?= $b['team2_score'] !== null ? $b['team2_score'] : '-' ?></span>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Third Place Playoff -->
                                    <?php if ($third_place_match): $b = $third_place_match; ?>
                                        <h4 class="text-xs font-bold text-amber-600 uppercase tracking-wider text-center border-b border-amber-600/10 pt-4 pb-2 font-heading">Third-place (ชิงอันดับ 3)</h4>
                                        <div class="bg-slate-900/60 border border-amber-600/20 rounded-2xl p-5 flex flex-col gap-3 relative shadow-md hover:border-amber-600/40 transition-all duration-300 bg-gradient-to-b from-slate-900/60 to-amber-700/2">
                                            <div class="text-[10px] text-slate-400 font-bold flex justify-between border-b border-white/5 pb-1">
                                                <span>คู่ชิงอันดับที่ 3</span>
                                                <?php if ($b['status'] === 'Completed'): ?>
                                                    <span class="text-amber-500 flex items-center gap-1"><i class="fa-solid fa-medal text-xs"></i>ได้อันดับที่ 3</span>
                                                <?php elseif ($b['status'] === 'Live'): ?>
                                                    <span class="text-rose-400">กำลังแข่ง</span>
                                                <?php else: ?>
                                                    <span class="text-slate-500">รอแข่ง</span>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <!-- Team 1 -->
                                            <div class="flex justify-between items-center py-1.5 <?= ($b['winner_house_id'] !== null && $b['winner_house_id'] == $b['team1_house_id']) ? 'font-bold text-amber-500' : 'text-slate-400' ?>">
                                                <span class="flex items-center gap-2 text-xs truncate">
                                                    <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background-color: <?= $b['team1_color'] ?: '#334155' ?>"></span>
                                                    <?= $b['team1_name'] ? htmlspecialchars($presenter->getHouseNameTh($b['team1_name'])) : 'รอผู้แพ้รอบรอง 1' ?>
                                                </span>
                                                <span class="text-xs font-black"><?= $b['team1_score'] !== null ? $b['team1_score'] : '-' ?></span>
                                            </div>

                                            <!-- Team 2 -->
                                            <div class="flex justify-between items-center py-1.5 <?= ($b['winner_house_id'] !== null && $b['winner_house_id'] == $b['team2_house_id']) ? 'font-bold text-amber-500' : 'text-slate-400' ?>">
                                                <span class="flex items-center gap-2 text-xs truncate">
                                                    <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background-color: <?= $b['team2_color'] ?: '#334155' ?>"></span>
                                                    <?= $b['team2_name'] ? htmlspecialchars($presenter->getHouseNameTh($b['team2_name'])) : 'รอผู้แพ้รอบรอง 2' ?>
                                                </span>
                                                <span class="text-xs font-black"><?= $b['team2_score'] !== null ? $b['team2_score'] : '-' ?></span>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Carousel navigation dots -->
            <div class="flex justify-center gap-2 mt-6" id="bracket-dots-container"></div>
        </div>
    <?php endif; ?>
</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const track = document.getElementById('bracket-slides-track');
    const dotsContainer = document.getElementById('bracket-dots-container');
    const prevBtn = document.getElementById('prev-bracket-btn');
    const nextBtn = document.getElementById('next-bracket-btn');
    const filterSelect = document.getElementById('bracket-filter');
    
    if (!track) return;
    
    const slides = track.children;
    if (slides.length === 0) return;
    
    let currentIndex = 0;
    const totalSlides = slides.length;
    
    // Create dots
    for (let i = 0; i < totalSlides; i++) {
        const dot = document.createElement('button');
        dot.className = `w-2 h-2 rounded-full transition-all duration-300 ${i === 0 ? 'w-6 bg-teal-400' : 'bg-slate-700 hover:bg-slate-600'} cursor-pointer`;
        dot.setAttribute('aria-label', `Go to slide ${i + 1}`);
        dot.addEventListener('click', () => goToSlide(i));
        dotsContainer.appendChild(dot);
    }
    
    const dots = dotsContainer.children;
    
    function updateControls() {
        prevBtn.disabled = currentIndex === 0;
        nextBtn.disabled = currentIndex === totalSlides - 1;
        
        // Update select value
        if (filterSelect) {
            filterSelect.value = currentIndex;
        }
        
        // Update dots
        for (let i = 0; i < totalSlides; i++) {
            if (i === currentIndex) {
                dots[i].className = 'w-6 h-2 bg-teal-400 rounded-full transition-all duration-300 cursor-pointer';
            } else {
                dots[i].className = 'w-2 h-2 bg-slate-700 rounded-full hover:bg-slate-600 transition-all duration-300 cursor-pointer';
            }
        }
    }
    
    function goToSlide(index) {
        if (index < 0 || index >= totalSlides) return;
        currentIndex = index;
        track.style.transform = `translateX(-${currentIndex * 100}%)`;
        updateControls();
    }
    
    prevBtn.addEventListener('click', () => goToSlide(currentIndex - 1));
    nextBtn.addEventListener('click', () => goToSlide(currentIndex + 1));
    
    if (filterSelect) {
        filterSelect.addEventListener('change', (e) => {
            goToSlide(parseInt(e.target.value, 10));
        });
    }
    
    updateControls();
    
    // Resize handler to adjust slide widths
    window.addEventListener('resize', () => {
        track.style.transform = `translateX(-${currentIndex * 100}%)`;
    });

    // --- Premium Particle & Burst Engine & Switcher ---
    let currentHeroStyle = 'football';
    const particleColors = {
        football: {
            burst: ['#14b8a6', '#06b6d4', '#6366f1', '#fbbf24', '#f59e0b'],
            ambient: ['#fbbf24', '#14b8a6']
        },
        torch: {
            burst: ['#ef4444', '#f97316', '#fbbf24', '#facc15', '#ea580c'],
            ambient: ['#fbbf24', '#f97316']
        },
        shield: {
            burst: ['#ec4899', '#a855f7', '#f97316', '#fbbf24', '#facc15'],
            ambient: ['#ec4899', '#f97316']
        }
    };

    const canvas = document.getElementById('hero-particles-canvas');
    if (canvas) {
        const ctx = canvas.getContext('2d');
        let width = canvas.offsetWidth;
        let height = canvas.offsetHeight;
        
        const setCanvasSize = () => {
            width = canvas.offsetWidth;
            height = canvas.offsetHeight;
            canvas.width = width * window.devicePixelRatio;
            canvas.height = height * window.devicePixelRatio;
            ctx.scale(window.devicePixelRatio, window.devicePixelRatio);
        };
        
        setCanvasSize();

        const particles = [];
        
        class Particle {
            constructor(x, y, isBurst = false) {
                this.x = x;
                this.y = y;
                this.isBurst = isBurst;
                
                if (isBurst) {
                    const angle = Math.random() * Math.PI * 2;
                    const speed = 1.0 + Math.random() * 5.0; // Higher speed explosion
                    this.vx = Math.cos(angle) * speed;
                    this.vy = Math.sin(angle) * speed;
                    this.life = 1.0;
                    this.decay = 0.01 + Math.random() * 0.012; // Slower fade out
                    this.size = 1.5 + Math.random() * 2.5; // Bigger sparks
                    
                    const colors = particleColors[currentHeroStyle].burst;
                    this.color = colors[Math.floor(Math.random() * colors.length)];
                } else {
                    // Ambient flowing particles
                    this.vx = (Math.random() - 0.5) * 0.35;
                    this.vy = -0.2 - Math.random() * 0.45;
                    this.life = 0.5 + Math.random() * 0.5;
                    this.decay = 0.0025 + Math.random() * 0.0035;
                    this.size = 0.8 + Math.random() * 1.5;
                    
                    const colors = particleColors[currentHeroStyle].ambient;
                    this.color = Math.random() > 0.45 ? colors[0] : colors[1];
                }
            }

            update() {
                this.x += this.vx;
                this.y += this.vy;
                this.life -= this.decay;
                
                if (this.isBurst) {
                    // Gradual deceleration
                    this.vx *= 0.96;
                    this.vy *= 0.96;
                }
            }

            draw() {
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                ctx.fillStyle = this.color;
                ctx.globalAlpha = this.life;
                ctx.shadowBlur = this.isBurst ? 8 : 4;
                ctx.shadowColor = this.color;
                ctx.fill();
                ctx.shadowBlur = 0;
                ctx.globalAlpha = 1;
            }
        }

        // Trigger burst function
        function triggerBurst() {
            const centerX = width / 2;
            const centerY = height / 2;
            // Generate 55 sparks for a much grander burst!
            for (let i = 0; i < 55; i++) {
                particles.push(new Particle(centerX, centerY, true));
            }
        }

        // Spawn ambient particles periodically (flow)
        setInterval(() => {
            if (particles.length < 150) {
                const offsetX = (Math.random() - 0.5) * 70;
                const offsetY = (Math.random() - 0.5) * 70;
                particles.push(new Particle(width / 2 + offsetX, height / 2 + offsetY, false));
            }
        }, 120);

        // Auto burst & style cycle loop
        let currentStyleIndex = 0;
        const styles = ['football', 'torch', 'shield'];
        let autoCycleInterval = null;
        
        function cycleStyleAndBurst() {
            currentStyleIndex = (currentStyleIndex + 1) % styles.length;
            window.switchHeroStyle(styles[currentStyleIndex]);
        }
        
        function startAutoCycle() {
            if (autoCycleInterval) clearInterval(autoCycleInterval);
            autoCycleInterval = setInterval(cycleStyleAndBurst, 3500);
        }
        
        // Trigger initial burst instantly on load
        triggerBurst();
        
        // Start loop style cycles
        startAutoCycle();

        // Hover to trigger instant burst
        canvas.parentElement.addEventListener('mouseenter', triggerBurst);

        // Click centerpiece to cycle style and trigger burst manually
        canvas.parentElement.addEventListener('click', () => {
            cycleStyleAndBurst();
            startAutoCycle();
        });

        // Bind click events to the theme switcher buttons
        styles.forEach(item => {
            const btn = document.getElementById('btn-hero-' + item);
            if (btn) {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation(); // Prevent triggering centerpiece click
                    window.switchHeroStyle(item, true);
                });
            }
        });

        function animLoop() {
            // Use destination-out to fade particle opacity on transparent canvas!
            ctx.globalCompositeOperation = 'destination-out';
            ctx.fillStyle = 'rgba(0, 0, 0, 0.15)';
            ctx.fillRect(0, 0, width, height);
            ctx.globalCompositeOperation = 'source-over';
            
            for (let i = particles.length - 1; i >= 0; i--) {
                const p = particles[i];
                p.update();
                if (p.life <= 0) {
                    particles.splice(i, 1);
                } else {
                    p.draw();
                }
            }
            
            requestAnimationFrame(animLoop);
        }

        animLoop();

        window.addEventListener('resize', setCanvasSize);

        // --- Style Switcher Function ---
        window.switchHeroStyle = function(style, isManual = false) {
            currentHeroStyle = style;
            
            // Update currentStyleIndex when manually switched (or kept in sync)
            const styleIdx = styles.indexOf(style);
            if (styleIdx !== -1) {
                currentStyleIndex = styleIdx;
            }
            
            // Restart cycle timer if user clicked manually to prevent immediate auto-switching
            if (isManual) {
                startAutoCycle();
            }
            
            // 1. Update container class for CSS styles (glow, rings, wordmark)
            const container = document.getElementById('hero-centerpiece-container');
            if (container) {
                container.className = container.className.replace(/style-\w+/g, '');
                container.classList.add('style-' + style);
            }
            
            // 2. Update active SVG item (keeping football SVG visible if no others exist)
            const items = ['football', 'torch', 'shield'];
            const hasOtherSvgs = items.some(item => item !== 'football' && document.getElementById('svg-hero-' + item));
            items.forEach(item => {
                const svgEl = document.getElementById('svg-hero-' + item);
                if (svgEl) {
                    if (item === style || (!hasOtherSvgs && item === 'football')) {
                        svgEl.classList.add('active');
                    } else {
                        svgEl.classList.remove('active');
                    }
                }
            });
            
            // Dynamically update the SVG neon gradient colors to match the theme color
            const neonGradient = document.getElementById('neonGradient');
            if (neonGradient) {
                const stops = neonGradient.getElementsByTagName('stop');
                if (stops.length >= 2) {
                    if (style === 'football') {
                        stops[0].setAttribute('stop-color', '#14b8a6');
                        stops[1].setAttribute('stop-color', '#6366f1');
                    } else if (style === 'torch') {
                        stops[0].setAttribute('stop-color', '#f97316');
                        stops[1].setAttribute('stop-color', '#ef4444');
                    } else if (style === 'shield') {
                        stops[0].setAttribute('stop-color', '#ec4899');
                        stops[1].setAttribute('stop-color', '#a855f7');
                    }
                }
            }
            
            // 3. Update active button style
            const btnConfigs = {
                football: { activeClass: 'border-teal-500/40 bg-teal-950/40 text-teal-300 shadow-[0_0_12px_rgba(20,184,166,0.25)]', inactiveClass: 'border-white/5 bg-slate-900/40 text-slate-400 hover:text-slate-200 hover:border-white/15' },
                torch: { activeClass: 'border-orange-500/40 bg-orange-950/40 text-orange-300 shadow-[0_0_12px_rgba(249,115,22,0.25)]', inactiveClass: 'border-white/5 bg-slate-900/40 text-slate-400 hover:text-slate-200 hover:border-white/15' },
                shield: { activeClass: 'border-purple-500/40 bg-purple-950/40 text-purple-300 shadow-[0_0_12px_rgba(168,85,247,0.25)]', inactiveClass: 'border-white/5 bg-slate-900/40 text-slate-400 hover:text-slate-200 hover:border-white/15' }
            };
            
            items.forEach(item => {
                const btn = document.getElementById('btn-hero-' + item);
                if (btn) {
                    // Clear all classes first
                    btn.className = 'hero-style-btn px-4 py-2 rounded-full text-xs font-bold transition-all duration-300 flex items-center gap-1.5 cursor-pointer';
                    if (item === style) {
                        btn.className += ' ' + btnConfigs[item].activeClass;
                    } else {
                        btn.className += ' ' + btnConfigs[item].inactiveClass;
                    }
                }
            });
            
            // 4. Trigger a themed particle burst instantly
            triggerBurst();
        };
    }
});
</script>

<!-- Results Section (ผลการแข่งขันล่าสุด) -->
<section id="results" class="py-20 max-w-4xl mx-auto px-4 border-t border-white/5 relative z-10">
    <div class="text-center mb-16">
        <span class="text-xs uppercase tracking-widest text-yellow-500 font-bold block mb-2">Results</span>
        <h2 class="text-3xl md:text-4xl font-black font-heading text-white">ผลการแข่งขันล่าสุด</h2>
        <p class="text-slate-400 text-sm mt-1">สรุปข้อมูลเหรียญรางวัลและผลคะแนนการแข่งขันที่เสร็จสิ้นสมบูรณ์</p>
    </div>
    
    <div class="flex flex-col gap-4">
        <?php
        $completedMatches = array_filter($matches, function($m) {
            return $m['status'] === 'Completed';
        });
        if (empty($completedMatches)): ?>
            <div class="glass-panel border border-white/5 rounded-2xl p-10 text-center text-slate-500 font-semibold">
                <i class="fa-solid fa-award text-3xl mb-3 block text-yellow-500/30"></i>
                ยังไม่มีรายการแข่งขันใดที่เสร็จสิ้นสมบูรณ์ในระบบ
            </div>
        <?php else: ?>
            <?php foreach ($completedMatches as $match): ?>
                <?php include __DIR__ . '/components/match_card.php'; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<!-- Sports Section (ชนิดกีฬาที่จัดแข่ง) -->
<section id="sports" class="py-20 max-w-5xl mx-auto px-4 border-t border-white/5 mb-16 relative z-10">
    <div class="text-center mb-16">
        <span class="text-xs uppercase tracking-widest text-purple-400 font-bold block mb-2">Sports</span>
        <h2 class="text-3xl md:text-4xl font-black font-heading text-white">ชนิดกีฬาที่จัดแข่ง</h2>
        <p class="text-slate-400 text-sm mt-1">รายการกีฬาประเภทต่าง ๆ ที่ลงทะเบียนแข่งและมีการแข่งขันในกีฬาสีปีนี้</p>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        <?php if (empty($sports)): ?>
            <div class="col-span-full bg-slate-900/40 backdrop-blur-md border border-white/5 rounded-2xl p-8 text-center text-slate-500 font-semibold">
                <i class="fa-solid fa-running text-2xl mb-2 block text-purple-400/50"></i>
                ยังไม่มีข้อมูลชนิดกีฬาลงทะเบียนในระบบ
            </div>
        <?php else: ?>
            <?php foreach ($sports as $sport): ?>
                <?php include __DIR__ . '/components/sport_card.php'; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/components/footer.php'; ?>

</body>
</html>
