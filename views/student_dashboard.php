<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบนักเรียนนักกีฬา - Phichai SportDay</title>
    <!-- Tailwind CSS v4 Browser CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- Font Awesome v6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- jQuery + Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        /* ── Select2 Dark Theme (matches SportDay) ─────────────────────────── */
        .select2-container--default .select2-selection--single {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 0.75rem;
            height: 2.6rem;
            display: flex;
            align-items: center;
            transition: border-color .2s, box-shadow .2s;
        }
        .select2-container--default.select2-container--focus .select2-selection--single,
        .select2-container--default.select2-container--open .select2-selection--single {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99,102,241,0.15);
            background: rgba(255,255,255,0.05);
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #f1f5f9;
            font-family: 'Mali', cursive;
            font-size: .875rem;
            line-height: 2.6rem;
            padding-left: .875rem;
        }
        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #64748b;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 2.6rem;
            right: .5rem;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: #64748b transparent transparent transparent;
        }
        .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
            border-color: transparent transparent #6366f1 transparent;
        }
        /* Dropdown panel */
        .select2-dropdown {
            background: #0d1022;
            border: 1px solid rgba(99,102,241,0.25);
            border-radius: .75rem;
            box-shadow: 0 8px 32px rgba(0,0,0,0.55);
            font-family: 'Mali', cursive;
            font-size: .875rem;
            overflow: hidden;
        }
        .select2-container--default .select2-search--dropdown {
            padding: .5rem;
        }
        .select2-container--default .select2-search--dropdown .select2-search__field {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(99,102,241,0.25);
            border-radius: .5rem;
            color: #f1f5f9;
            font-family: 'Mali', cursive;
            padding: .4rem .75rem;
            outline: none;
            width: 100%;
        }
        .select2-container--default .select2-search--dropdown .select2-search__field:focus {
            border-color: #6366f1;
        }
        .select2-results__option {
            color: #94a3b8;
            padding: .55rem .875rem;
            font-size: .8rem;
        }
        .select2-results__option--highlighted {
            background: rgba(99,102,241,0.2) !important;
            color: #f1f5f9 !important;
        }
        .select2-results__option[aria-selected=true] {
            background: rgba(99,102,241,0.12);
            color: #a5b4fc;
        }
        .select2-container { width: 100% !important; }
        .select2-container .select2-selection--single {
            width: 100% !important;
            box-sizing: border-box;
        }
        .select2-container .select2-selection--single .select2-selection__rendered {
            width: 100% !important;
            box-sizing: border-box;
            padding-right: 2rem;
        }
    </style>
    <style type="text/tailwindcss">
        @theme {
            --font-heading: 'Itim', cursive;
            --font-body: 'Mali', cursive;
        }
        .tab-btn {
            border-bottom: 3px solid transparent;
            color: #94a3b8;
            transition: all 0.25s ease;
        }
        .tab-btn.active {
            border-bottom-color: #6366f1;
            color: #ffffff;
            text-shadow: 0 0 8px rgba(99, 102, 241, 0.4);
        }
        .tab-btn:hover {
            color: #e2e8f0;
        }
    </style>
</head>
<body class="text-slate-100 font-body min-h-screen">

<!-- Header Navbar -->
<header class="app-header sticky top-0 z-50 bg-[#070913]/85 backdrop-blur-xl border-b border-white/5 shadow-md">
    <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
        <a href="index.php" class="brand-logo text-xl md:text-2xl font-black flex items-center gap-2 hover:scale-102 transition-transform duration-300">
            <i class="fa-solid fa-trophy text-[#d4af37]"></i>
            <span class="bg-gradient-to-r from-white to-slate-400 bg-clip-text text-transparent">Phichai SportDay</span>
        </a>
        <div class="flex items-center gap-4">
            <span class="hidden sm:inline-flex bg-gradient-to-r from-indigo-500/10 to-purple-600/10 text-[#cbd5e1] border border-indigo-500/20 text-xs font-bold px-3.5 py-1.5 rounded-full shadow-md">ระบบนักเรียนนักกีฬา</span>
            <div class="flex items-center gap-2 pl-3 border-l border-white/5">
                <a href="index.php?route=login&action=logout" class="bg-white/5 hover:bg-white/10 text-white border border-white/5 hover:border-white/10 font-bold px-3.5 py-1.5 rounded-xl text-xs transition-all duration-200">ออกจากระบบ</a>
            </div>
        </div>
    </div>
