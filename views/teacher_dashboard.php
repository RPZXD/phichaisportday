<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบจัดการอาจารย์ผู้ดูแล - SportDay</title>
    <!-- Tailwind CSS v4 Browser CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- Font Awesome v6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="assets/style.css">
    <style type="text/tailwindcss">
        @theme {
            --font-heading: 'Itim', cursive;
            --font-body: 'Mali', cursive;
        }
        @keyframes pulseSlow {
            0%, 100% { transform: scale(1) translate(0px, 0px); opacity: 0.6; }
            50% { transform: scale(1.12) translate(10px, -15px); opacity: 0.85; }
        }
        @keyframes pulseSlowReverse {
            0%, 100% { transform: scale(1.08) translate(0px, 0px); opacity: 0.8; }
            50% { transform: scale(0.95) translate(-15px, 15px); opacity: 0.55; }
        }
        .animate-pulse-slow {
            animation: pulseSlow 9s ease-in-out infinite;
        }
        .animate-pulse-slow-reverse {
            animation: pulseSlowReverse 11s ease-in-out infinite;
        }
        .tabs-container {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            padding: 0.375rem;
            border-radius: 1.25rem;
            border: 1px solid rgba(255, 255, 255, 0.05);
            margin-bottom: 2rem;
        }
        .tab-btn {
            flex: 1;
            min-w: calc(50% - 0.25rem); /* 2 columns on mobile */
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.625rem 0.875rem;
            font-size: 0.75rem; /* text-xs */
            font-weight: 700;
            color: #94a3b8;
            background: transparent;
            border: 1px solid transparent;
            border-radius: 0.875rem;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            white-space: nowrap;
        }
        @media (min-width: 640px) {
            .tab-btn {
                font-size: 0.825rem;
                padding: 0.75rem 1rem;
            }
        }
        @media (min-width: 768px) {
            .tab-btn {
                min-w: calc(33.333% - 0.375rem); /* 3 columns on tablet */
            }
        }
        @media (min-width: 1024px) {
            .tabs-container {
                flex-wrap: nowrap;
            }
            .tab-btn {
                min-w: 0;
            }
        }
        .tab-btn.active {
            background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2);
            border-color: rgba(255, 255, 255, 0.1);
        }
        .tab-btn:hover:not(.active) {
            color: #f1f5f9;
            background: rgba(255, 255, 255, 0.05);
        }

        /* Custom Select element styling to look premium and consistent on mobile */
        select {
            appearance: none !important;
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            background-color: #0b0f19 !important;
            color: #ffffff !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            border-radius: 0.75rem !important;
            padding: 0.5rem 2.25rem 0.5rem 0.75rem !important; /* Spacing for custom arrow */
            background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><polyline points='6 9 12 15 18 9'></polyline></svg>") !important;
            background-repeat: no-repeat !important;
            background-position: right 0.75rem center !important;
            background-size: 1rem !important;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        select:focus {
            border-color: #6366f1 !important; /* indigo-500 */
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15) !important;
            outline: none !important;
        }
        select option {
            background-color: #0d1022 !important;
            color: #f1f5f9 !important;
        }
    </style>
</head>
<body class="text-slate-100 font-body min-h-screen relative overflow-x-hidden">

<?php include __DIR__ . '/components/ambient_orbs.php'; ?>
<?php include __DIR__ . '/components/header.php'; ?>

