<?php
/**
 * Dedicated Public Leaderboard Page
 */
$pageTitle = "ตารางคะแนนรวมและเหรียญรางวัล - Pichai Game 2026";
$activeRoute = "leaderboard";
$leaderboard = $leaderboard ?? [];
$presenter = $presenter ?? new SportPresenter();
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
            background: rgba(13, 17, 33, 0.45);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 font-body antialiased selection:bg-amber-500 selection:text-slate-950 min-h-screen flex flex-col justify-between">

    <!-- Header Navigation -->
    <?php include __DIR__ . '/components/header.php'; ?>

    <main class="flex-grow pt-24 pb-20 max-w-4xl mx-auto px-4 w-full">
        <!-- Page Title & Header -->
        <div class="text-center mb-10">
            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/25 text-indigo-300 text-xs font-bold uppercase tracking-wider mb-2.5">
                <i class="fa-solid fa-ranking-star text-amber-400"></i> Live Scoreboard
            </div>
            <h1 class="text-3xl md:text-5xl font-black font-heading text-white flex items-center justify-center gap-3">
                <span class="w-3 h-8 bg-gradient-to-b from-amber-400 to-orange-500 rounded-full"></span>
                ตารางคะแนนและอันดับเหรียญรางวัลล่าสุด
            </h1>
            <p class="text-slate-400 text-xs sm:text-sm mt-2 max-w-xl mx-auto">
                สรุปอันดับเหรียญรางวัลสะสมสูงสุดและคะแนนรวมของแต่ละคณะสีแบบเรียลไทม์
            </p>
        </div>

        <div class="glass-panel rounded-[32px] p-6 md:p-8 shadow-2xl relative overflow-hidden mb-12">
            <div class="absolute -right-20 -top-20 w-48 h-48 rounded-full bg-indigo-500/5 blur-3xl pointer-events-none"></div>
            
            <!-- Podium Chart of Top 3 Houses -->
            <?php include __DIR__ . '/components/podium_chart.php'; ?>
            
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
                            include __DIR__ . '/components/leaderboard_row.php';
                            $rank++;
                        endforeach;
                    ?>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/components/footer.php'; ?>
</body>
</html>