</header>

<main class="max-w-6xl mx-auto px-4 py-8">
    <!-- Flash alerts rendered via UtilController (SweetAlert2) -->


    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
        
        <!-- Left Column: Profile & Medals & Registrations (Takes 3/5 width) -->
        <div class="lg:col-span-3 flex flex-col gap-8">
            
            <!-- Profile Card -->
            <?php
                $houseNameTh = '';
                if ($athlete) {
                    $houseNameTh = $presenter->getHouseNameTh($athlete['house_name']);
                }
            ?>
            <div class="bg-slate-900/60 backdrop-blur-xl border border-white/5 rounded-[24px] p-6 shadow-lg flex flex-col sm:flex-row items-center gap-6 relative overflow-hidden transition-all duration-300 hover:border-white/10 <?= $athlete ? 'border-l-6 border-l-[var(--house-color)]' : '' ?>" <?= $athlete ? $presenter->getHouseStyle($athlete['color_code']) : '' ?>>
                <?php if ($athlete): ?>
                    <!-- Ambient glow for house card -->
                    <div class="absolute -right-10 -top-10 w-32 h-32 rounded-full bg-[rgba(var(--house-color-rgb),0.15)] blur-2xl pointer-events-none"></div>
                <?php endif; ?>
                
                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full flex items-center justify-center font-bold text-3xl sm:text-4xl text-white shadow-lg shrink-0 select-none <?= $athlete ? 'bg-gradient-to-br from-[var(--house-color)] to-indigo-900 shadow-[0_0_15px_rgba(var(--house-color-rgb),0.4)]' : 'bg-gradient-to-br from-indigo-500 to-purple-600 shadow-indigo-500/20' ?>">
                    <?= mb_substr($_SESSION['user']['name'], 0, 1, 'UTF-8') ?>
                </div>
                
                <div class="text-center sm:text-left flex-1 min-w-0">
                    <h2 class="text-2xl font-black text-white mb-1 tracking-wide truncate"><?= htmlspecialchars($student_name) ?></h2>
                    <p class="text-slate-400 text-xs sm:text-sm font-semibold mb-3">รหัสประจำตัวนักเรียน: <?= htmlspecialchars($student_id) ?></p>
                    
                    <?php if ($athlete): ?>
                        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
                            <?php if ($isRepresentative): ?>
                                <span class="inline-flex bg-[rgba(var(--house-color-rgb),0.1)] text-[var(--house-color)] border border-[rgba(var(--house-color-rgb),0.25)] text-xs font-bold px-3 py-1 rounded-full uppercase">
                                    <i class="fa-solid fa-user-shield mr-1.5"></i>ตัวแทนสี: <?= htmlspecialchars($houseNameTh) ?>
                                </span>
                            <?php else: ?>
                                <span class="inline-flex bg-[rgba(var(--house-color-rgb),0.1)] text-[var(--house-color)] border border-[rgba(var(--house-color-rgb),0.25)] text-xs font-bold px-3 py-1 rounded-full uppercase">
                                    <i class="fa-solid fa-users mr-1.5"></i>สมาชิกคณะสี: <?= htmlspecialchars($houseNameTh) ?>
                                </span>
                            <?php endif; ?>
                            <span class="text-xs text-slate-400 font-medium"><i class="fa-solid fa-circle-check text-green-400 mr-1"></i>ห้อง ม.<?= $athlete['grade_level'] ?>/<?= $athlete['room_number'] ?> สังกัดเรียบร้อย</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (!$athlete): ?>
                <!-- Not Registered Warning -->
                <div class="bg-slate-900/60 backdrop-blur-xl border border-white/5 rounded-[24px] p-6 md:p-8 text-center shadow-lg relative overflow-hidden">
                    <div class="w-14 h-14 rounded-full bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center mx-auto mb-4 text-indigo-400 text-2xl shadow-inner">
                        <i class="fa-solid fa-circle-info"></i>
                    </div>
                    <h3 class="text-xl font-bold text-indigo-300 mb-2">บัญชีผู้ใช้นักเรียนทั่วไป</h3>
                    <p class="text-slate-400 text-xs sm:text-sm max-w-md mx-auto leading-relaxed">
                        ยินดีต้อนรับ! ห้องเรียนของคุณยังไม่ได้ถูกจัดสังกัดคณะสีโดยครูผู้ดูแลระบบ คุณจึงมีสิทธิ์เป็นผู้ใช้นักเรียนทั่วไปในการเข้าติดตามผลสรุปคะแนนและตารางการแข่งขันได้ทางคอลัมน์ด้านขวา
                    </p>
                </div>
            <?php else: ?>
                
                <?php if ($isRepresentative): ?>
                    <!-- Tab Switching Navigation for Representatives -->
                    <div class="flex gap-1 overflow-x-auto border-b border-white/5 pb-1 scrollbar-none">
                        <button class="tab-btn active px-4 py-3 text-sm font-bold whitespace-nowrap cursor-pointer flex items-center gap-2" onclick="switchTab('my-stats-tab')">
                            <i class="fa-solid fa-user-tag text-indigo-400"></i>
                            เกียรติประวัติส่วนตัว
                        </button>
                        <button class="tab-btn px-4 py-3 text-sm font-bold whitespace-nowrap cursor-pointer flex items-center gap-2" onclick="switchTab('manage-house-tab')">
                            <i class="fa-solid fa-users-gear text-purple-400"></i>
                            ลงสมัครกีฬาคณะสี
                        </button>
                    </div>
                <?php endif; ?>

                <div class="tab-content mt-6">
                    <!-- Tab A: Personal Achievements -->
                    <div id="my-stats-tab" class="<?= $isRepresentative ? 'tab-pane active' : '' ?> flex flex-col gap-8">
                        <!-- Enrolled Events Section -->
                        <div>
                            <h3 class="text-base font-bold text-white mb-4 flex items-center gap-2">
                                <i class="fa-solid fa-circle-nodes text-indigo-400"></i>
                                รายการแข่งขันของฉัน
                            </h3>
                            
                            <?php if (empty($registrations)): ?>
                                <div class="bg-slate-900/40 backdrop-blur-md border border-white/5 rounded-2xl p-8 text-center text-slate-400 text-sm">
                                    คุณยังไม่ได้ลงสมัครแข่งขันในกีฬาชนิดใดเลย
                                </div>
                            <?php else: ?>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <?php foreach ($registrations as $reg): ?>
                                        <div class="bg-slate-900/40 backdrop-blur-md border border-white/5 rounded-2xl p-4 flex items-center justify-between hover:translate-y-[-2px] hover:border-white/10 hover:shadow-lg transition-all duration-300">
                                            <div class="min-w-0 pr-2">
                                                <strong class="block text-sm sm:text-base text-white font-bold truncate mb-0.5"><?= htmlspecialchars($reg['sport_name']) ?></strong>
                                                <span class="text-xs text-slate-400 font-semibold block truncate">หมวดหมู่: <?= htmlspecialchars($reg['category']) ?></span>
                                            </div>
                                            <span class="inline-flex bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 text-[10px] font-black px-2.5 py-1 rounded-full whitespace-nowrap">มีสิทธิ์ลงแข่ง</span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Achievements / Medals Section -->
                        <div>
                            <h3 class="text-base font-bold text-white mb-4 flex items-center gap-2">
                                <i class="fa-solid fa-award text-yellow-500"></i>
                                เกียรติประวัติและพิมพ์เกียรติบัตรรางวัล
                            </h3>
                            
                            <?php if (empty($medals)): ?>
                                <div class="bg-slate-900/40 backdrop-blur-md border border-white/5 rounded-2xl p-8 text-center text-slate-400 text-xs sm:text-sm leading-relaxed">
                                    ยังไม่มีข้อมูลรางวัลเกียรติบัตรบันทึกไว้ในแมตช์การแข่งขันของคุณ
                                </div>
                            <?php else: ?>
                                <div class="flex flex-col gap-4">
                                    <?php foreach ($medals as $medal): ?>
                                        <div class="bg-slate-900/40 backdrop-blur-md border border-white/5 border-l-4 border-l-[var(--house-color)] rounded-2xl p-5 flex flex-col sm:flex-row justify-between sm:items-center gap-4 hover:translate-y-[-2px] transition-all duration-300" <?= $presenter->getHouseStyle($athlete['color_code']) ?>>
                                            <div class="min-w-0 flex-1">
                                                <h4 class="text-base sm:text-lg font-bold text-white mb-0.5 truncate font-heading"><?= htmlspecialchars($medal['sport_name']) ?></h4>
                                                <p class="text-xs text-slate-400 font-semibold mb-3">
                                                    หมวดหมู่: <?= htmlspecialchars($medal['category']) ?> • บันทึกคะแนนเมื่อ: <?= $presenter->formatDate($medal['event_date']) ?>
                                                </p>
                                                <div>
                                                    <?= $presenter->getMedalBadge($medal['medal']) ?>
                                                </div>
                                            </div>
                                            <div class="shrink-0 flex items-center">
                                                <a href="index.php?route=certificate&result_id=<?= $medal['result_id'] ?>" target="_blank" class="w-full sm:w-auto bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-bold px-4 py-2 rounded-xl text-xs flex items-center justify-center gap-1.5 shadow-md transition-all duration-200 cursor-pointer">
                                                    <i class="fa-solid fa-print"></i>
                                                    พิมพ์เกียรติบัตร
                                                </a>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if ($isRepresentative): ?>
                        <!-- Tab B: Color Athlete Registrations tools -->
                        <div id="manage-house-tab" class="tab-pane flex flex-col gap-8">
                            
                            <!-- Enroll Form -->
                            <div class="bg-slate-900/60 backdrop-blur-xl border border-white/5 rounded-[24px] p-6 shadow-lg">
                                <h3 class="text-base font-bold mb-2 flex items-center gap-2">
                                    <i class="fa-solid fa-user-plus text-purple-400"></i>
                                    ลงสมัครประเภทกีฬาให้สมาชิก
                                </h3>
                                <p class="text-slate-400 text-xs mb-5">
                                    ลงทะเบียนส่งสมาชิกในคณะ<?= htmlspecialchars($houseNameTh) ?>เข้าร่วมการแข่งขันกีฬาประเภทต่างๆ
                                </p>
                                
                                <form id="register-event-form" action="index.php?route=dashboard&action=register_event" method="POST">
                                    <!-- 1️⃣ เลือกประเภทกีฬาก่อน -->
                                    <div class="mb-4">
                                        <label class="block mb-2 text-xs font-bold text-slate-400 uppercase tracking-wider" for="sport_id">เลือกประเภทกีฬาที่เปิดแข่งขัน</label>
                                        <select name="sport_id" id="sport_id" class="w-full bg-white/3 border border-white/5 focus:border-indigo-500 focus:bg-white/5 focus:ring-3 focus:ring-indigo-500/15 rounded-xl py-2.5 px-3.5 text-white text-sm outline-none transition-all duration-200" required>
                                            <option value="">-- กรุณาเลือกประเภทกีฬา --</option>
                                            <?php foreach ($sports as $sport): ?>
                                                <option value="<?= $sport['id'] ?>"><?= htmlspecialchars($sport['sport_name']) ?> [<?= htmlspecialchars($sport['category']) ?>]</option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <!-- 2️⃣ เลือกนักกีฬา (มีปุ่มเพิ่ม/ลบ แถว) -->
                                    <div class="mb-6">
                                        <div class="flex justify-between items-center mb-2">
                                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">เลือกนักกีฬาในสี</label>
                                            <button type="button" id="add-athlete-btn" class="bg-indigo-500/10 hover:bg-indigo-500/20 text-indigo-400 font-bold px-3 py-1.5 rounded-lg text-xs flex items-center gap-1 transition-all duration-200 cursor-pointer">
                                                <i class="fa-solid fa-plus"></i>
                                                เพิ่มนักกีฬา
                                            </button>
                                        </div>

                                        <!-- Container สำหรับแถวนักกีฬา -->
                                        <div id="athlete-rows" class="flex flex-col gap-3">
                                            <!-- แถวเริ่มต้น -->
                                            <div class="athlete-row flex items-center gap-2">
                                                <div class="flex-1 min-w-0">
                                                    <select name="sports_day_athlete_id[]" class="athlete-select w-full" required>
                                                        <option value=""></option>
                                                        <?php foreach ($colorAthletes as $colAthlete): ?>
                                                            <option value="<?= $colAthlete['id'] ?>">
                                                                <?= htmlspecialchars($colAthlete['student_name']) ?> — รหัส <?= htmlspecialchars($colAthlete['student_id']) ?> [ม.<?= htmlspecialchars($colAthlete['grade_level']) ?>/<?= htmlspecialchars($colAthlete['room_number']) ?>]
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <button type="button" class="remove-athlete-row shrink-0 bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 p-2.5 rounded-xl text-sm transition-all duration-200 flex items-center justify-center cursor-pointer" title="ลบ">
                                                    <i class="fa-solid fa-xmark"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-2.5 rounded-xl text-sm transition-colors cursor-pointer">ลงทะเบียนเข้าแข่งขัน</button>
                                </form>

                                <!-- Template สำหรับ Clone แถวใหม่ -->
                                <template id="athlete-row-template">
                                    <div class="athlete-row flex items-center gap-2">
                                        <div class="flex-1 min-w-0">
                                            <select name="sports_day_athlete_id[]" class="athlete-select w-full" required>
                                                <option value=""></option>
                                                <?php foreach ($colorAthletes as $colAthlete): ?>
                                                    <option value="<?= $colAthlete['id'] ?>">
                                                        <?= htmlspecialchars($colAthlete['student_name']) ?> — รหัส <?= htmlspecialchars($colAthlete['student_id']) ?> [ม.<?= htmlspecialchars($colAthlete['grade_level']) ?>/<?= htmlspecialchars($colAthlete['room_number']) ?>]
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <button type="button" class="remove-athlete-row shrink-0 bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 p-2.5 rounded-xl text-sm transition-all duration-200 flex items-center justify-center cursor-pointer" title="ลบ">
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
                                    </div>
                                </template>
                            </div>

                            <!-- Summary lookup list for this house color -->
                            <div class="bg-slate-900/60 backdrop-blur-xl border border-white/5 rounded-[24px] p-6 shadow-lg">
                                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                                    <h3 class="text-base font-bold flex items-center gap-2">
                                        <i class="fa-solid fa-list-check text-indigo-400"></i>
                                        สรุปการลงทะเบียนประเภทกีฬา
                                    </h3>
                                    <div class="w-full sm:w-auto">
                                        <select id="lookup-sport-select" class="w-full sm:w-auto bg-white/3 border border-white/5 focus:border-indigo-500 focus:bg-white/5 focus:ring-3 focus:ring-indigo-500/15 rounded-xl py-2 px-3 text-white text-xs outline-none transition-all duration-200" onchange="loadSportRegistrations(this.value)">
                                            <option value="">-- กรองประเภทกีฬา --</option>
                                            <?php foreach ($sports as $sport): ?>
                                                <option value="<?= $sport['id'] ?>"><?= htmlspecialchars($sport['sport_name']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="overflow-x-auto w-full rounded-xl border border-white/5">
                                    <table class="w-full text-left border-collapse text-sm">
                                        <thead>
                                            <tr class="bg-white/2 border-b border-white/5">
                                                <th class="p-3 text-slate-400 font-bold text-xs uppercase tracking-wider">ชื่อนักกีฬา</th>
                                                <th class="p-3 text-slate-400 font-bold text-xs uppercase tracking-wider">คณะสี</th>
                                                <th class="p-3 text-slate-400 font-bold text-xs uppercase tracking-wider">การจัดการ</th>
                                            </tr>
                                        </thead>
                                        <tbody id="registrations-lookup-body">
                                            <tr>
                                                <td colspan="3" class="p-8 text-center text-slate-500">กรุณาเลือกประเภทกีฬาด้านบนเพื่อเรียกแสดงรายชื่อ</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    <?php endif; ?>
                </div>

            <?php endif; ?>
        </div>

        <!-- Right Column: Standing and Schedule (Takes 2/5 width) -->
        <div class="lg:col-span-2 flex flex-col gap-8">
            
            <!-- Standing Table Card -->
            <div class="bg-slate-900/60 backdrop-blur-xl border border-white/5 rounded-[24px] p-6 shadow-lg">
                <h3 class="text-lg font-bold text-white mb-6 flex items-center gap-2 justify-center font-heading">
                    <i class="fa-solid fa-ranking-star text-[#d4af37]"></i>
                    ตารางคะแนนสะสมคณะสี
                </h3>
                <div class="flex flex-col gap-3">
                    <?php $rank = 1; foreach ($leaderboard as $row): 
                        $houseNameTh = $presenter->getHouseNameTh($row['house_name']);
                    ?>
                        <div class="flex justify-between items-center p-3.5 rounded-xl border border-white/5 bg-white/[0.01] hover:bg-white/[0.02] border-l-4 border-l-[var(--house-color)] transition-all duration-200" <?= $presenter->getHouseStyle($row['color_code']) ?>>
                            <div class="flex items-center gap-3">
                                <div class="text-base font-black text-slate-400 w-5 text-center"><?= $rank++ ?></div>
                                <div>
                                    <div class="font-bold text-white text-sm mb-0.5"><?= htmlspecialchars($houseNameTh) ?></div>
                                    <div class="flex flex-wrap gap-1">
                                        <span class="inline-flex bg-yellow-500/10 text-yellow-400 border border-yellow-500/20 text-[9px] font-bold px-1.5 py-0.5 rounded-full">ทอง: <?= $row['gold_count'] ?></span>
                                        <span class="inline-flex bg-slate-300/10 text-slate-300 border border-slate-300/20 text-[9px] font-bold px-1.5 py-0.5 rounded-full">เงิน: <?= $row['silver_count'] ?></span>
                                        <span class="inline-flex bg-orange-500/10 text-orange-400 border border-orange-500/20 text-[9px] font-bold px-1.5 py-0.5 rounded-full">ทองแดง: <?= $row['bronze_count'] ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-sm font-black text-indigo-400 shrink-0 ml-2"><?= htmlspecialchars($row['total_points']) ?> คะแนน</div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Schedule Card -->
            <div class="bg-slate-900/60 backdrop-blur-xl border border-white/5 rounded-[24px] p-6 shadow-lg">
                <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2 font-heading">
                    <i class="fa-solid fa-calendar-days text-indigo-400"></i>
                    ตารางการแข่งขันกีฬา
                </h3>
                
                <div class="max-h-[350px] overflow-y-auto pr-1 flex flex-col gap-3 scrollbar-thin scrollbar-thumb-white/10">
                    <?php if (empty($matches)): ?>
                        <p class="text-slate-500 text-xs text-center py-6 font-semibold">ไม่มีตารางการแข่งขันบันทึกไว้</p>
                    <?php else: ?>
                        <?php foreach ($matches as $match): ?>
                            <div class="bg-slate-900/40 backdrop-blur-md border border-white/5 rounded-xl p-4 flex justify-between items-center hover:translate-x-1 transition-transform duration-300">
                                <div class="min-w-0 pr-2">
                                    <strong class="block text-sm text-white font-bold truncate mb-0.5 font-heading"><?= htmlspecialchars($match['sport_name']) ?></strong>
                                    <span class="text-[11px] text-slate-400 font-semibold block truncate">
                                        <i class="fa-regular fa-clock mr-1"></i><?= $presenter->formatDate($match['event_date']) ?>
                                    </span>
                                </div>
                                <div class="shrink-0 ml-2">
                                    <?php if ($match['status'] === 'Completed'): ?>
                                        <span class="inline-flex bg-green-500/10 text-green-400 border border-green-500/15 text-[10px] font-bold px-2 py-0.5 rounded-full">เสร็จสิ้น</span>
                                    <?php elseif ($match['status'] === 'Live'): ?>
                                        <span class="inline-flex bg-rose-500/10 text-rose-400 border border-rose-500/25 text-[10px] font-bold px-2 py-0.5 rounded-full items-center"><span class="live-pulse"></span>กำลังแข่ง</span>
                                    <?php else: ?>
                                        <span class="inline-flex bg-slate-800 text-slate-400 border border-white/5 text-[10px] font-bold px-2 py-0.5 rounded-full">รอแข่ง</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

        </div>

    </div>
</main>

<script>
    function switchTab(tabId) {
        const panes = document.querySelectorAll('.tab-pane');
        panes.forEach(pane => pane.classList.remove('active'));

        const buttons = document.querySelectorAll('.tab-btn');
        buttons.forEach(btn => btn.classList.remove('active'));

        document.getElementById(tabId).classList.add('active');
        
        buttons.forEach(btn => {
            if (btn.getAttribute('onclick').includes(tabId)) {
                btn.classList.add('active');
            }
        });

        // Initialize Select2 dynamically when switching to manage-house-tab to avoid 0-width bugs
        if (tabId === 'manage-house-tab') {
            $('#athlete-rows .athlete-select').each(function() {
                if ($(this).data('select2')) {
                    $(this).select2('destroy');
                }
                initSelect2Row(this);
            });
        }
    }

    function loadSportRegistrations(sportId) {
        const body = document.getElementById('registrations-lookup-body');
        if (!sportId) {
            body.innerHTML = '<tr><td colspan="3" class="p-8 text-center text-slate-500">กรุณาเลือกประเภทกีฬาด้านบนเพื่อเรียกแสดงรายชื่อ</td></tr>';
            return;
        }

        body.innerHTML = '<tr><td colspan="3" class="p-8 text-center text-slate-500">กำลังดึงข้อมูลรายชื่อนักกีฬา...</td></tr>';

        fetch('index.php?route=get_sport_regs&sport_id=' + sportId)
            .then(res => res.json())
            .then(data => {
                body.innerHTML = '';
                if (data.length === 0) {
                    body.innerHTML = '<tr><td colspan="3" class="p-8 text-center text-slate-500">ยังไม่มีรายชื่อนักกีฬาลงทะเบียนแข่งขันในรายการนี้</td></tr>';
                } else {
                    const userHouseId = <?= $athlete ? $athlete['house_id'] : 'null' ?>;

                    data.forEach(reg => {
                        const tr = document.createElement('tr');
                        tr.className = 'border-b border-white/[0.03] hover:bg-white/[0.01] transition-colors house-indicator';
                        
                        let houseNameTh = getHouseNameTh(reg.house_name);

                        tr.setAttribute('style', `--house-color: ${reg.color_code}; --house-color-rgb: ${hexToRgb(reg.color_code)};`);
                        
                        let actionHtml = '';
                        if (userHouseId && reg.house_id == userHouseId) {
                            actionHtml = `
                                <form id="del-reg-${reg.registration_id}" action="index.php?route=dashboard&action=remove_event_reg" method="POST">
                                    <input type="hidden" name="registration_id" value="${reg.registration_id}">
                                    <button type="button" class="bg-rose-500/10 hover:bg-rose-500/20 text-rose-300 border border-rose-500/20 px-3 py-1 rounded-xl text-xs transition-colors cursor-pointer"
                                        onclick="swalConfirm('del-reg-${reg.registration_id}', '\u0e16\u0e2d\u0e19\u0e15\u0e31\u0e27\u0e19\u0e31\u0e01\u0e01\u0e35\u0e2c\u0e32', '\u0e16\u0e2d\u0e19\u0e23\u0e32\u0e22\u0e0a\u0e37\u0e48\u0e2d\u0e19\u0e31\u0e01\u0e01\u0e35\u0e2c\u0e32\u0e04\u0e19\u0e19\u0e35\u0e49\u0e2d\u0e2d\u0e01\u0e08\u0e32\u0e01\u0e01\u0e32\u0e23\u0e41\u0e02\u0e48\u0e07\u0e02\u0e31\u0e19\u0e2b\u0e23\u0e37\u0e2d\u0e44\u0e21\u0e48?')"
                                    >\u0e16\u0e2d\u0e19\u0e15\u0e31\u0e27</button>
                                </form>
                            `;
                        } else {
                            actionHtml = '<span class="text-slate-600 text-[11px] font-semibold">เฉพาะสีของตน</span>';
                        }

                        tr.innerHTML = `
                            <td class="p-3.5">
                                <strong class="block text-white text-xs sm:text-sm">${reg.student_name}</strong>
                                <span class="text-[10px] text-slate-400">รหัส: ${reg.student_id}</span>
                            </td>
                            <td class="p-3.5">
                                <span class="badge house-badge text-[11px]">${houseNameTh}</span>
                            </td>
                            <td class="p-3.5">${actionHtml}</td>
                        `;
                        body.appendChild(tr);
                    });
                }
            });
    }

    function getHouseNameTh(houseName) {
        if (!houseName) return '';
        if (stripos(houseName, 'purple') || stripos(houseName, 'ม่วง')) return 'สีม่วง';
        if (stripos(houseName, 'green') || stripos(houseName, 'เขียว')) return 'สีเขียว';
        if (stripos(houseName, 'orange') || stripos(houseName, 'แสด') || stripos(houseName, 'ส้ม')) return 'สีแสด';
        if (stripos(houseName, 'light blue') || stripos(houseName, 'sky') || stripos(houseName, 'ฟ้า')) return 'สีฟ้า';
        if (stripos(houseName, 'blue') || stripos(houseName, 'น้ำเงิน')) return 'สีน้ำเงิน';
        if (stripos(houseName, 'pink') || stripos(houseName, 'ชมพู')) return 'สีชมพู';
        if (stripos(houseName, 'red') || stripos(houseName, 'แดง')) return 'สีแดง';
        if (stripos(houseName, 'yellow') || stripos(houseName, 'เหลือง')) return 'สีเหลือง';
        return houseName;
    }

    function hexToRgb(hex) {
        hex = hex.replace('#', '');
        let r, g, b;
        if (hex.length === 3) {
            r = parseInt(hex.substring(0, 1) + hex.substring(0, 1), 16);
            g = parseInt(hex.substring(1, 1) + hex.substring(1, 1), 16);
            b = parseInt(hex.substring(2, 1) + hex.substring(2, 1), 16);
        } else {
            r = parseInt(hex.substring(0, 2), 16);
            g = parseInt(hex.substring(2, 4), 16);
            b = parseInt(hex.substring(4, 6), 16);
        }
        return `${r}, ${g}, ${b}`;
    }

    function stripos(haystack, needle) {
        return haystack.toLowerCase().indexOf(needle.toLowerCase()) !== -1;
    }
</script>

<script>
// ── Select2: Dynamic athlete rows ─────────────────────────────────────────
var SELECT2_OPTS = {
    placeholder: 'ค้นหาชื่อหรือรหัสนักกีฬา...',
    allowClear: true,
    width: '100%',
    language: {
        noResults: function () { return 'ไม่พบนักกีฬาที่ค้นหา'; },
        searching: function () { return 'กำลังค้นหา...'; },
    },
};

function initSelect2Row(sel) {
    $(sel).select2(SELECT2_OPTS);
    // Select2 injects inline style="width: Xpx" — force override to fill flex parent
    $(sel).next('.select2-container').attr('style', 'width: 100%');
}

function updateRemoveButtons() {
    var rows = $('#athlete-rows .athlete-row');
    rows.each(function () {
        var btn = $(this).find('.remove-athlete-row');
        if (rows.length <= 1) {
            btn.addClass('opacity-0 pointer-events-none');
        } else {
            btn.removeClass('opacity-0 pointer-events-none');
        }
    });
}

$(document).ready(function () {
    // Init Select2 on the first row
    initSelect2Row($('#athlete-rows .athlete-select').first());

    // Add new row
    $('#add-athlete-btn').on('click', function () {
        var tmpl = document.getElementById('athlete-row-template');
        var clone = document.importNode(tmpl.content, true);
        $('#athlete-rows').append(clone);
        initSelect2Row($('#athlete-rows .athlete-row:last .athlete-select'));
        updateRemoveButtons();
    });

    // Remove row (event delegation)
    $('#athlete-rows').on('click', '.remove-athlete-row', function () {
        var row = $(this).closest('.athlete-row');
        var sel = row.find('.athlete-select');
        if (sel.data('select2')) { sel.select2('destroy'); }
        row.remove();
        updateRemoveButtons();
    });

    updateRemoveButtons();
});
</script>

<script>
/**
 * swalConfirm — แทน native confirm() ด้วย SweetAlert2 popup แบบ dark-theme
 */
function swalConfirm(formId, title, text) {
    Swal.fire({
        icon:               'warning',
        title:              title,
        text:               text,
        background:         '#0d1022',
        color:              '#f1f5f9',
        iconColor:          '#f59e0b',
        showCancelButton:   true,
        confirmButtonText:  '\u2713 \u0e22\u0e37\u0e19\u0e22\u0e31\u0e19',
        cancelButtonText:   '\u00d7 \u0e22\u0e01\u0e40\u0e25\u0e34\u0e01',
        confirmButtonColor: '#f43f5e',
        cancelButtonColor:  'rgba(255,255,255,0.06)',
        customClass: {
            popup:         'swal-sportday-popup',
            title:         'swal-sportday-title',
            htmlContainer: 'swal-sportday-text',
            confirmButton: 'swal-sportday-btn',
            cancelButton:  'swal-sportday-btn-cancel',
        },
        showClass: { popup: 'swal-fade-in' },
        hideClass: { popup: 'swal-fade-out' },
    }).then(function (result) {
        if (result.isConfirmed) {
            document.getElementById(formId).submit();
        }
    });
}
</script>

<?php UtilController::renderFlashJS(); ?>
</body>
</html>