<div class="max-w-6xl mx-auto px-4 py-8 relative z-10">
    <div class="mb-8">
        <h2 class="text-2xl md:text-3xl font-black text-white mb-2">แผงควบคุมการจัดการกีฬาสี</h2>
        <p class="text-slate-400 text-sm md:text-base">จัดการคณะสีให้ห้องเรียน แต่งตั้งตัวแทนสีนักเรียน กำหนดตารางการแข่ง และบันทึกคะแนนสรุป</p>
    </div>

    <!-- Flash alerts rendered via UtilController (SweetAlert2) -->

    <?php
    $uncreated_count = 0;
    foreach ($sports as $s) {
        if (!isset($s['bracket_count']) || $s['bracket_count'] == 0) {
            $uncreated_count++;
        }
    }
    ?>

    <!-- Responsive Tabs Navigation -->
    <div class="tabs-container">
        <button class="tab-btn active" onclick="switchTab('athletes-tab')">
            <i class="fa-solid fa-users-gear text-indigo-400"></i>
            จัดการคณะสีและตัวแทน
        </button>
        <button class="tab-btn" onclick="switchTab('enroll-tab')">
            <i class="fa-solid fa-file-signature text-purple-400"></i>
            ชนิดกีฬาและการสมัคร
        </button>
        <button class="tab-btn" onclick="switchTab('matches-tab')">
            <i class="fa-solid fa-calendar-check text-rose-400"></i>
            ตารางแข่งและลงคะแนน
        </button>
        <button class="tab-btn" onclick="switchTab('leaderboard-tab')">
            <i class="fa-solid fa-ranking-star text-[#d4af37]"></i>
            ตารางสรุปคะแนน
        </button>
        <button class="tab-btn" onclick="switchTab('bracket-tab')">
            <i class="fa-solid fa-diagram-project text-teal-400"></i>
            สายการแข่งขัน (Bracket)
            <?php if ($uncreated_count > 0): ?>
                <span class="ml-1 px-1.5 py-0.5 text-[10px] font-bold bg-amber-500 text-slate-950 rounded-full"><?= $uncreated_count ?></span>
            <?php endif; ?>
        </button>
    </div>

    <!-- Tab Contents -->
    <div class="tab-content">
        <!-- Tab 1: Athlete Management (Re-arranged into Classroom Mapping and Representatives) -->
        <div id="athletes-tab" class="tab-pane active">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <!-- Column A: Classroom Color Mapping -->
                <div class="flex flex-col gap-6">
                    <!-- Classroom Assign Form -->
                    <div class="bg-slate-900/60 backdrop-blur-xl border border-white/5 rounded-[24px] p-6 md:p-8 shadow-lg">
                        <h3 class="text-xl font-bold mb-2 flex items-center gap-2">
                            <i class="fa-solid fa-school text-indigo-400"></i>
                            จัดคณะสีให้ห้องเรียน
                        </h3>
                        <p class="text-slate-400 text-xs md:text-sm mb-6">
                            กำหนดระดับชั้นและห้องเรียนเข้าสังกัดคณะสี นักเรียนทุกคนในห้องนั้นจะอยู่คณะสีที่จัดโดยอัตโนมัติ
                        </p>
                        
                        <form action="index.php?route=dashboard&action=assign_classroom" method="POST" class="flex flex-col gap-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block mb-1 text-xs font-bold text-slate-400 uppercase tracking-wider" for="grade_level">ระดับชั้น</label>
                                    <select name="grade_level" id="grade_level" class="w-full bg-white/3 border border-white/5 focus:border-indigo-500 focus:bg-white/5 rounded-xl py-2.5 px-3 text-white text-sm outline-none transition-all" required>
                                        <option value="">-- ระดับชั้น --</option>
                                        <option value="1">มัธยมศึกษาปีที่ 1 (ม.1)</option>
                                        <option value="2">มัธยมศึกษาปีที่ 2 (ม.2)</option>
                                        <option value="3">มัธยมศึกษาปีที่ 3 (ม.3)</option>
                                        <option value="4">มัธยมศึกษาปีที่ 4 (ม.4)</option>
                                        <option value="5">มัธยมศึกษาปีที่ 5 (ม.5)</option>
                                        <option value="6">มัธยมศึกษาปีที่ 6 (ม.6)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block mb-1 text-xs font-bold text-slate-400 uppercase tracking-wider" for="room_number">ห้องเรียน</label>
                                    <select name="room_number" id="room_number" class="w-full bg-white/3 border border-white/5 focus:border-indigo-500 focus:bg-white/5 rounded-xl py-2.5 px-3 text-white text-sm outline-none transition-all" required>
                                        <option value="">-- ห้อง --</option>
                                        <?php for($r=1; $r<=12; $r++): ?>
                                            <option value="<?= $r ?>">ห้อง <?= $r ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="block mb-1 text-xs font-bold text-slate-400 uppercase tracking-wider" for="assign_house_id">คณะสี</label>
                                <select name="house_id" id="assign_house_id" class="w-full bg-white/3 border border-white/5 focus:border-indigo-500 focus:bg-white/5 rounded-xl py-2.5 px-3 text-white text-sm outline-none transition-all" required>
                                    <option value="">-- กรุณาเลือกคณะสี --</option>
                                    <?php foreach ($houses as $house): 
                                        $houseNameTh = $presenter->getHouseNameTh($house['house_name']);
                                    ?>
                                        <option value="<?= $house['id'] ?>"><?= htmlspecialchars($houseNameTh) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 rounded-xl text-sm transition-colors cursor-pointer mt-1">บันทึกการจัดห้องเรียน</button>
                        </form>
                    </div>

                    <!-- Classroom Mappings Table -->
                    <div class="bg-slate-900/60 backdrop-blur-xl border border-white/5 rounded-[24px] p-6 md:p-8 shadow-lg">
                        <h3 class="text-xl font-bold mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-list-ul text-indigo-400"></i>
                            ตารางข้อมูลการจัดห้องเรียน
                        </h3>
                        <div class="overflow-x-auto w-full rounded-xl border border-white/5 max-h-[300px] overflow-y-auto">
                            <table class="w-full text-left border-collapse text-sm">
                                <thead>
                                    <tr class="bg-white/2 border-b border-white/5">
                                        <th class="p-3 text-slate-400 font-bold text-xs uppercase tracking-wider">ระดับชั้น/ห้อง</th>
                                        <th class="p-3 text-slate-400 font-bold text-xs uppercase tracking-wider">สังกัดสี</th>
                                        <th class="p-3 text-slate-400 font-bold text-xs uppercase tracking-wider">การจัดการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($classroom_mappings)): ?>
                                        <tr>
                                            <td colspan="3" class="p-8 text-center text-slate-500">ยังไม่มีข้อมูลการจัดห้องเรียนเข้าคณะสี</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($classroom_mappings as $mapping): 
                                            $houseNameTh = $presenter->getHouseNameTh($mapping['house_name']);
                                        ?>
                                            <tr class="border-b border-white/[0.03] hover:bg-white/[0.01] transition-colors house-indicator" <?= $presenter->getHouseStyle($mapping['color_code']) ?>>
                                                <td class="p-3.5">
                                                    <strong class="text-white text-base">ม.<?= $mapping['grade_level'] ?>/<?= $mapping['room_number'] ?></strong>
                                                </td>
                                                <td class="p-3.5">
                                                    <span class="badge house-badge text-[11px]"><?= htmlspecialchars($houseNameTh) ?></span>
                                                </td>
                                                <td class="p-3.5">
                                                    <form id="del-classroom-<?= $mapping['id'] ?>" action="index.php?route=dashboard&action=delete_classroom" method="POST">
                                                        <input type="hidden" name="mapping_id" value="<?= $mapping['id'] ?>">
                                                        <button type="button" class="bg-rose-500/10 hover:bg-rose-500/20 text-rose-300 border border-rose-500/20 hover:border-rose-500/30 px-3 py-1 rounded-xl text-xs transition-colors cursor-pointer"
                                                            onclick="swalConfirm('del-classroom-<?= $mapping['id'] ?>', '\u0e22\u0e01\u0e40\u0e25\u0e34\u0e01\u0e01\u0e32\u0e23\u0e08\u0e31\u0e14\u0e04\u0e13\u0e30\u0e2a\u0e35', '\u0e15\u0e49\u0e2d\u0e07\u0e01\u0e32\u0e23\u0e22\u0e01\u0e40\u0e25\u0e34\u0e01\u0e01\u0e32\u0e23\u0e08\u0e31\u0e14\u0e04\u0e13\u0e30\u0e2a\u0e35\u0e02\u0e2d\u0e07\u0e2b\u0e49\u0e2d\u0e07 \u0e21.<?= $mapping['grade_level'] ?>/<?= $mapping['room_number'] ?> \u0e2b\u0e23\u0e37\u0e2d\u0e44\u0e21\u0e48?')"
                                                        >ยกเลิก</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Column B: Color Representatives -->
                <div class="flex flex-col gap-6">
                    <!-- Appoint Representative Form -->
                    <div class="bg-slate-900/60 backdrop-blur-xl border border-white/5 rounded-[24px] p-6 md:p-8 shadow-lg">
                        <h3 class="text-xl font-bold mb-2 flex items-center gap-2">
                            <i class="fa-solid fa-user-shield text-purple-400"></i>
                            แต่งตั้งตัวแทนคณะสี
                        </h3>
                        <p class="text-slate-400 text-xs md:text-sm mb-6">
                            แต่งตั้งนักเรียนที่มีคณะสีตามห้องเรียน ให้ได้สิทธิ์เป็นตัวแทนสีสำหรับลงทะเบียนสมัครแข่งขันกีฬาประเภทต่างๆ
                        </p>

                        <div class="mb-4">
                            <label class="block mb-2 text-xs font-bold text-slate-400 uppercase tracking-wider" for="appoint-house-select">เลือกคณะสี</label>
                            <select id="appoint-house-select" class="w-full bg-white/3 border border-white/5 focus:border-indigo-500 focus:bg-white/5 focus:ring-3 focus:ring-indigo-500/15 rounded-xl py-2.5 px-3.5 text-white text-sm outline-none transition-all duration-200" onchange="onHouseChange()">
                                <option value="">-- กรุณาเลือกคณะสี --</option>
                                <?php foreach ($houses as $house): 
                                    $houseNameTh = $presenter->getHouseNameTh($house['house_name']);
                                ?>
                                    <option value="<?= $house['id'] ?>"><?= htmlspecialchars($houseNameTh) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-6 relative">
                            <label class="block mb-2 text-xs font-bold text-slate-400 uppercase tracking-wider" for="student-search-input">ค้นหาชื่อนักเรียน หรือ รหัสประจำตัว</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"><i class="fa-solid fa-magnifying-glass"></i></span>
                                <input type="text" id="student-search-input" class="w-full bg-white/3 border border-white/5 focus:border-indigo-500 focus:bg-white/5 focus:ring-3 focus:ring-indigo-500/15 rounded-xl py-3 pl-11 pr-4 text-white text-sm outline-none transition-all duration-200" placeholder="กรุณาเลือกคณะสีก่อนค้นหา..." oninput="doSearchStudent(this.value)" disabled>
                            </div>
                            <div id="search-results-list" class="bg-slate-950 border border-white/5 rounded-xl max-h-[220px] overflow-y-auto absolute w-full z-10 mt-1 hidden shadow-2xl"></div>
                        </div>

                        <!-- Hidden representative appointment form -->
                        <form id="athlete-registration-form" action="index.php?route=dashboard&action=register_athlete" method="POST" class="hidden">
                            <input type="hidden" name="student_id" id="register-student-id">
                            <input type="hidden" name="house_id" id="register-house-id">
                            <div class="bg-white/2 p-4 rounded-xl border border-white/5 mb-4">
                                <p style="font-weight: 700; color: #fff; font-size: 1.05rem;" id="selected-student-display"></p>
                                <p style="font-size: 0.8rem; color: #94a3b8;" id="selected-student-id-display"></p>
                                <p style="font-size: 0.85rem; color: #a5b4fc; font-weight: 600;" id="selected-student-house-display"></p>
                            </div>
                            <div class="flex gap-2 justify-end">
                                <button type="button" class="bg-white/5 hover:bg-white/10 text-white border border-white/5 font-bold px-4 py-2 rounded-xl text-xs transition-colors cursor-pointer" onclick="cancelRegistration()">ยกเลิก</button>
                                <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-bold px-4 py-2 rounded-xl text-xs transition-colors cursor-pointer">แต่งตั้งตัวแทนสี</button>
                            </div>
                        </form>
                    </div>

                    <!-- Representatives List Table -->
                    <div class="bg-slate-900/60 backdrop-blur-xl border border-white/5 rounded-[24px] p-6 md:p-8 shadow-lg">
                        <h3 class="text-xl font-bold mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-address-book text-purple-400"></i>
                            รายชื่อตัวแทนคณะสีทั้งหมด
                        </h3>
                        <div class="overflow-x-auto w-full rounded-xl border border-white/5 max-h-[300px] overflow-y-auto">
                            <table class="w-full text-left border-collapse text-sm">
                                <thead>
                                    <tr class="bg-white/2 border-b border-white/5">
                                        <th class="p-3 text-slate-400 font-bold text-xs uppercase tracking-wider">ตัวแทนคณะสี</th>
                                        <th class="p-3 text-slate-400 font-bold text-xs uppercase tracking-wider">สังกัดคณะสี</th>
                                        <th class="p-3 text-slate-400 font-bold text-xs uppercase tracking-wider">การจัดการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($representatives)): ?>
                                        <tr>
                                            <td colspan="3" class="p-8 text-center text-slate-500">ยังไม่มีตัวแทนสีได้รับการแต่งตั้ง</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($representatives as $rep): 
                                            $houseNameTh = $presenter->getHouseNameTh($rep['house_name']);
                                        ?>
                                            <tr class="border-b border-white/[0.03] hover:bg-white/[0.01] transition-colors house-indicator" <?= $presenter->getHouseStyle($rep['color_code']) ?>>
                                                <td class="p-3.5">
                                                    <strong class="block text-white text-sm"><?= htmlspecialchars($rep['student_name']) ?></strong>
                                                    <span class="text-xs text-slate-400">รหัส: <?= htmlspecialchars($rep['student_id']) ?></span>
                                                </td>
                                                <td class="p-3.5">
                                                    <span class="badge house-badge text-[11px]"><?= htmlspecialchars($houseNameTh) ?></span>
                                                </td>
                                                <td class="p-3.5">
                                                    <form id="del-athlete-<?= $rep['id'] ?>" action="index.php?route=dashboard&action=delete_athlete" method="POST">
                                                        <input type="hidden" name="athlete_id" value="<?= $rep['id'] ?>">
                                                        <button type="button" class="bg-rose-500/10 hover:bg-rose-500/20 text-rose-300 border border-rose-500/20 hover:border-rose-500/30 px-3 py-1 rounded-xl text-xs transition-colors cursor-pointer"
                                                            onclick="swalConfirm('del-athlete-<?= $rep['id'] ?>', '\u0e16\u0e2d\u0e14\u0e16\u0e2d\u0e19\u0e15\u0e31\u0e27\u0e41\u0e17\u0e19', '\u0e15\u0e49\u0e2d\u0e07\u0e01\u0e32\u0e23\u0e22\u0e01\u0e40\u0e25\u0e34\u0e01\u0e15\u0e33\u0e41\u0e2b\u0e19\u0e48\u0e07\u0e15\u0e31\u0e27\u0e41\u0e17\u0e19\u0e2a\u0e35\u0e19\u0e31\u0e01\u0e40\u0e23\u0e35\u0e22\u0e19\u0e04\u0e19\u0e19\u0e35\u0e49\u0e2b\u0e23\u0e37\u0e2d\u0e44\u0e21\u0e48?')"
                                                        >ถอดถอน</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab 2: Event Enrollment -->
        <div id="enroll-tab" class="tab-pane">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Forms column -->
                <div class="lg:col-span-1 flex flex-col gap-6">
                    <!-- Create Sport Form -->
                    <div class="bg-slate-900/60 backdrop-blur-xl border border-white/5 rounded-[24px] p-6 shadow-lg">
                        <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-plus-circle text-purple-400"></i>
                            เพิ่มประเภทกีฬาเข้าระบบ
                        </h3>
                        <form action="index.php?route=dashboard&action=add_sport" method="POST">
                            <div class="mb-4">
                                <label class="block mb-2 text-xs font-bold text-slate-400 uppercase tracking-wider" for="sport_name">ชื่อประเภทกีฬา</label>
                                <input type="text" name="sport_name" id="sport_name" class="w-full bg-white/3 border border-white/5 focus:border-indigo-500 focus:bg-white/5 focus:ring-3 focus:ring-indigo-500/15 rounded-xl py-2.5 px-3.5 text-white text-sm outline-none transition-all duration-200" placeholder="เช่น วิ่งผลัด 4x100 เมตร" required>
                            </div>
                            <div class="mb-6">
                                <label class="block mb-2 text-xs font-bold text-slate-400 uppercase tracking-wider" for="category">หมวดหมู่กีฬา</label>
                                <input type="text" name="category" id="category" class="w-full bg-white/3 border border-white/5 focus:border-indigo-500 focus:bg-white/5 focus:ring-3 focus:ring-indigo-500/15 rounded-xl py-2.5 px-3.5 text-white text-sm outline-none transition-all duration-200" placeholder="เช่น กรีฑา, ประเภทลู่" required>
                            </div>
                            <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-2.5 rounded-xl text-sm transition-colors cursor-pointer">บันทึกกีฬาใหม่</button>
                        </form>
                    </div>
                </div>

                <!-- Lookup List column -->
                <div class="lg:col-span-2 bg-slate-900/60 backdrop-blur-xl border border-white/5 rounded-[24px] p-6 md:p-8 shadow-lg">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                        <h3 class="text-xl font-bold flex items-center gap-2">
                            <i class="fa-solid fa-file-lines text-indigo-400"></i>
                            สรุปผู้สมัครลงแข่งกีฬา
                        </h3>
                        <div class="w-full sm:w-auto flex items-center gap-2">
                            <select id="lookup-sport-select" class="w-full sm:w-auto bg-white/3 border border-white/5 focus:border-indigo-500 focus:bg-white/5 focus:ring-3 focus:ring-indigo-500/15 rounded-xl py-2 px-3 text-white text-xs outline-none transition-all duration-200" onchange="loadSportRegistrations(this.value)">
                                <option value="">-- กรองตามประเภทกีฬา --</option>
                                <?php foreach ($sports as $sport): ?>
                                    <option value="<?= $sport['id'] ?>"><?= htmlspecialchars($sport['sport_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button type="button" onclick="printRegistrations()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-3 py-2 rounded-xl text-xs flex items-center gap-1.5 cursor-pointer shadow-md select-none shrink-0">
                                <i class="fa-solid fa-print"></i>
                                พิมพ์รายชื่อ
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto w-full rounded-xl border border-white/5">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                                <tr class="bg-white/2 border-b border-white/5">
                                    <th class="p-3 text-slate-400 font-bold text-xs uppercase tracking-wider">ชื่อนักกีฬา</th>
                                    <th class="p-3 text-slate-400 font-bold text-xs uppercase tracking-wider">คณะสี</th>
                                </tr>
                            </thead>
                            <tbody id="registrations-lookup-body">
                                <tr>
                                    <td colspan="2" class="p-8 text-center text-slate-500">กรุณาเลือกประเภทกีฬาด้านบนเพื่อเรียกแสดงรายชื่อ</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab 3: Matches & Scores -->
        <div id="matches-tab" class="tab-pane">
            <div class="grid grid-cols-1 gap-8">
                <!-- Matches List -->
                <div class="bg-slate-900/60 backdrop-blur-xl border border-white/5 rounded-[24px] p-6 md:p-8 shadow-lg">
                    <h3 class="text-xl font-bold mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-list-check text-indigo-400"></i>
                        ตารางแมตช์การแข่งขันทั้งหมด
                    </h3>

                    <!-- Filters -->
                    <div class="mb-5 grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label for="filter-sport" class="block mb-1 text-[11px] font-bold text-slate-400 uppercase tracking-wider">ประเภทกีฬา</label>
                            <select id="filter-sport" onchange="filterMatches()" class="w-full bg-white/3 border border-white/5 focus:border-indigo-500 focus:bg-white/5 rounded-xl py-2 px-3 text-white text-xs outline-none transition-all duration-200">
                                <option value="all">ทั้งหมด</option>
                                <?php 
                                $uniqueSports = [];
                                if (!empty($matches)) {
                                    foreach ($matches as $m) {
                                        $uniqueSports[$m['sport_id']] = $m['sport_name'];
                                    }
                                }
                                foreach ($uniqueSports as $id => $name) {
                                    echo '<option value="' . $id . '">' . htmlspecialchars($name) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div>
                            <label for="filter-category" class="block mb-1 text-[11px] font-bold text-slate-400 uppercase tracking-wider">ประเภท/เพศ</label>
                            <select id="filter-category" onchange="filterMatches()" class="w-full bg-white/3 border border-white/5 focus:border-indigo-500 focus:bg-white/5 rounded-xl py-2 px-3 text-white text-xs outline-none transition-all duration-200">
                                <option value="all">ทั้งหมด</option>
                                <?php 
                                $uniqueCats = [];
                                if (!empty($matches)) {
                                    foreach ($matches as $m) {
                                        $uniqueCats[$m['category']] = $m['category'];
                                    }
                                }
                                foreach ($uniqueCats as $cat) {
                                    echo '<option value="' . htmlspecialchars($cat) . '">' . htmlspecialchars($cat) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div>
                            <label for="filter-status" class="block mb-1 text-[11px] font-bold text-slate-400 uppercase tracking-wider">สถานะ</label>
                            <select id="filter-status" onchange="filterMatches()" class="w-full bg-white/3 border border-white/5 focus:border-indigo-500 focus:bg-white/5 rounded-xl py-2 px-3 text-white text-xs outline-none transition-all duration-200">
                                <option value="all">ทั้งหมด</option>
                                <option value="Scheduled">รอแข่ง</option>
                                <option value="Live">กำลังแข่ง</option>
                                <option value="Completed">เสร็จสิ้น</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Desktop Table (Visible on md and above) -->
                    <div class="hidden md:block overflow-x-auto w-full rounded-xl border border-white/5">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                                <tr class="bg-white/2 border-b border-white/5">
                                    <th class="p-3 text-slate-400 font-bold text-xs uppercase tracking-wider">รายการแข่งขัน</th>
                                    <th class="p-3 text-slate-400 font-bold text-xs uppercase tracking-wider">รอบ/แมตช์</th>
                                    <th class="p-3 text-slate-400 font-bold text-xs uppercase tracking-wider">สถานะ</th>
                                    <th class="p-3 text-slate-400 font-bold text-xs uppercase tracking-wider">ผลแข่งขัน/ผู้ชนะ</th>
                                    <th class="p-3 text-slate-400 font-bold text-xs uppercase tracking-wider">การจัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($matches)): ?>
                                    <tr class="empty-row">
                                        <td colspan="5" class="p-8 text-center text-slate-500">ยังไม่มีข้อมูลการแข่งขันลงกำหนดการ</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($matches as $match): ?>
                                        <tr class="match-item-row border-b border-white/[0.03] hover:bg-white/[0.01] transition-colors"
                                            data-sport-id="<?= $match['sport_id'] ?>"
                                            data-category="<?= htmlspecialchars($match['category']) ?>"
                                            data-status="<?= htmlspecialchars($match['status']) ?>">
                                            <td class="p-3.5">
                                                <strong class="block text-white"><?= htmlspecialchars($match['sport_name']) ?></strong>
                                                <span class="text-xs text-slate-400"><?= htmlspecialchars($match['category']) ?></span>
                                            </td>
                                            <td class="p-3.5">
                                                <?php if ($match['bracket_id'] !== null): ?>
                                                    <span class="text-xs font-semibold text-slate-300"><?= htmlspecialchars($match['round_name']) ?></span>
                                                <?php else: ?>
                                                    <span class="text-slate-500 text-xs">แมตช์ทั่วไป</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="p-3.5">
                                                <?php if ($match['status'] === 'Completed'): ?>
                                                    <span class="inline-flex bg-green-500/10 text-green-400 border border-green-500/15 text-[10px] font-bold px-2 py-0.5 rounded-full">เสร็จสิ้น</span>
                                                <?php elseif ($match['status'] === 'Live'): ?>
                                                    <span class="inline-flex bg-rose-500/10 text-rose-400 border border-rose-500/25 text-[10px] font-bold px-2 py-0.5 rounded-full items-center"><span class="live-pulse" style="width:6px;height:6px;margin-right:0.25rem;"></span>กำลังแข่ง</span>
                                                <?php else: ?>
                                                    <span class="inline-flex bg-slate-800 text-slate-400 border border-white/5 text-[10px] font-bold px-2 py-0.5 rounded-full">รอแข่ง</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="p-3.5">
                                                <?php if ($match['bracket_id'] !== null): ?>
                                                    <?php if ($match['winner_house_id'] !== null): 
                                                        $winnerName = ($match['winner_house_id'] == $match['team1_house_id']) ? $match['team1_name'] : $match['team2_name'];
                                                        $winnerColor = ($match['winner_house_id'] == $match['team1_house_id']) ? $match['team1_color'] : $match['team2_color'];
                                                    ?>
                                                        <div class="text-xs">
                                                            ชนะ: <span class="font-bold" style="color: <?= $winnerColor ?>"><?= htmlspecialchars($presenter->getHouseNameTh($winnerName)) ?></span>
                                                            <div class="text-[10px] text-slate-400 mt-0.5">(<?= $match['team1_score'] ?> - <?= $match['team2_score'] ?>)</div>
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="text-xs text-slate-400">
                                                            <?= $match['team1_name'] ? htmlspecialchars($presenter->getHouseNameTh($match['team1_name'])) : 'TBD' ?> vs 
                                                            <?= $match['team2_name'] ? htmlspecialchars($presenter->getHouseNameTh($match['team2_name'])) : 'TBD' ?>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <span class="text-slate-500 text-xs">บันทึกผลที่สรุปผลรางวัลกีฬา</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="p-3.5">
                                                <div class="flex gap-1.5 items-center">
                                                    <?php if ($match['bracket_id'] !== null): ?>
                                                        <?php if ($match['winner_house_id'] === null): ?>
                                                            <?php if ($match['team1_house_id'] && $match['team2_house_id']): ?>
                                                                <button class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-2.5 py-1 rounded-lg text-xs transition-colors cursor-pointer animate-pulse"
                                                                        onclick="openBracketScoreModal(<?= $match['bracket_id'] ?>, <?= $match['sport_id'] ?>, '<?= htmlspecialchars($match['round_name']) ?>', 'คู่ที่ <?= $match['match_order'] ?>', <?= $match['team1_house_id'] ?>, '<?= htmlspecialchars($presenter->getHouseNameTh($match['team1_name'])) ?>', <?= $match['team2_house_id'] ?>, '<?= htmlspecialchars($presenter->getHouseNameTh($match['team2_name'])) ?>')">
                                                                    ลงผล
                                                                </button>
                                                            <?php else: ?>
                                                                <span class="bg-slate-800 text-slate-500 text-[10px] font-bold px-2 py-1 rounded-lg border border-white/5">รอคู่แข่ง</span>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                    <form id="del-match-<?= $match['id'] ?>" action="index.php?route=dashboard&action=delete_match" method="POST" class="inline">
                                                        <input type="hidden" name="match_id" value="<?= $match['id'] ?>">
                                                        <button type="button" class="bg-rose-500/10 hover:bg-rose-500/20 text-rose-300 border border-rose-500/20 px-2 py-1 rounded-lg text-xs transition-colors cursor-pointer"
                                                            onclick="swalConfirm('del-match-<?= $match['id'] ?>', '\u0e25\u0e1a\u0e01\u0e32\u0e23\u0e41\u0e02\u0e48\u0e07\u0e02\u0e31\u0e19', '\u0e15\u0e49\u0e2d\u0e07\u0e01\u0e32\u0e23\u0e25\u0e1a\u0e41\u0e21\u0e15\u0e0a\u0e4c\u0e01\u0e32\u0e23\u0e41\u0e02\u0e48\u0e07\u0e02\u0e31\u0e19\u0e19\u0e35\u0e49\u0e2b\u0e23\u0e37\u0e2d\u0e44\u0e21\u0e48? \u0e01\u0e32\u0e23\u0e01\u0e23\u0e30\u0e17\u0e33\u0e19\u0e35\u0e49\u0e44\u0e21\u0e48\u0e2a\u0e32\u0e21\u0e32\u0e23\u0e16\u0e22\u0e49\u0e2d\u0e19\u0e01\u0e25\u0e31\u0e1a\u0e44\u0e14\u0e49')"
                                                        >ลบ</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                <tr id="no-matches-desktop-row" class="hidden">
                                    <td colspan="5" class="p-8 text-center text-slate-500">ไม่มีข้อมูลที่ตรงกับตัวกรอง</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Card List (Visible on mobile only) -->
                    <div id="matches-mobile-list" class="block md:hidden space-y-3">
                        <?php if (empty($matches)): ?>
                            <div class="no-matches-card text-center p-8 bg-slate-800/20 border border-white/5 rounded-2xl text-slate-500 text-sm">
                                ยังไม่มีข้อมูลการแข่งขันลงกำหนดการ
                            </div>
                        <?php else: ?>
                            <?php foreach ($matches as $match): ?>
                                <div class="match-item-card bg-slate-800/30 border border-white/5 rounded-2xl p-4 transition-all hover:bg-slate-800/50"
                                     data-sport-id="<?= $match['sport_id'] ?>"
                                     data-category="<?= htmlspecialchars($match['category']) ?>"
                                     data-status="<?= htmlspecialchars($match['status']) ?>">
                                    <div class="flex justify-between items-start gap-2 mb-2">
                                        <div>
                                            <h4 class="text-sm font-bold text-white"><?= htmlspecialchars($match['sport_name']) ?></h4>
                                            <p class="text-xs text-slate-400 mt-0.5"><?= htmlspecialchars($match['category']) ?></p>
                                        </div>
                                        <div>
                                            <?php if ($match['status'] === 'Completed'): ?>
                                                <span class="inline-flex bg-green-500/10 text-green-400 border border-green-500/15 text-[10px] font-bold px-2 py-0.5 rounded-full">เสร็จสิ้น</span>
                                            <?php elseif ($match['status'] === 'Live'): ?>
                                                <span class="inline-flex bg-rose-500/10 text-rose-400 border border-rose-500/25 text-[10px] font-bold px-2 py-0.5 rounded-full items-center"><span class="live-pulse" style="width:6.5px;height:6.5px;margin-right:0.25rem;"></span>กำลังแข่ง</span>
                                            <?php else: ?>
                                                <span class="inline-flex bg-slate-800 text-slate-400 border border-white/5 text-[10px] font-bold px-2 py-0.5 rounded-full">รอแข่ง</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    
                                    <div class="border-t border-white/[0.03] my-2.5"></div>
                                    
                                    <div class="flex flex-col gap-2 mb-3">
                                        <div class="flex items-center justify-between text-xs">
                                            <span class="text-slate-400">รอบ/ลำดับ:</span>
                                            <span class="font-semibold text-slate-300">
                                                <?php if ($match['bracket_id'] !== null): ?>
                                                    <?= htmlspecialchars($match['round_name']) ?>
                                                <?php else: ?>
                                                    แมตช์ทั่วไป
                                                <?php endif; ?>
                                            </span>
                                        </div>
                                        <div class="flex flex-col gap-1">
                                            <span class="text-xs text-slate-400 mb-0.5">ผลการแข่งขัน/ผู้ชนะ:</span>
                                            <?php if ($match['bracket_id'] !== null): ?>
                                                <?php if ($match['winner_house_id'] !== null): 
                                                    $winnerName = ($match['winner_house_id'] == $match['team1_house_id']) ? $match['team1_name'] : $match['team2_name'];
                                                    $winnerColor = ($match['winner_house_id'] == $match['team1_house_id']) ? $match['team1_color'] : $match['team2_color'];
                                                ?>
                                                    <div class="text-xs pl-2 border-l border-white/5">
                                                        ชนะ: <span class="font-bold" style="color: <?= $winnerColor ?>"><?= htmlspecialchars($presenter->getHouseNameTh($winnerName)) ?></span>
                                                        <div class="text-[10px] text-slate-400 mt-0.5">(<?= $match['team1_score'] ?> - <?= $match['team2_score'] ?>)</div>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="text-xs text-slate-400 pl-2 border-l border-white/5">
                                                        <?= $match['team1_name'] ? htmlspecialchars($presenter->getHouseNameTh($match['team1_name'])) : 'TBD' ?> vs 
                                                        <?= $match['team2_name'] ? htmlspecialchars($presenter->getHouseNameTh($match['team2_name'])) : 'TBD' ?>
                                                    </div>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="text-slate-500 text-xs pl-2">— บันทึกผลที่สรุปผลรางวัลกีฬา —</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    
                                    <div class="flex justify-end gap-2 pt-2 border-t border-white/[0.03]">
                                        <?php if ($match['bracket_id'] !== null): ?>
                                            <?php if ($match['winner_house_id'] === null): ?>
                                                <?php if ($match['team1_house_id'] && $match['team2_house_id']): ?>
                                                    <button class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-2.5 py-1.5 rounded-lg text-xs transition-colors cursor-pointer" 
                                                            onclick="openBracketScoreModal(<?= $match['bracket_id'] ?>, <?= $match['sport_id'] ?>, '<?= htmlspecialchars($match['round_name']) ?>', 'คู่ที่ <?= $match['match_order'] ?>', <?= $match['team1_house_id'] ?>, '<?= htmlspecialchars($presenter->getHouseNameTh($match['team1_name'])) ?>', <?= $match['team2_house_id'] ?>, '<?= htmlspecialchars($presenter->getHouseNameTh($match['team2_name'])) ?>')">
                                                        ลงผล
                                                    </button>
                                                <?php else: ?>
                                                    <span class="bg-slate-800 text-slate-500 text-[10px] font-bold px-2.5 py-1.5 rounded-lg border border-white/5">รอคู่แข่ง</span>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        
                                        <form id="del-match-mob-<?= $match['id'] ?>" action="index.php?route=dashboard&action=delete_match" method="POST" class="inline">
                                            <input type="hidden" name="match_id" value="<?= $match['id'] ?>">
                                            <button type="button" class="bg-rose-500/10 hover:bg-rose-500/20 text-rose-300 border border-rose-500/20 px-2.5 py-1.5 rounded-lg text-xs transition-colors cursor-pointer"
                                                onclick="swalConfirm('del-match-mob-<?= $match['id'] ?>', '\u0e25\u0e1a\u0e01\u0e32\u0e23\u0e41\u0e02\u0e48\u0e07\u0e02\u0e31\u0e19', '\u0e15\u0e49\u0e2d\u0e07\u0e01\u0e32\u0e23\u0e25\u0e1a\u0e41\u0e21\u0e15\u0e0a\u0e4c\u0e01\u0e32\u0e23\u0e41\u0e02\u0e48\u0e07\u0e02\u0e31\u0e19\u0e19\u0e35\u0e49\u0e2b\u0e23\u0e37\u0e2d\u0e44\u0e21\u0e48? \u0e01\u0e32\u0e23\u0e01\u0e23\u0e30\u0e17\u0e33\u0e19\u0e35\u0e49\u0e44\u0e21\u0e48\u0e2a\u0e32\u0e21\u0e32\u0e23\u0e16\u0e22\u0e49\u0e2d\u0e19\u0e01\u0e25\u0e31\u0e1a\u0e44\u0e14\u0e49')"
                                            >ลบ</button>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <div id="no-matches-mobile-card" class="hidden text-center p-8 bg-slate-800/20 border border-white/5 rounded-2xl text-slate-500 text-sm">
                            ไม่มีข้อมูลที่ตรงกับตัวกรอง
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Tab 4: Standings -->
        <div id="leaderboard-tab" class="tab-pane">
            <div class="bg-slate-900/60 backdrop-blur-xl border border-white/5 rounded-[24px] p-6 md:p-8 shadow-lg max-w-2xl mx-auto">
                <h3 class="text-xl font-bold mb-6 text-center flex items-center justify-center gap-2">
                    <i class="fa-solid fa-ranking-star text-[#d4af37]"></i>
                    สรุปผลตารางคะแนนรวมและเหรียญรางวัล
                </h3>
                <div class="flex flex-col gap-3">
                    <?php if (empty($leaderboard)): ?>
                        <p class="text-center text-slate-500">ยังไม่มีข้อมูลการสรุปผลคะแนน</p>
                    <?php else: ?>
                        <?php 
                            $rank = 1; 
                            $maxPoints = 1;
                            foreach ($leaderboard as $r) {
                                if ($r['total_points'] > $maxPoints) {
                                    $maxPoints = $r['total_points'];
                                }
                            }
                            $isCompact = true;
                            foreach ($leaderboard as $row): 
                                include __DIR__ . '/components/leaderboard_row.php';
                                $rank++;
                            endforeach; 
                        ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Tab 5: Bracket Management -->
        <div id="bracket-tab" class="tab-pane">
            <div class="bg-slate-900/60 backdrop-blur-xl border border-white/5 rounded-[24px] p-6 md:p-8 shadow-lg">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-white/5 pb-6 mb-6">
                    <div>
                        <h3 class="text-xl font-bold flex items-center gap-2 text-white font-heading">
                            <i class="fa-solid fa-diagram-project text-teal-400"></i>
                            ระบบสายการแข่งขันแบบแพ้คัดออก (Single Elimination Bracket)
                        </h3>
                        <p class="text-xs text-slate-400 mt-1">จัดการรอบจับคู่และบันทึกคะแนนเพื่อหาผู้ชนะผ่านการแข่งแบบน็อคเอาต์</p>
                    </div>

                    <!-- Sport selector -->
                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 w-full sm:w-auto">
                        <label for="bracket-sport-select" class="text-xs font-bold text-slate-300 whitespace-nowrap">เลือกชนิดกีฬา:</label>
                        <select id="bracket-sport-select" class="w-full sm:w-auto bg-slate-950 border border-white/10 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-teal-500 transition-colors" onchange="changeBracketSport(this.value)">
                            <?php foreach ($sports as $s): ?>
                                <option value="<?= $s['id'] ?>" <?= $s['id'] == $selected_sport_id ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($s['sport_name']) ?> (<?= htmlspecialchars($s['category']) ?>) - <?= (isset($s['bracket_count']) && $s['bracket_count'] > 0) ? 'สร้างสายแล้ว' : 'ยังไม่ได้สร้างสาย' ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <?php 
                $uncreated_sports = [];
                foreach ($sports as $s) {
                    if (!isset($s['bracket_count']) || $s['bracket_count'] == 0) {
                        $uncreated_sports[] = htmlspecialchars($s['sport_name']) . ' (' . htmlspecialchars($s['category']) . ')';
                    }
                }
                if (!empty($uncreated_sports)): 
                ?>
                    <div class="bg-amber-500/10 border border-amber-500/20 rounded-2xl p-4 mb-6 flex items-start gap-3">
                        <i class="fa-solid fa-triangle-exclamation text-amber-500 text-lg mt-0.5 animate-pulse"></i>
                        <div>
                            <h4 class="text-xs font-bold text-white mb-1">กีฬาที่ยังไม่ได้สร้างสายการแข่งขัน (<?= count($uncreated_sports) ?> รายการ)</h4>
                            <p class="text-[11px] text-slate-400">กีฬาที่ค้างอยู่: <?= implode(', ', $uncreated_sports) ?></p>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (empty($brackets)): ?>
                    <!-- Generator form (6 teams) -->
                    <div class="max-w-2xl mx-auto py-4">
                        <div class="bg-teal-500/10 border border-teal-500/20 rounded-2xl p-5 mb-6 text-center">
                            <i class="fa-solid fa-circle-info text-teal-400 text-2xl mb-2"></i>
                            <h4 class="text-sm font-bold text-white mb-1">ยังไม่มีการสร้างสายการแข่งขันสำหรับกีฬานี้</h4>
                            <p class="text-xs text-slate-400 max-w-md mx-auto">กรุณาจัดทีมและตำแหน่งสายการแข่งด้านล่างเพื่อเริ่มสร้างสายการแข่งขันแบบ 6 ทีม (ทีมสิทธิ์บาย 2 ทีมจะข้ามไปรอบรองชนะเลิศโดยอัตโนมัติ)</p>
                        </div>

                        <form action="index.php?route=dashboard&action=create_bracket" method="POST" id="create-bracket-form">
                            <input type="hidden" name="sport_id" value="<?= $selected_sport_id ?>">
                            
                            <h4 class="text-xs font-bold text-slate-300 uppercase tracking-wider mb-3">กำหนดตำแหน่งทีมในสายแข่ง</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                <div class="bg-slate-950/40 border border-white/5 rounded-xl p-4 flex flex-col gap-3">
                                    <h5 class="text-xs font-bold text-indigo-400 flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-indigo-400"></span>รอบแรก คู่ที่ 1 (Quarter-final 1)</h5>
                                    <div>
                                        <label class="text-[11px] text-slate-400 font-semibold block mb-1">ทีมที่ 1</label>
                                        <select name="teams[]" class="w-full bg-slate-900 border border-white/10 rounded-lg px-3 py-1.5 text-xs text-white" required>
                                            <option value="">-- เลือกคณะสี --</option>
                                            <?php foreach ($houses as $h): ?>
                                                <option value="<?= $h['id'] ?>"><?= htmlspecialchars($h['house_name']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-[11px] text-slate-400 font-semibold block mb-1">ทีมที่ 2</label>
                                        <select name="teams[]" class="w-full bg-slate-900 border border-white/10 rounded-lg px-3 py-1.5 text-xs text-white" required>
                                            <option value="">-- เลือกคณะสี --</option>
                                            <?php foreach ($houses as $h): ?>
                                                <option value="<?= $h['id'] ?>"><?= htmlspecialchars($h['house_name']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="bg-slate-950/40 border border-white/5 rounded-xl p-4 flex flex-col gap-3">
                                    <h5 class="text-xs font-bold text-purple-400 flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-purple-400"></span>รอบแรก คู่ที่ 2 (Quarter-final 2)</h5>
                                    <div>
                                        <label class="text-[11px] text-slate-400 font-semibold block mb-1">ทีมที่ 1</label>
                                        <select name="teams[]" class="w-full bg-slate-900 border border-white/10 rounded-lg px-3 py-1.5 text-xs text-white" required>
                                            <option value="">-- เลือกคณะสี --</option>
                                            <?php foreach ($houses as $h): ?>
                                                <option value="<?= $h['id'] ?>"><?= htmlspecialchars($h['house_name']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-[11px] text-slate-400 font-semibold block mb-1">ทีมที่ 2</label>
                                        <select name="teams[]" class="w-full bg-slate-900 border border-white/10 rounded-lg px-3 py-1.5 text-xs text-white" required>
                                            <option value="">-- เลือกคณะสี --</option>
                                            <?php foreach ($houses as $h): ?>
                                                <option value="<?= $h['id'] ?>"><?= htmlspecialchars($h['house_name']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="bg-slate-950/40 border border-white/5 rounded-xl p-4 flex flex-col gap-3 md:col-span-2">
                                    <h5 class="text-xs font-bold text-teal-400 flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-teal-400"></span>ทีมสิทธิ์บายรอบแรก (Byes to Semi-finals)</h5>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="text-[11px] text-slate-400 font-semibold block mb-1">บายไปรอรอบรองชนะเลิศคู่ที่ 1</label>
                                            <select name="teams[]" class="w-full bg-slate-900 border border-white/10 rounded-lg px-3 py-1.5 text-xs text-white" required>
                                                <option value="">-- เลือกคณะสี --</option>
                                                <?php foreach ($houses as $h): ?>
                                                    <option value="<?= $h['id'] ?>"><?= htmlspecialchars($h['house_name']) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="text-[11px] text-slate-400 font-semibold block mb-1">บายไปรอรอบรองชนะเลิศคู่ที่ 2</label>
                                            <select name="teams[]" class="w-full bg-slate-900 border border-white/10 rounded-lg px-3 py-1.5 text-xs text-white" required>
                                                <option value="">-- เลือกคณะสี --</option>
                                                <?php foreach ($houses as $h): ?>
                                                    <option value="<?= $h['id'] ?>"><?= htmlspecialchars($h['house_name']) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="w-full bg-teal-600 hover:bg-teal-700 text-white font-bold py-2.5 rounded-xl text-sm transition-colors cursor-pointer flex items-center justify-center gap-1.5">
                                <i class="fa-solid fa-wand-magic-sparkles"></i>
                                สร้างสายการแข่งขัน
                            </button>
                        </form>
                    </div>
                <?php else: ?>
                    <!-- Display Bracket Tree -->
                    <?php
                    $round_matches = [1 => [], 2 => [], 3 => []];
                    foreach ($brackets as $b) {
                        $round_matches[$b['round_number']][] = $b;
                    }
                    ?>
                    <div class="flex justify-end mb-4">
                        <form action="index.php?route=dashboard&action=reset_bracket" method="POST" id="reset-bracket-form">
                            <input type="hidden" name="sport_id" value="<?= $selected_sport_id ?>">
                            <button type="button" class="bg-red-600/10 hover:bg-red-600 border border-red-500/20 hover:border-red-600 text-red-400 hover:text-white font-bold px-4 py-2 rounded-xl text-xs transition-all cursor-pointer flex items-center gap-1.5" onclick="swalConfirm('reset-bracket-form', 'ยืนยันการล้างข้อมูล', 'สายการแข่งขัน คะแนน และผลลัพธ์ของกีฬานี้จะถูกลบทั้งหมดเพื่อเริ่มสร้างใหม่ ท่านแน่ใจหรือไม่?')">
                                <i class="fa-solid fa-trash-can"></i>
                                ล้างสายและสร้างใหม่
                            </button>
                        </form>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 relative py-4">
                        <!-- Round 1: Quarter-finals -->
                        <div class="flex flex-col gap-6 justify-center">
                            <h4 class="text-xs font-bold text-indigo-400 uppercase tracking-wider text-center border-b border-indigo-500/10 pb-2 font-heading">Quarter-finals (รอบแรก)</h4>
                            <?php foreach ($round_matches[1] as $b): ?>
                                <div class="bg-slate-800/80 border border-white/5 rounded-2xl p-4 flex flex-col gap-2 relative shadow-md hover:border-white/10 transition-all duration-300">
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
                                    <div class="flex justify-between items-center py-1 <?= ($b['winner_house_id'] !== null && $b['winner_house_id'] == $b['team1_house_id']) ? 'font-bold text-white' : 'text-slate-300' ?> <?= $b['team1_house_id'] ? 'cursor-pointer hover:bg-white/5 px-2 -mx-2 rounded-lg transition-colors' : '' ?>"
                                         <?= $b['team1_house_id'] ? 'onclick="showTeamAthletes(' . $selected_sport_id . ', ' . $b['team1_house_id'] . ', \'' . htmlspecialchars($presenter->getHouseNameTh($b['team1_name'])) . '\', \'' . ($b['team1_color'] ?: '#334155') . '\')"' : '' ?>
                                         <?= $b['team1_house_id'] ? 'title="คลิกเพื่อดูรายชื่อนักกีฬา"' : '' ?>>
                                        <span class="flex items-center gap-2 text-xs truncate">
                                            <span class="w-2 h-2 rounded-full shrink-0" style="background-color: <?= $b['team1_color'] ?: '#334155' ?>"></span>
                                            <?= $b['team1_name'] ? htmlspecialchars($presenter->getHouseNameTh($b['team1_name'])) : 'TBD' ?>
                                        </span>
                                        <span class="text-xs font-black"><?= $b['team1_score'] !== null ? $b['team1_score'] : '-' ?></span>
                                    </div>

                                    <!-- Team 2 -->
                                    <div class="flex justify-between items-center py-1 <?= ($b['winner_house_id'] !== null && $b['winner_house_id'] == $b['team2_house_id']) ? 'font-bold text-white' : 'text-slate-300' ?> <?= $b['team2_house_id'] ? 'cursor-pointer hover:bg-white/5 px-2 -mx-2 rounded-lg transition-colors' : '' ?>"
                                         <?= $b['team2_house_id'] ? 'onclick="showTeamAthletes(' . $selected_sport_id . ', ' . $b['team2_house_id'] . ', \'' . htmlspecialchars($presenter->getHouseNameTh($b['team2_name'])) . '\', \'' . ($b['team2_color'] ?: '#334155') . '\')"' : '' ?>
                                         <?= $b['team2_house_id'] ? 'title="คลิกเพื่อดูรายชื่อนักกีฬา"' : '' ?>>
                                        <span class="flex items-center gap-2 text-xs truncate">
                                            <span class="w-2 h-2 rounded-full shrink-0" style="background-color: <?= $b['team2_color'] ?: '#334155' ?>"></span>
                                            <?= $b['team2_name'] ? htmlspecialchars($presenter->getHouseNameTh($b['team2_name'])) : 'TBD' ?>
                                        </span>
                                        <span class="text-xs font-black"><?= $b['team2_score'] !== null ? $b['team2_score'] : '-' ?></span>
                                    </div>

                                    <?php if ($b['winner_house_id'] === null && $b['team1_house_id'] && $b['team2_house_id']): ?>
                                        <button class="mt-2 w-full bg-teal-600 hover:bg-teal-700 text-white font-bold py-1.5 px-3 rounded-lg text-[10px] transition-all cursor-pointer" onclick="openBracketScoreModal(<?= $b['id'] ?>, <?= $selected_sport_id ?>, 'Quarter-finals', 'คู่ที่ <?= $b['match_order'] ?>', <?= $b['team1_house_id'] ?>, '<?= htmlspecialchars($presenter->getHouseNameTh($b['team1_name'])) ?>', <?= $b['team2_house_id'] ?>, '<?= htmlspecialchars($presenter->getHouseNameTh($b['team2_name'])) ?>')">
                                            บันทึกผล
                                        </button>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Round 2: Semi-finals -->
                        <div class="flex flex-col gap-6 justify-center">
                            <h4 class="text-xs font-bold text-purple-400 uppercase tracking-wider text-center border-b border-purple-500/10 pb-2 font-heading">Semi-finals (รอบรองชนะเลิศ)</h4>
                            <?php foreach ($round_matches[2] as $b): ?>
                                <div class="bg-slate-800/80 border border-white/5 rounded-2xl p-4 flex flex-col gap-2 relative shadow-md hover:border-white/10 transition-all duration-300">
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
                                    
                                    <!-- Team 1 (Bye Team) -->
                                    <div class="flex justify-between items-center py-1 <?= ($b['winner_house_id'] !== null && $b['winner_house_id'] == $b['team1_house_id']) ? 'font-bold text-white' : 'text-slate-300' ?> <?= $b['team1_house_id'] ? 'cursor-pointer hover:bg-white/5 px-2 -mx-2 rounded-lg transition-colors' : '' ?>"
                                         <?= $b['team1_house_id'] ? 'onclick="showTeamAthletes(' . $selected_sport_id . ', ' . $b['team1_house_id'] . ', \'' . htmlspecialchars($presenter->getHouseNameTh($b['team1_name'])) . '\', \'' . ($b['team1_color'] ?: '#334155') . '\')"' : '' ?>
                                         <?= $b['team1_house_id'] ? 'title="คลิกเพื่อดูรายชื่อนักกีฬา"' : '' ?>>
                                        <span class="flex items-center gap-2 text-xs truncate">
                                            <span class="w-2 h-2 rounded-full shrink-0" style="background-color: <?= $b['team1_color'] ?: '#334155' ?>"></span>
                                            <?= $b['team1_name'] ? htmlspecialchars($presenter->getHouseNameTh($b['team1_name'])) : 'TBD' ?>
                                            <span class="text-[8px] bg-teal-500/10 text-teal-400 px-1 py-0.2 rounded border border-teal-500/10 scale-90">BYE</span>
                                        </span>
                                        <span class="text-xs font-black"><?= $b['team1_score'] !== null ? $b['team1_score'] : '-' ?></span>
                                    </div>

                                    <!-- Team 2 (Winner of Quarter-final) -->
                                    <div class="flex justify-between items-center py-1 <?= ($b['winner_house_id'] !== null && $b['winner_house_id'] == $b['team2_house_id']) ? 'font-bold text-white' : 'text-slate-300' ?> <?= $b['team2_house_id'] ? 'cursor-pointer hover:bg-white/5 px-2 -mx-2 rounded-lg transition-colors' : '' ?>"
                                         <?= $b['team2_house_id'] ? 'onclick="showTeamAthletes(' . $selected_sport_id . ', ' . $b['team2_house_id'] . ', \'' . htmlspecialchars($presenter->getHouseNameTh($b['team2_name'])) . '\', \'' . ($b['team2_color'] ?: '#334155') . '\')"' : '' ?>
                                         <?= $b['team2_house_id'] ? 'title="คลิกเพื่อดูรายชื่อนักกีฬา"' : '' ?>>
                                        <span class="flex items-center gap-2 text-xs truncate">
                                            <span class="w-2 h-2 rounded-full shrink-0" style="background-color: <?= $b['team2_color'] ?: '#334155' ?>"></span>
                                            <?= $b['team2_name'] ? htmlspecialchars($presenter->getHouseNameTh($b['team2_name'])) : 'รอผู้ชนะคู่ ' . ($b['match_order'] == 1 ? '1' : '2') ?>
                                        </span>
                                        <span class="text-xs font-black"><?= $b['team2_score'] !== null ? $b['team2_score'] : '-' ?></span>
                                    </div>

                                    <?php if ($b['winner_house_id'] === null && $b['team1_house_id'] && $b['team2_house_id']): ?>
                                        <button class="mt-2 w-full bg-teal-600 hover:bg-teal-700 text-white font-bold py-1.5 px-3 rounded-lg text-[10px] transition-all cursor-pointer" onclick="openBracketScoreModal(<?= $b['id'] ?>, <?= $selected_sport_id ?>, 'Semi-finals', 'คู่ที่ <?= $b['match_order'] ?>', <?= $b['team1_house_id'] ?>, '<?= htmlspecialchars($presenter->getHouseNameTh($b['team1_name'])) ?>', <?= $b['team2_house_id'] ?>, '<?= htmlspecialchars($presenter->getHouseNameTh($b['team2_name'])) ?>')">
                                            บันทึกผล
                                        </button>
                                    <?php endif; ?>
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
                                <h4 class="text-xs font-bold text-[#d4af37] uppercase tracking-wider text-center border-b border-[#d4af37]/10 pb-2 font-heading">Finals (รอบชิงชนะเลิศ)</h4>
                                <div class="bg-slate-800/80 border border-[#d4af37]/20 rounded-2xl p-5 flex flex-col gap-3 relative shadow-md hover:border-[#d4af37]/40 transition-all duration-300 bg-gradient-to-b from-slate-900/60 to-yellow-500/2">
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
                                    <div class="flex justify-between items-center py-1.5 <?= ($b['winner_house_id'] !== null && $b['winner_house_id'] == $b['team1_house_id']) ? 'font-bold text-yellow-400' : 'text-slate-300' ?> <?= $b['team1_house_id'] ? 'cursor-pointer hover:bg-white/5 px-2 -mx-2 rounded-lg transition-colors' : '' ?>"
                                         <?= $b['team1_house_id'] ? 'onclick="showTeamAthletes(' . $selected_sport_id . ', ' . $b['team1_house_id'] . ', \'' . htmlspecialchars($presenter->getHouseNameTh($b['team1_name'])) . '\', \'' . ($b['team1_color'] ?: '#334155') . '\')"' : '' ?>
                                         <?= $b['team1_house_id'] ? 'title="คลิกเพื่อดูรายชื่อนักกีฬา"' : '' ?>>
                                        <span class="flex items-center gap-2 text-xs truncate">
                                            <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background-color: <?= $b['team1_color'] ?: '#334155' ?>"></span>
                                            <?= $b['team1_name'] ? htmlspecialchars($presenter->getHouseNameTh($b['team1_name'])) : 'รอผู้ชนะรอบรอง 1' ?>
                                        </span>
                                        <span class="text-xs font-black"><?= $b['team1_score'] !== null ? $b['team1_score'] : '-' ?></span>
                                    </div>

                                    <!-- Team 2 -->
                                    <div class="flex justify-between items-center py-1.5 <?= ($b['winner_house_id'] !== null && $b['winner_house_id'] == $b['team2_house_id']) ? 'font-bold text-yellow-400' : 'text-slate-300' ?> <?= $b['team2_house_id'] ? 'cursor-pointer hover:bg-white/5 px-2 -mx-2 rounded-lg transition-colors' : '' ?>"
                                         <?= $b['team2_house_id'] ? 'onclick="showTeamAthletes(' . $selected_sport_id . ', ' . $b['team2_house_id'] . ', \'' . htmlspecialchars($presenter->getHouseNameTh($b['team2_name'])) . '\', \'' . ($b['team2_color'] ?: '#334155') . '\')"' : '' ?>
                                         <?= $b['team2_house_id'] ? 'title="คลิกเพื่อดูรายชื่อนักกีฬา"' : '' ?>>
                                        <span class="flex items-center gap-2 text-xs truncate">
                                            <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background-color: <?= $b['team2_color'] ?: '#334155' ?>"></span>
                                            <?= $b['team2_name'] ? htmlspecialchars($presenter->getHouseNameTh($b['team2_name'])) : 'รอผู้ชนะรอบรอง 2' ?>
                                        </span>
                                        <span class="text-xs font-black"><?= $b['team2_score'] !== null ? $b['team2_score'] : '-' ?></span>
                                    </div>

                                    <?php if ($b['winner_house_id'] === null && $b['team1_house_id'] && $b['team2_house_id']): ?>
                                        <button class="mt-2 w-full bg-yellow-500 hover:bg-yellow-600 text-slate-950 font-bold py-1.5 px-3 rounded-lg text-xs transition-all cursor-pointer" onclick="openBracketScoreModal(<?= $b['id'] ?>, <?= $selected_sport_id ?>, 'Finals', 'ชิงชนะเลิศ', <?= $b['team1_house_id'] ?>, '<?= htmlspecialchars($presenter->getHouseNameTh($b['team1_name'])) ?>', <?= $b['team2_house_id'] ?>, '<?= htmlspecialchars($presenter->getHouseNameTh($b['team2_name'])) ?>')">
                                            บันทึกผลชิงชนะเลิศ
                                        </button>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <!-- Third Place Playoff -->
                            <?php if ($third_place_match): $b = $third_place_match; ?>
                                <h4 class="text-xs font-bold text-amber-600 uppercase tracking-wider text-center border-b border-amber-600/10 pt-4 pb-2 font-heading">Third-place (ชิงอันดับ 3)</h4>
                                <div class="bg-slate-800/80 border border-amber-600/20 rounded-2xl p-5 flex flex-col gap-3 relative shadow-md hover:border-amber-600/40 transition-all duration-300 bg-gradient-to-b from-slate-900/60 to-amber-700/2">
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
                                    <div class="flex justify-between items-center py-1.5 <?= ($b['winner_house_id'] !== null && $b['winner_house_id'] == $b['team1_house_id']) ? 'font-bold text-amber-500' : 'text-slate-300' ?> <?= $b['team1_house_id'] ? 'cursor-pointer hover:bg-white/5 px-2 -mx-2 rounded-lg transition-colors' : '' ?>"
                                         <?= $b['team1_house_id'] ? 'onclick="showTeamAthletes(' . $selected_sport_id . ', ' . $b['team1_house_id'] . ', \'' . htmlspecialchars($presenter->getHouseNameTh($b['team1_name'])) . '\', \'' . ($b['team1_color'] ?: '#334155') . '\')"' : '' ?>
                                         <?= $b['team1_house_id'] ? 'title="คลิกเพื่อดูรายชื่อนักกีฬา"' : '' ?>>
                                        <span class="flex items-center gap-2 text-xs truncate">
                                            <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background-color: <?= $b['team1_color'] ?: '#334155' ?>"></span>
                                            <?= $b['team1_name'] ? htmlspecialchars($presenter->getHouseNameTh($b['team1_name'])) : 'รอผู้แพ้รอบรอง 1' ?>
                                        </span>
                                        <span class="text-xs font-black"><?= $b['team1_score'] !== null ? $b['team1_score'] : '-' ?></span>
                                    </div>

                                    <!-- Team 2 -->
                                    <div class="flex justify-between items-center py-1.5 <?= ($b['winner_house_id'] !== null && $b['winner_house_id'] == $b['team2_house_id']) ? 'font-bold text-amber-500' : 'text-slate-300' ?> <?= $b['team2_house_id'] ? 'cursor-pointer hover:bg-white/5 px-2 -mx-2 rounded-lg transition-colors' : '' ?>"
                                         <?= $b['team2_house_id'] ? 'onclick="showTeamAthletes(' . $selected_sport_id . ', ' . $b['team2_house_id'] . ', \'' . htmlspecialchars($presenter->getHouseNameTh($b['team2_name'])) . '\', \'' . ($b['team2_color'] ?: '#334155') . '\')"' : '' ?>
                                         <?= $b['team2_house_id'] ? 'title="คลิกเพื่อดูรายชื่อนักกีฬา"' : '' ?>>
                                        <span class="flex items-center gap-2 text-xs truncate">
                                            <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background-color: <?= $b['team2_color'] ?: '#334155' ?>"></span>
                                            <?= $b['team2_name'] ? htmlspecialchars($presenter->getHouseNameTh($b['team2_name'])) : 'รอผู้แพ้รอบรอง 2' ?>
                                        </span>
                                        <span class="text-xs font-black"><?= $b['team2_score'] !== null ? $b['team2_score'] : '-' ?></span>
                                    </div>

                                    <?php if ($b['winner_house_id'] === null && $b['team1_house_id'] && $b['team2_house_id']): ?>
                                        <button class="mt-2 w-full bg-amber-600 hover:bg-amber-700 text-white font-bold py-1.5 px-3 rounded-lg text-xs transition-all cursor-pointer" onclick="openBracketScoreModal(<?= $b['id'] ?>, <?= $selected_sport_id ?>, 'Third-place', 'ชิงอันดับ 3', <?= $b['team1_house_id'] ?>, '<?= htmlspecialchars($presenter->getHouseNameTh($b['team1_name'])) ?>', <?= $b['team2_house_id'] ?>, '<?= htmlspecialchars($presenter->getHouseNameTh($b['team2_name'])) ?>')">
                                            บันทึกผลชิงอันดับ 3
                                        </button>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Bracket Score Modal -->
<div id="bracket-score-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header border-b border-white/5 pb-3">
            <h3 id="bracket-modal-match-title" class="text-lg font-bold flex items-center gap-2">
                <i class="fa-solid fa-trophy text-teal-400"></i>
                บันทึกผลการแข่งขัน (Bracket)
            </h3>
            <button class="close-btn" onclick="closeBracketScoreModal()">&times;</button>
        </div>
        <form action="index.php?route=dashboard&action=record_bracket_result" method="POST">
            <input type="hidden" name="bracket_id" id="bracket-modal-id">
            <input type="hidden" name="sport_id" id="bracket-modal-sport-id">

            <div class="flex flex-col gap-4 py-4">
                <!-- Scores Input -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label id="bracket-modal-team1-label" class="text-xs font-bold text-slate-300 block mb-1">คะแนน ทีม 1</label>
                        <input type="number" name="team1_score" id="bracket-modal-team1-score" class="w-full bg-slate-950 border border-white/10 rounded-xl px-3 py-2 text-xs text-white" required min="0">
                    </div>
                    <div>
                        <label id="bracket-modal-team2-label" class="text-xs font-bold text-slate-300 block mb-1">คะแนน ทีม 2</label>
                        <input type="number" name="team2_score" id="bracket-modal-team2-score" class="w-full bg-slate-950 border border-white/10 rounded-xl px-3 py-2 text-xs text-white" required min="0">
                    </div>
                </div>

                <!-- Winner Selector -->
                <div>
                    <label class="text-xs font-bold text-slate-300 block mb-1">เลือกผู้ชนะการแข่งขัน (เพื่อเลื่อนเข้ารอบถัดไป)</label>
                    <select name="winner_house_id" id="bracket-modal-winner" class="w-full bg-slate-950 border border-white/10 rounded-xl px-3 py-2 text-xs text-white" required>
                        <option value="">-- เลือกผู้ชนะ --</option>
                        <option value="" id="bracket-modal-opt-team1">ทีม 1</option>
                        <option value="" id="bracket-modal-opt-team2">ทีม 2</option>
                    </select>
                </div>

                <!-- Leaderboard Points -->
                <div class="grid grid-cols-2 gap-4 border-t border-white/5 pt-4">
                    <div>
                        <label class="text-xs font-bold text-slate-300 block mb-1">คะแนนลีดเดอร์บอร์ดผู้ชนะ</label>
                        <input type="number" name="points_winner" id="bracket-modal-points-winner" class="w-full bg-slate-950 border border-white/10 rounded-xl px-3 py-2 text-xs text-white" required min="0" value="5">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-300 block mb-1">คะแนนลีดเดอร์บอร์ดผู้แพ้</label>
                        <input type="number" name="points_loser" id="bracket-modal-points-loser" class="w-full bg-slate-950 border border-white/10 rounded-xl px-3 py-2 text-xs text-white" required min="0" value="2">
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 border-t border-white/5 pt-3">
                <button type="button" class="bg-white/5 hover:bg-white/10 border border-white/5 text-white font-bold px-4 py-2 rounded-xl text-xs transition-colors cursor-pointer" onclick="closeBracketScoreModal()">ยกเลิก</button>
                <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white font-bold px-4 py-2 rounded-xl text-xs transition-colors cursor-pointer">บันทึกผล</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Dialog logging results -->
<div id="score-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header border-b border-white/5 pb-3">
            <h3 id="modal-match-title" class="text-lg font-bold flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-indigo-400"></i>
                บันทึกผลการแข่งขัน
            </h3>
            <button class="close-btn" onclick="closeScoreModal()">&times;</button>
        </div>
        <form action="index.php?route=dashboard&action=record_result" method="POST">
            <input type="hidden" name="match_id" id="modal-match-id">
            
            <div class="my-6">
                <p class="text-xs text-slate-400 mb-4">
                    ระบุคะแนนสะสมและเลือกผลรางวัลเหรียญรางวัลให้กับคู่คณะสีที่ลงแข่งขัน เมื่อกดยืนยันแล้วแมตช์นี้จะถูกบันทึกสำเร็จ
                </p>
                
                <div class="grid grid-cols-3 font-bold text-[10px] text-slate-400 uppercase tracking-wider border-b border-white/5 pb-2 mb-2">
                    <div>คณะสี</div>
                    <div class="text-center">คะแนนสะสม</div>
                    <div class="text-center">เหรียญรางวัล</div>
                </div>

                <?php foreach ($houses as $house): 
                    $houseNameTh = $presenter->getHouseNameTh($house['house_name']);
                ?>
                    <div class="grid grid-cols-3 items-center py-2.5 border-b border-white/[0.03]">
                        <span class="house-text font-bold text-sm" <?= $presenter->getHouseStyle($house['color_code']) ?>>
                            <?= htmlspecialchars($houseNameTh) ?>
                        </span>
                        <div class="px-2">
                            <input type="number" name="points[<?= $house['id'] ?>]" class="w-full bg-white/3 border border-white/5 focus:border-indigo-500 focus:bg-white/5 rounded-lg py-1.5 px-2 text-white text-center text-sm outline-none transition-all" value="0" min="0" required>
                        </div>
                        <div class="px-1">
                            <select name="medal[<?= $house['id'] ?>]" class="w-full bg-white/3 border border-white/5 focus:border-indigo-500 focus:bg-white/5 rounded-lg py-1.5 px-2 text-white text-xs outline-none transition-all">
                                <option value="">ไม่มีเหรียญ</option>
                                <option value="Gold">เหรียญทอง 🥇</option>
                                <option value="Silver">เหรียญเงิน 🥈</option>
                                <option value="Bronze">เหรียญทองแดง 🥉</option>
                            </select>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="flex gap-2 justify-end border-t border-white/5 pt-4">
                <button type="button" class="bg-white/5 hover:bg-white/10 text-white border border-white/5 font-bold px-4 py-2 rounded-xl text-xs transition-colors cursor-pointer" onclick="closeScoreModal()">ยกเลิก</button>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-4 py-2 rounded-xl text-xs transition-colors cursor-pointer">บันทึกคะแนน</button>
            </div>
        </form>
    </div>
</div>



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
    }

    function filterMatches() {
        const sportVal = document.getElementById('filter-sport').value;
        const catVal = document.getElementById('filter-category').value;
        const statusVal = document.getElementById('filter-status').value;

        // Filter desktop rows
        const rows = document.querySelectorAll('.match-item-row');
        let visibleRowsCount = 0;
        rows.forEach(row => {
            const sportId = row.getAttribute('data-sport-id');
            const category = row.getAttribute('data-category');
            const status = row.getAttribute('data-status');

            const matchSport = (sportVal === 'all' || sportVal === sportId);
            const matchCat = (catVal === 'all' || catVal === category);
            const matchStatus = (statusVal === 'all' || statusVal === status);

            if (matchSport && matchCat && matchStatus) {
                row.style.display = '';
                visibleRowsCount++;
            } else {
                row.style.display = 'none';
            }
        });

        const noMatchesRow = document.getElementById('no-matches-desktop-row');
        if (noMatchesRow) {
            if (visibleRowsCount === 0 && rows.length > 0) {
                noMatchesRow.classList.remove('hidden');
            } else {
                noMatchesRow.classList.add('hidden');
            }
        }

        // Filter mobile cards
        const cards = document.querySelectorAll('.match-item-card');
        let visibleCardsCount = 0;
        cards.forEach(card => {
            const sportId = card.getAttribute('data-sport-id');
            const category = card.getAttribute('data-category');
            const status = card.getAttribute('data-status');

            const matchSport = (sportVal === 'all' || sportVal === sportId);
            const matchCat = (catVal === 'all' || catVal === category);
            const matchStatus = (statusVal === 'all' || statusVal === status);

            if (matchSport && matchCat && matchStatus) {
                card.style.display = '';
                visibleCardsCount++;
            } else {
                card.style.display = 'none';
            }
        });

        const noMatchesCard = document.getElementById('no-matches-mobile-card');
        if (noMatchesCard) {
            if (visibleCardsCount === 0 && cards.length > 0) {
                noMatchesCard.classList.remove('hidden');
            } else {
                noMatchesCard.classList.add('hidden');
            }
        }
    }



    function openScoreModal(matchId, sportName) {
        document.getElementById('modal-match-id').value = matchId;
        document.getElementById('modal-match-title').innerHTML = `
            <i class="fa-solid fa-circle-check text-indigo-400"></i>
            บันทึกผลแข่ง: ${sportName}
        `;
        document.getElementById('score-modal').style.display = 'flex';
    }

    function closeScoreModal() {
        document.getElementById('score-modal').style.display = 'none';
    }

    function openBracketScoreModal(bracketId, sportId, roundName, matchName, team1Id, team1Name, team2Id, team2Name) {
        document.getElementById('bracket-modal-id').value = bracketId;
        document.getElementById('bracket-modal-sport-id').value = sportId;
        
        document.getElementById('bracket-modal-team1-label').innerText = 'คะแนน คณะ' + team1Name;
        document.getElementById('bracket-modal-team2-label').innerText = 'คะแนน คณะ' + team2Name;
        
        const opt1 = document.getElementById('bracket-modal-opt-team1');
        const opt2 = document.getElementById('bracket-modal-opt-team2');
        
        opt1.value = team1Id;
        opt1.innerText = 'คณะ' + team1Name;
        opt2.value = team2Id;
        opt2.innerText = 'คณะ' + team2Name;
        
        document.getElementById('bracket-modal-team1-score').value = '';
        document.getElementById('bracket-modal-team2-score').value = '';
        document.getElementById('bracket-modal-winner').value = '';
        
        // Suggest leaderboard points based on round
        let pointsWinner = 5;
        let pointsLoser = 2;
        
        if (roundName === 'Finals') {
            pointsWinner = 15; // Gold
            pointsLoser = 10;  // Silver
        } else if (roundName === 'Semi-finals') {
            pointsWinner = 5;
            pointsLoser = 2;
        } else {
            pointsWinner = 3;
            pointsLoser = 1;
        }
        
        document.getElementById('bracket-modal-points-winner').value = pointsWinner;
        document.getElementById('bracket-modal-points-loser').value = pointsLoser;
        
        document.getElementById('bracket-modal-match-title').innerHTML = `
            <i class="fa-solid fa-trophy text-teal-400"></i>
            บันทึกผล: ${roundName} (${matchName})
        `;
        document.getElementById('bracket-score-modal').style.display = 'flex';
    }

    function closeBracketScoreModal() {
        document.getElementById('bracket-score-modal').style.display = 'none';
    }

    function changeBracketSport(sportId) {
        window.location.href = 'index.php?route=dashboard&bracket_sport_id=' + sportId + '&tab=bracket-tab';
    }

    window.addEventListener('DOMContentLoaded', () => {
        const urlParams = new URLSearchParams(window.location.search);
        const tab = urlParams.get('tab');
        if (tab) {
            switchTab(tab);
        }
    });

    function onHouseChange() {
        const houseSelect = document.getElementById('appoint-house-select');
        const searchInput = document.getElementById('student-search-input');
        const resultsList = document.getElementById('search-results-list');
        
        // Clear previous input & results
        searchInput.value = '';
        resultsList.innerHTML = '';
        resultsList.style.display = 'none';
        
        if (houseSelect.value !== '') {
            searchInput.disabled = false;
            searchInput.placeholder = 'พิมพ์ชื่อหรือรหัสนักเรียนในสังกัดเพื่อค้นหา...';
            searchInput.focus();
        } else {
            searchInput.disabled = true;
            searchInput.placeholder = 'กรุณาเลือกคณะสีก่อนค้นหา...';
        }
    }

    let searchTimeout = null;
    function doSearchStudent(val) {
        clearTimeout(searchTimeout);
        const resultsList = document.getElementById('search-results-list');
        const houseId = document.getElementById('appoint-house-select').value;
        
        if (!houseId) {
            resultsList.innerHTML = '<div class="p-3 text-slate-500 text-xs text-center">กรุณาเลือกคณะสีก่อน</div>';
            resultsList.style.display = 'block';
            return;
        }

        if (val.trim().length < 2) {
            resultsList.style.display = 'none';
            return;
        }

        searchTimeout = setTimeout(() => {
            fetch('index.php?route=search_student&house_id=' + houseId + '&q=' + encodeURIComponent(val))
                .then(res => res.json())
                .then(data => {
                    resultsList.innerHTML = '';
                    if (data.length === 0) {
                        resultsList.innerHTML = '<div class="p-3 text-slate-500 text-xs text-center">ไม่พบชื่อหรือรหัสนักเรียนในคณะสีนี้</div>';
                    } else {
                        data.forEach(stud => {
                            const div = document.createElement('div');
                            div.className = 'search-item';
                            
                            let rightElement = '';
                            if (stud.is_registered) {
                                rightElement = '<span class="bg-white/5 border border-white/5 text-slate-400 text-[10px] font-bold px-2 py-0.5 rounded-full">เป็นตัวแทนแล้ว</span>';
                            } else if (!stud.house_id) {
                                rightElement = '<span class="text-rose-400 text-[11px] font-bold">ห้องเรียนไม่มีคณะสี</span>';
                            } else {
                                rightElement = `<button type="button" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-3 py-1 rounded-xl text-xs transition-colors cursor-pointer" onclick="selectStudentForReg('${stud.Stu_id}', '${escapeHtml(stud.Stu_name)} ${escapeHtml(stud.Stu_sur)}', '${stud.house_id}', '${escapeHtml(getHouseNameTh(stud.house_name))}', 'ชั้น ม.${stud.grade_level}/${stud.room_number}')">เลือก</button>`;
                            }

                            div.innerHTML = `
                                <div>
                                    <strong class="block text-white text-sm">${stud.Stu_name} ${stud.Stu_sur}</strong>
                                    <span class="text-xs text-slate-400">รหัส: ${stud.Stu_id} • ม.${stud.grade_level}/${stud.room_number}</span>
                                    ${stud.house_name ? `<span class="inline-block text-[10px] px-1.5 py-0.5 rounded bg-white/5 border border-white/5 text-slate-300 mt-1 font-semibold">คณะ${getHouseNameTh(stud.house_name)}</span>` : ''}
                                </div>
                                <div>${rightElement}</div>
                            `;
                            resultsList.appendChild(div);
                        });
                    }
                    resultsList.style.display = 'block';
                });
        }, 300);
    }

    function selectStudentForReg(id, name, houseId, houseName, roomText) {
        document.getElementById('register-student-id').value = id;
        document.getElementById('register-house-id').value = houseId;
        document.getElementById('selected-student-display').innerText = name;
        document.getElementById('selected-student-id-display').innerText = 'รหัสนักเรียน: ' + id + ' • ' + roomText;
        document.getElementById('selected-student-house-display').innerText = 'สังกัดคณะสีตามห้องเรียน: คณะ' + houseName;
        
        document.getElementById('student-search-input').parentElement.parentElement.style.display = 'none';
        document.getElementById('appoint-house-select').parentElement.style.display = 'none';
        document.getElementById('athlete-registration-form').style.display = 'block';
        document.getElementById('search-results-list').style.display = 'none';
    }

    function cancelRegistration() {
        document.getElementById('register-student-id').value = '';
        document.getElementById('register-house-id').value = '';
        document.getElementById('student-search-input').value = '';
        document.getElementById('student-search-input').placeholder = 'กรุณาเลือกคณะสีก่อนค้นหา...';
        document.getElementById('student-search-input').disabled = true;
        document.getElementById('appoint-house-select').value = '';
        
        document.getElementById('student-search-input').parentElement.parentElement.style.display = 'block';
        document.getElementById('appoint-house-select').parentElement.style.display = 'block';
        document.getElementById('athlete-registration-form').style.display = 'none';
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

    function loadSportRegistrations(sportId) {
        const body = document.getElementById('registrations-lookup-body');
        if (!sportId) {
            body.innerHTML = '<tr><td colspan="2" class="p-8 text-center text-slate-500">กรุณาเลือกประเภทกีฬาด้านบนเพื่อเรียกแสดงรายชื่อ</td></tr>';
            return;
        }

        body.innerHTML = '<tr><td colspan="2" class="p-8 text-center text-slate-500">กำลังดึงข้อมูลรายชื่อนักกีฬา...</td></tr>';

        fetch('index.php?route=get_sport_regs&sport_id=' + sportId)
            .then(res => res.json())
            .then(data => {
                body.innerHTML = '';
                if (data.length === 0) {
                    body.innerHTML = '<tr><td colspan="2" class="p-8 text-center text-slate-500">ยังไม่มีรายชื่อนักกีฬาลงทะเบียนแข่งขันในรายการนี้</td></tr>';
                } else {
                    data.forEach(reg => {
                        const tr = document.createElement('tr');
                        tr.className = 'border-b border-white/[0.03] hover:bg-white/[0.01] transition-colors house-indicator';
                        
                        let houseNameTh = getHouseNameTh(reg.house_name);

                        tr.setAttribute('style', `--house-color: ${reg.color_code}; --house-color-rgb: ${hexToRgb(reg.color_code)};`);
                        
                        tr.innerHTML = `
                            <td class="p-3.5">
                                <strong class="block text-white">${reg.student_name}</strong>
                                <span class="text-xs text-slate-400">รหัสประจำตัว: ${reg.student_id} | ชั้น ม.${reg.grade_level}/${reg.room_number}</span>
                            </td>
                            <td class="p-3.5">
                                <span class="badge house-badge text-[11px]">${houseNameTh}</span>
                            </td>
                        `;
                        body.appendChild(tr);
                    });
                }
            });
    }

    function printRegistrations() {
        const sportSelect = document.getElementById('lookup-sport-select');
        const sportId = sportSelect.value;
        if (!sportId) {
            Swal.fire({
                icon: 'warning',
                title: 'กรุณาเลือกประเภทกีฬา',
                text: 'กรุณาเลือกชนิดกีฬาที่ต้องการพิมพ์รายชื่อก่อนครับ',
                background: '#0f172a',
                color: '#f1f5f9',
                confirmButtonColor: '#6366f1'
            });
            return;
        }

        const sportName = sportSelect.options[sportSelect.selectedIndex].text;
        const bodyContent = document.getElementById('registrations-lookup-body').innerHTML;

        if (bodyContent.includes('กรุณาเลือก') || bodyContent.includes('ยังไม่มีรายชื่อ')) {
            Swal.fire({
                icon: 'warning',
                title: 'ไม่มีรายชื่อนักกีฬา',
                text: 'ไม่มีข้อมูลนักกีฬาที่จะพิมพ์ในชนิดกีฬานี้',
                background: '#0f172a',
                color: '#f1f5f9',
                confirmButtonColor: '#6366f1'
            });
            return;
        }

        const printWindow = window.open('', '_blank', 'width=800,height=600');
        printWindow.document.write(`
            <html>
            <head>
                <title>รายชื่อนักกีฬา - ${sportName}</title>
                <style>
                    @import url('https://fonts.googleapis.com/css2?family=Sarabun:wght@400;700&display=swap');
                    body {
                        font-family: 'Sarabun', sans-serif;
                        padding: 40px;
                        color: #333;
                    }
                    h1 {
                        text-align: center;
                        font-size: 22px;
                        margin-bottom: 5px;
                    }
                    .subtitle {
                        text-align: center;
                        font-size: 14px;
                        color: #666;
                        margin-bottom: 30px;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-top: 20px;
                    }
                    th, td {
                        border: 1px solid #ddd;
                        padding: 12px 15px;
                        text-align: left;
                        font-size: 14px;
                    }
                    th {
                        background-color: #f8fafc !important;
                        font-weight: bold;
                        color: #0f172a;
                        -webkit-print-color-adjust: exact;
                        print-color-adjust: exact;
                    }
                    .text-slate-400 {
                        color: #64748b;
                        font-size: 12px;
                        display: block;
                        margin-top: 2px;
                    }
                    .badge {
                        display: inline-block;
                        padding: 4px 10px;
                        border-radius: 6px;
                        font-size: 12px;
                        font-weight: bold;
                        background-color: #f1f5f9;
                        border: 1px solid #cbd5e1;
                        color: #334155;
                    }
                    .house-badge {
                        background-color: var(--house-color) !important;
                        color: #fff !important;
                        text-shadow: 0 1px 2px rgba(0,0,0,0.2);
                        border: none;
                        -webkit-print-color-adjust: exact;
                        print-color-adjust: exact;
                    }
                </style>
            </head>
            <body>
                <h1>รายชื่อผู้สมัครลงแข่งขันกีฬา</h1>
                <div class="subtitle">ชนิดกีฬา: ${sportName} | กีฬาสีโรงเรียนพิชัย</div>
                <table>
                    <thead>
                        <tr>
                            <th>ชื่อนักกีฬา / รหัสประจำตัว</th>
                            <th>คณะสี</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${bodyContent}
                    </tbody>
                </table>
                <script>
                    window.onload = function() {
                        window.print();
                        setTimeout(function() { window.close(); }, 500);
                    }
                <\/script>
            </body>
            </html>
        `);
        printWindow.document.close();
    }

    function showTeamAthletes(sportId, houseId, houseName, houseColor) {
        if (!houseId) return;

        Swal.fire({
            title: 'กำลังโหลดรายชื่อนักกีฬา...',
            html: '<div class="flex justify-center py-4"><i class="fa-solid fa-circle-notch fa-spin text-teal-400 text-3xl"></i></div>',
            showConfirmButton: false,
            allowOutsideClick: false,
            background: '#0f172a',
            color: '#f1f5f9',
            customClass: {
                popup: 'border border-white/10 rounded-[24px]'
            }
        });

        fetch('index.php?route=get_sport_regs&sport_id=' + sportId)
            .then(res => res.json())
            .then(data => {
                const athletes = data.filter(r => r.house_id == houseId);
                let htmlContent = '';

                if (athletes.length === 0) {
                    htmlContent = `<div class="text-center py-8 text-slate-400 text-sm">ยังไม่มีนักกีฬาลงสมัครแข่งขันในประเภทนี้</div>`;
                } else {
                    htmlContent = `
                        <div class="text-left mt-2 max-h-64 overflow-y-auto pr-1">
                            <table class="w-full text-sm text-slate-300">
                                <thead>
                                    <tr class="border-b border-white/10 text-xs text-slate-400 font-bold uppercase tracking-wider text-left">
                                        <th class="pb-2">ชื่อนักกีฬา</th>
                                        <th class="pb-2 text-right">รหัสประจำตัว</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${athletes.map(a => `
                                        <tr class="border-b border-white/5 hover:bg-white/5 transition-colors">
                                            <td class="py-2.5 font-semibold text-white">
                                                ${escapeHtml(a.student_name)}
                                                <span class="block text-[11px] text-slate-400 font-normal">ชั้น ม.${escapeHtml(a.grade_level)}/${escapeHtml(a.room_number)}</span>
                                            </td>
                                            <td class="py-2.5 text-right text-xs text-slate-400">${escapeHtml(a.student_id)}</td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        </div>
                    `;
                }

                Swal.fire({
                    title: `<div class="flex items-center justify-center gap-2 text-lg font-bold font-heading">
                                <span class="w-3.5 h-3.5 rounded-full shrink-0 shadow-sm" style="background-color: ${houseColor}"></span>
                                รายชื่อนักกีฬา คณะสี${escapeHtml(houseName)}
                            </div>`,
                    html: htmlContent,
                    background: '#0f172a',
                    color: '#f1f5f9',
                    confirmButtonText: 'ปิดหน้าต่าง',
                    confirmButtonColor: '#14b8a6', // teal-500
                    customClass: {
                        popup: 'border border-white/10 rounded-[24px]',
                        confirmButton: 'rounded-xl text-xs px-5 py-2.5 font-bold cursor-pointer'
                    }
                });
            })
            .catch(err => {
                console.error("Error in showTeamAthletes:", err);
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: 'ไม่สามารถดึงข้อมูลรายชื่อนักกีฬาได้: ' + err.message,
                    background: '#0f172a',
                    color: '#f1f5f9',
                    confirmButtonColor: '#6366f1'
                });
            });
    }

    function escapeHtml(text) {
        if (text === null || text === undefined) return '';
        return String(text)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
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
/**
 * swalConfirm — แทน native confirm() ด้วย SweetAlert2 popup แบบ dark-theme
 * @param {string} formId   id ของ <form> ที่จะ submit ถ้าผู้ใช้กด "ยืนยัน"
 * @param {string} title    หัวข้อ popup
 * @param {string} text     ข้อความรายละเอียด
 */
function swalConfirm(formId, title, text) {
    Swal.fire({
        icon:                 'warning',
        title:                title,
        text:                 text,
        background:           '#0d1022',
        color:                '#f1f5f9',
        iconColor:            '#f59e0b',
        showCancelButton:     true,
        confirmButtonText:    '\u2713 \u0e22\u0e37\u0e19\u0e22\u0e31\u0e19',
        cancelButtonText:     '\u00d7 \u0e22\u0e01\u0e40\u0e25\u0e34\u0e01',
        confirmButtonColor:   '#f43f5e',
        cancelButtonColor:    'rgba(255,255,255,0.06)',
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
