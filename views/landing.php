<!DOCTYPE html>
<html lang="th" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SportDay - ระบบการแข่งขันกีฬาสีโรงเรียนพิชัย</title>
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
        .animate-pulse-slow {
            animation: pulseSlow 10s ease-in-out infinite;
        }
        .animate-pulse-slow-reverse {
            animation: pulseSlowReverse 12s ease-in-out infinite;
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
    </style>
</head>
<body class="text-slate-100 font-body min-h-screen relative overflow-x-hidden">

<!-- Floating Blur Ambient BG Effect Orbs -->
<div class="absolute top-[5%] left-[-15%] w-80 h-80 md:w-[600px] md:h-[600px] rounded-full bg-indigo-500/[0.07] blur-[100px] md:blur-[130px] pointer-events-none animate-pulse-slow z-0"></div>
<div class="absolute top-[35%] right-[-15%] w-80 h-80 md:w-[600px] md:h-[600px] rounded-full bg-purple-500/[0.07] blur-[100px] md:blur-[130px] pointer-events-none animate-pulse-slow-reverse z-0"></div>
<div class="absolute bottom-[10%] left-[2%] w-96 h-96 md:w-[700px] md:h-[700px] rounded-full bg-cyan-500/[0.05] blur-[120px] md:blur-[150px] pointer-events-none animate-pulse-slow z-0"></div>

<!-- Navbar Header -->
<header class="app-header sticky top-0 z-50 bg-[#070913]/60 backdrop-blur-xl border-b border-white/5 shadow-2xl">
    <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
        <a href="index.php" class="brand-logo text-2xl font-black flex items-center gap-2 hover:scale-102 transition-transform duration-300 font-heading select-none">
            <i class="fa-solid fa-trophy text-[#d4af37] drop-shadow-[0_0_8px_rgba(212,175,55,0.4)]"></i>
            <span class="bg-gradient-to-r from-white via-slate-100 to-slate-400 bg-clip-text text-transparent">Phichai SportDay</span>
        </a>
        <nav class="hidden lg:flex items-center gap-8">
            <a href="#houses" class="text-slate-300 hover:text-white font-semibold transition-colors duration-200 relative after:absolute after:-bottom-1 after:left-0 after:w-0 after:h-0.5 after:bg-indigo-500 hover:after:w-full after:transition-all after:duration-300">คณะสี</a>
            <a href="#leaderboard" class="text-slate-300 hover:text-white font-semibold transition-colors duration-200 relative after:absolute after:-bottom-1 after:left-0 after:w-0 after:h-0.5 after:bg-indigo-500 hover:after:w-full after:transition-all after:duration-300">ตารางคะแนน</a>
            <a href="#schedule" class="text-slate-300 hover:text-white font-semibold transition-colors duration-200 relative after:absolute after:-bottom-1 after:left-0 after:w-0 after:h-0.5 after:bg-indigo-500 hover:after:w-full after:transition-all after:duration-300">ตารางแข่ง</a>
            <a href="#results" class="text-slate-300 hover:text-white font-semibold transition-colors duration-200 relative after:absolute after:-bottom-1 after:left-0 after:w-0 after:h-0.5 after:bg-indigo-500 hover:after:w-full after:transition-all after:duration-300">ผลการแข่ง</a>
            <a href="#sports" class="text-slate-300 hover:text-white font-semibold transition-colors duration-200 relative after:absolute after:-bottom-1 after:left-0 after:w-0 after:h-0.5 after:bg-indigo-500 hover:after:w-full after:transition-all after:duration-300">ชนิดกีฬา</a>
            <a href="index.php?route=login" class="bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-bold px-6 py-2 rounded-xl shadow-lg hover:shadow-indigo-500/20 transition-all duration-300 hover:-translate-y-0.5 select-none cursor-pointer">เข้าสู่ระบบ</a>
        </nav>
        <!-- Mobile Sign In Button -->
        <a href="index.php?route=login" class="lg:hidden bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-bold px-4 py-2 rounded-xl text-sm shadow-md">เข้าสู่ระบบ</a>
    </div>
</header>

<!-- Hero Section -->
<div class="max-w-4xl mx-auto text-center px-4 py-20 md:py-28 relative z-10">
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-gradient-to-r from-indigo-500/10 via-purple-500/10 to-pink-500/10 blur-3xl rounded-full -z-10 scale-120 pointer-events-none"></div>
    
    <span class="inline-flex items-center gap-1.5 bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 text-xs font-bold px-3 py-1 rounded-full uppercase mb-6 shadow-sm">
        <i class="fa-solid fa-sparkles"></i> โรงเรียนพิชัย อุตรดิตถ์
    </span>
    
    <h1 class="text-4xl sm:text-5xl md:text-7xl font-extrabold tracking-tight leading-[1.1] mb-8 bg-gradient-to-br from-white via-slate-100 to-slate-400 bg-clip-text text-transparent font-heading">
        การแข่งขันกีฬาสีโรงเรียน<br class="hidden sm:block"> ประจำปี 2569
    </h1>
    <p class="text-slate-400 text-base md:text-lg max-w-2xl mx-auto mb-10 leading-relaxed font-medium">
        ร่วมเป็นส่วนหนึ่งของชัยชนะและเกียรติยศ ติดตามผลการแข่งขันแบบเรียลไทม์ ตารางคะแนนสรุปอันดับเหรียญทอง และดาวน์โหลดเกียรติบัตรเหรียญรางวัลของนักเรียนได้ทันที!
    </p>
    <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
        <a href="#leaderboard" class="w-full sm:w-auto bg-gradient-to-r from-indigo-500 via-purple-600 to-pink-500 hover:from-indigo-600 hover:via-purple-700 hover:to-pink-600 text-white font-bold px-8 py-3.5 rounded-2xl shadow-lg shadow-indigo-500/25 transition-all duration-300 hover:scale-103 hover:-translate-y-0.5 select-none">
            <i class="fa-solid fa-ranking-star mr-1.5"></i>ดูตารางคะแนนสะสม
        </a>
        <a href="index.php?route=login" class="w-full sm:w-auto bg-white/5 hover:bg-white/10 text-white font-bold px-8 py-3.5 rounded-2xl border border-white/5 hover:border-white/15 transition-all duration-300 hover:-translate-y-0.5 select-none">
            <i class="fa-solid fa-user-shield mr-1.5"></i>เข้าสู่ระบบนักกีฬา / ผู้ดูแล
        </a>
    </div>
</div>

<!-- Stats Counter Grid -->
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
                <?php 
                    $iconClass = $presenter->getHouseIcon($row['house_name']);
                    $houseNameTh = $presenter->getHouseNameTh($row['house_name']);
                ?>
                <div class="bg-slate-900/35 backdrop-blur-xl rounded-[28px] p-6 text-center border border-[rgba(var(--house-color-rgb),0.15)] hover:border-[rgba(var(--house-color-rgb),0.45)] hover:shadow-[0_20px_50px_-5px_rgba(var(--house-color-rgb),0.2)] hover:-translate-y-2 hover:scale-102 transition-all duration-300 relative group overflow-hidden" <?= $presenter->getHouseStyle($row['color_code']) ?>>
                    <div class="absolute inset-0 bg-gradient-to-br from-[var(--house-color)]/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="w-16 h-16 rounded-2xl bg-[rgba(var(--house-color-rgb),0.08)] border border-[rgba(var(--house-color-rgb),0.2)] text-[var(--house-color)] flex items-center justify-center mx-auto mb-6 text-2xl shadow-[0_0_20px_rgba(var(--house-color-rgb),0.15)] group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                        <i class="<?= $iconClass ?>"></i>
                    </div>
                    <h3 class="text-xl font-bold text-[var(--house-color)] mb-3 font-heading"><?= htmlspecialchars($houseNameTh) ?></h3>
                    <span class="inline-flex bg-[rgba(var(--house-color-rgb),0.08)] text-[var(--house-color)] border border-[rgba(var(--house-color-rgb),0.2)] text-[10px] font-bold px-3.5 py-1 rounded-full uppercase tracking-wider">ผู้ร่วมท้าชิง</span>
                </div>
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
                    $pct = ($row['total_points'] / $maxPoints) * 100;
                    $houseNameTh = $presenter->getHouseNameTh($row['house_name']);
            ?>
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 p-5 rounded-2xl bg-white/[0.01] hover:bg-slate-900/60 border-l-6 border-[var(--house-color)] border border-white/5 hover:border-white/10 transition-all duration-300 relative group overflow-hidden" <?= $presenter->getHouseStyle($row['color_code']) ?>>
                    <!-- Rank & Name info -->
                    <div class="flex items-center gap-4">
                        <div class="text-3xl font-black w-8 text-center text-slate-505 font-heading">
                            <?php if ($rank === 1): ?>
                                <span class="text-yellow-500 drop-shadow-[0_0_6px_rgba(234,179,8,0.4)]"><i class="fa-solid fa-crown animate-bounce"></i></span>
                            <?php else: ?>
                                <?= $rank ?>
                            <?php endif; $rank++; ?>
                        </div>
                        <div>
                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                <span class="text-xl font-bold text-white font-heading"><?= htmlspecialchars($houseNameTh) ?></span>
                                <div class="flex flex-wrap gap-1">
                                    <span class="inline-flex bg-yellow-500/10 text-yellow-400 border border-yellow-500/20 text-[9px] font-black px-2 py-0.5 rounded-full">ทอง: <?= $row['gold_count'] ?></span>
                                    <span class="inline-flex bg-slate-300/10 text-slate-300 border border-slate-300/20 text-[9px] font-black px-2 py-0.5 rounded-full">เงิน: <?= $row['silver_count'] ?></span>
                                    <span class="inline-flex bg-orange-500/10 text-orange-400 border border-orange-500/20 text-[9px] font-black px-2 py-0.5 rounded-full">ทองแดง: <?= $row['bronze_count'] ?></span>
                                </div>
                            </div>
                            <div class="bg-white/5 border border-white/5 rounded-full h-2 w-48 sm:w-64 overflow-hidden">
                                <div class="h-full rounded-full bg-[var(--house-color)] shadow-[0_0_10px_rgba(var(--house-color-rgb),0.5)] transition-all duration-1000" style="width: <?= $pct ?>%;"></div>
                            </div>
                        </div>
                    </div>
                    <!-- Points -->
                    <div class="text-2xl md:text-3xl font-black text-right text-indigo-400 font-heading shrink-0"><?= htmlspecialchars($row['total_points']) ?> <span class="text-xs text-slate-400 font-semibold block sm:inline sm:text-sm">คะแนน</span></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Schedule Section (รายการที่กำลังจะแข่ง) -->
<section id="schedule" class="py-20 max-w-4xl mx-auto px-4 border-t border-white/5 relative z-10">
    <div class="text-center mb-16">
        <span class="text-xs uppercase tracking-widest text-indigo-400 font-bold block mb-2">Schedule</span>
        <h2 class="text-3xl md:text-4xl font-black font-heading text-white">รายการที่กำลังจะแข่ง</h2>
        <p class="text-slate-400 text-sm mt-1">ตารางเวลาและกำหนดการแข่งกีฬาสีที่กำลังดำเนินอยู่หรือเตรียมแข่งถัดไป</p>
    </div>
    
    <div class="flex flex-col gap-4">
        <?php
        $upcomingMatches = array_filter($matches, function($m) {
            return $m['status'] !== 'Completed';
        });
        if (empty($upcomingMatches)): ?>
            <div class="glass-panel border border-white/5 rounded-2xl p-10 text-center text-slate-500 font-semibold">
                <i class="fa-solid fa-calendar-xmark text-3xl mb-3 block text-indigo-400/30"></i>
                ไม่มีการแข่งขันที่อยู่ระหว่างเตรียมแข่งหรือกำลังแข่งในขณะนี้
            </div>
        <?php else: ?>
            <?php foreach ($upcomingMatches as $match): ?>
                <div class="bg-slate-900/35 backdrop-blur-xl border border-white/5 rounded-2xl p-5 md:p-6 flex flex-col md:flex-row md:items-center justify-between gap-4 hover:border-white/10 hover:shadow-lg hover:translate-y-[-2px] transition-all duration-300">
                    <div class="flex items-center gap-4">
                        <div class="bg-indigo-500/10 border border-indigo-500/15 p-3.5 rounded-2xl text-indigo-400 text-xl">
                            <i class="fa-solid fa-clock animate-pulse"></i>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-white mb-0.5 font-heading"><?= htmlspecialchars($match['sport_name']) ?></h4>
                            <span class="text-xs text-slate-400 block font-semibold">
                                หมวดหมู่: <?= htmlspecialchars($match['category']) ?> • <i class="fa-regular fa-calendar mr-1"></i><?= $presenter->formatDate($match['event_date']) ?>
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <div>
                            <?php if ($match['status'] === 'Live'): ?>
                                <span class="inline-flex bg-rose-500/10 text-rose-400 border border-rose-500/25 text-xs font-bold px-3.5 py-1 rounded-full items-center shadow-inner shadow-rose-500/5 select-none"><span class="live-pulse"></span>กำลังแข่ง</span>
                            <?php else: ?>
                                <span class="inline-flex bg-slate-800 text-slate-400 border border-white/5 text-xs font-bold px-3.5 py-1 rounded-full select-none">รอการแข่งขัน</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

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
                <div class="bg-slate-900/35 backdrop-blur-xl border border-white/5 rounded-2xl p-5 md:p-6 flex flex-col md:flex-row md:items-center justify-between gap-4 hover:border-white/10 hover:shadow-lg hover:translate-y-[-2px] transition-all duration-300">
                    <div class="flex items-center gap-4">
                        <div class="bg-yellow-500/10 border border-yellow-500/15 p-3.5 rounded-2xl text-yellow-500 text-xl">
                            <i class="fa-solid fa-trophy"></i>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-white mb-0.5 font-heading"><?= htmlspecialchars($match['sport_name']) ?></h4>
                            <span class="text-xs text-slate-400 block font-semibold">
                                หมวดหมู่: <?= htmlspecialchars($match['category']) ?> • <i class="fa-regular fa-calendar mr-1"></i><?= $presenter->formatDate($match['event_date']) ?>
                            </span>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                        <!-- Medal holders details -->
                        <?php if (isset($matchResults[$match['id']])): ?>
                            <div class="flex flex-wrap gap-2">
                                <?php foreach ($matchResults[$match['id']] as $res): ?>
                                    <?php if (!empty($res['medal'])): 
                                        $medalIcon = 'fa-solid fa-medal text-yellow-500';
                                        if ($res['medal'] === 'Silver') $medalIcon = 'fa-solid fa-medal text-slate-300';
                                        elseif ($res['medal'] === 'Bronze') $medalIcon = 'fa-solid fa-medal text-orange-500';
                                        $hNameTh = $presenter->getHouseNameTh($res['house_name']);
                                    ?>
                                        <div class="inline-flex bg-[rgba(var(--house-color-rgb),0.08)] text-[var(--house-color)] border border-[rgba(var(--house-color-rgb),0.2)] pl-2.5 pr-3 py-1 items-center gap-1.5 rounded-full text-xs font-semibold shadow-sm" <?= $presenter->getHouseStyle($res['color_code']) ?>>
                                            <i class="<?= $medalIcon ?>"></i>
                                            <span><?= htmlspecialchars($hNameTh) ?></span>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
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
            <?php foreach ($sports as $sport): 
                // Dynamically guess category icons
                $sportIcon = 'fa-solid fa-circle-play';
                $cat = mb_strtolower($sport['category'], 'UTF-8');
                $name = mb_strtolower($sport['sport_name'], 'UTF-8');
                
                if (stripos($cat, 'กรีฑา') !== false || stripos($name, 'วิ่ง') !== false || stripos($cat, 'ประเภทลู่') !== false) {
                    $sportIcon = 'fa-solid fa-person-running';
                } elseif (stripos($name, 'ฟุตบอล') !== false || stripos($name, 'ฟุตซอล') !== false) {
                    $sportIcon = 'fa-solid fa-futbol';
                } elseif (stripos($name, 'บาสเกตบอล') !== false) {
                    $sportIcon = 'fa-solid fa-basketball';
                } elseif (stripos($name, 'วอลเลย์บอล') !== false) {
                    $sportIcon = 'fa-solid fa-volleyball';
                } elseif (stripos($name, 'เทเบิลเทนนิส') !== false || stripos($name, 'ปิงปอง') !== false) {
                    $sportIcon = 'fa-solid fa-table-tennis-paddle-ball';
                } elseif (stripos($name, 'แบดมินตัน') !== false) {
                    $sportIcon = 'fa-solid fa-dumbbell';
                } elseif (stripos($name, 'เปตอง') !== false) {
                    $sportIcon = 'fa-solid fa-circle';
                }
            ?>
                <div class="bg-slate-900/40 backdrop-blur-xl border border-white/5 rounded-2xl p-5 hover:border-white/10 hover:shadow-[0_12px_30px_rgba(168,85,247,0.08)] hover:-translate-y-1 transition-all duration-300 flex items-center gap-4 group cursor-pointer relative overflow-hidden">
                    <div class="w-12 h-12 rounded-xl bg-purple-500/10 border border-purple-500/15 text-purple-400 flex items-center justify-center text-xl shrink-0 group-hover:scale-110 group-hover:rotate-6 transition-transform duration-300 shadow-sm">
                        <i class="<?= $sportIcon ?>"></i>
                    </div>
                    <div class="min-w-0">
                        <strong class="block text-white font-bold text-sm truncate font-heading group-hover:text-purple-300 transition-colors duration-200"><?= htmlspecialchars($sport['sport_name']) ?></strong>
                        <span class="text-[11px] text-slate-400 block truncate font-semibold">หมวดหมู่: <?= htmlspecialchars($sport['category']) ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<!-- Footer -->
<footer class="border-t border-white/5 py-12 text-center text-slate-500 text-sm relative z-10 bg-[#070913]/30">
    <p>&copy; 2569 ระบบรายงานผลการแข่งขันกีฬาสีโรงเรียนพิชัย พัฒนาขึ้นเพื่อเชื่อมสัมพันธ์สามัคคี</p>
</footer>

</body>
</html>
