<?php
/**
 * Dedicated Public Houses & Teams Page
 */
$pageTitle = "คณะสีที่ร่วมประชันชัย - Pichai Game 2026";
$activeRoute = "houses";
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

    <main class="flex-grow pt-24 pb-20 max-w-6xl mx-auto px-4 w-full">
        <!-- Page Title & Header -->
        <div class="text-center mb-14">
            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/25 text-emerald-300 text-xs font-bold uppercase tracking-wider mb-2.5">
                <i class="fa-solid fa-flag"></i> Competing Houses
            </div>
            <h1 class="text-3xl md:text-5xl font-black font-heading text-white flex items-center justify-center gap-3">
                <span class="w-3 h-8 bg-gradient-to-b from-emerald-400 to-teal-500 rounded-full"></span>
                คณะสีที่ร่วมประชันชัย
            </h1>
            <p class="text-slate-400 text-xs sm:text-sm mt-2 max-w-xl mx-auto">
                รายชื่อคณะสี สัญลักษณ์ประจำสี และข้อมูลการแข่งขันกีฬาโรงเรียนพิชัย
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
            <?php foreach ($leaderboard as $row): ?>
                <?php include __DIR__ . '/components/house_card.php'; ?>
            <?php endforeach; ?>
        </div>
    </main>

    <?php include __DIR__ . '/components/footer.php'; ?>
</body>
</html>
