<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สรุปตารางคะแนนสะสม - SportDay</title>
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
    </style>
</head>
<body class="text-slate-100 font-body min-h-screen">

<div class="max-w-3xl mx-auto px-4 py-12">
    
    <!-- Header Title Section -->
    <div class="text-center mb-10">
        <h1 class="text-3xl md:text-5xl font-black mb-3 bg-gradient-to-r from-white via-slate-100 to-slate-400 bg-clip-text text-transparent font-heading">
            <i class="fa-solid fa-ranking-star text-[#d4af37] mr-1"></i>
            สรุปตารางคะแนนสะสม
        </h1>
        <p class="text-slate-400 text-sm md:text-base font-semibold">อันดับคะแนนรวมสะสมของทุกคณะสีและเหรียญรางวัลรวมแบบเรียลไทม์</p>
    </div>

    <!-- Navigation Bar / Back button -->
    <div class="mb-6 flex justify-between items-center">
        <a href="index.php?route=dashboard" class="bg-white/5 hover:bg-white/10 text-white border border-white/5 font-bold px-4 py-2 rounded-xl text-xs transition-colors duration-200 cursor-pointer flex items-center gap-1.5 shadow-md">
            <i class="fa-solid fa-arrow-left"></i>
            ย้อนกลับหน้าหลัก
        </a>
        <span class="inline-flex bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 text-[10px] font-black px-3 py-1 rounded-full uppercase">
            อัปเดตเรียลไทม์
        </span>
    </div>

    <!-- Leaderboard Standings Card -->
    <div class="bg-slate-900/60 backdrop-blur-xl border border-white/5 rounded-[24px] p-6 md:p-8 shadow-2xl relative overflow-hidden">
        <div class="absolute -right-20 -top-20 w-48 h-48 rounded-full bg-indigo-500/5 blur-3xl pointer-events-none"></div>
        
        <div class="flex flex-col gap-4">
            <?php if (empty($leaderboard)): ?>
                <div class="text-center text-slate-500 py-12 font-semibold">
                    <i class="fa-solid fa-circle-info text-2xl mb-2 block"></i>
                    ยังไม่มีข้อมูลผลคะแนนในระบบ
                </div>
            <?php else: ?>
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
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 rounded-2xl bg-white/[0.01] hover:bg-white/[0.03] border border-white/5 border-l-6 border-l-[var(--house-color)] transition-all duration-200" <?= $presenter->getHouseStyle($row['color_code']) ?>>
                        <div class="flex items-center gap-4">
                            <!-- Rank -->
                            <div class="text-2xl font-black w-8 text-center text-slate-400 font-heading">
                                <?php if ($rank === 1): ?>
                                    <span class="text-yellow-500"><i class="fa-solid fa-crown"></i></span>
                                <?php else: ?>
                                    <?= $rank ?>
                                <?php endif; $rank++; ?>
                            </div>
                            
                            <!-- Name and bar graph -->
                            <div>
                                <div class="flex flex-wrap items-center gap-2 mb-1.5">
                                    <span class="text-base sm:text-lg font-bold text-white font-heading"><?= htmlspecialchars($houseNameTh) ?></span>
                                    <div class="flex flex-wrap gap-1">
                                        <span class="inline-flex bg-yellow-500/10 text-yellow-400 border border-yellow-500/20 text-[9px] font-bold px-1.5 py-0.5 rounded-full">ทอง: <?= $row['gold_count'] ?></span>
                                        <span class="inline-flex bg-slate-300/10 text-slate-300 border border-slate-300/20 text-[9px] font-bold px-1.5 py-0.5 rounded-full">เงิน: <?= $row['silver_count'] ?></span>
                                        <span class="inline-flex bg-orange-500/10 text-orange-400 border border-orange-500/20 text-[9px] font-bold px-1.5 py-0.5 rounded-full">ทองแดง: <?= $row['bronze_count'] ?></span>
                                    </div>
                                </div>
                                <div class="bg-white/5 border border-white/5 rounded-full h-1.5 w-44 sm:w-56 overflow-hidden">
                                    <div class="h-full rounded-full bg-[var(--house-color)] shadow-[0_0_10px_rgba(var(--house-color-rgb),0.5)] transition-all duration-1000" style="width: <?= $pct ?>%;"></div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Points -->
                        <div class="text-xl md:text-2xl font-black text-left sm:text-right text-indigo-400 shrink-0 ml-12 sm:ml-0 font-heading">
                            <?= htmlspecialchars($row['total_points']) ?> คะแนน
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>
