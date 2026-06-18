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
<body class="text-slate-100 font-body min-h-screen relative overflow-x-hidden">

<!-- Floating Blur Ambient BG Effect Orbs -->
<div class="absolute top-[8%] left-[-10%] w-72 h-72 md:w-[500px] md:h-[500px] rounded-full bg-indigo-500/[0.08] blur-[80px] md:blur-[110px] pointer-events-none animate-pulse-slow z-0"></div>
<div class="absolute top-[40%] right-[-10%] w-72 h-72 md:w-[500px] md:h-[500px] rounded-full bg-purple-500/[0.08] blur-[80px] md:blur-[110px] pointer-events-none animate-pulse-slow-reverse z-0"></div>
<div class="absolute bottom-[15%] left-[5%] w-80 h-80 md:w-[600px] md:h-[600px] rounded-full bg-cyan-500/[0.06] blur-[90px] md:blur-[120px] pointer-events-none animate-pulse-slow z-0"></div>

<!-- Header Navbar -->
<header class="app-header sticky top-0 z-50 bg-[#070913]/85 backdrop-blur-xl border-b border-white/5 shadow-md">
    <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
        <a href="index.php" class="brand-logo text-xl md:text-2xl font-black flex items-center gap-2">
            <i class="fa-solid fa-trophy text-[#d4af37]"></i>
            <span class="bg-gradient-to-r from-white to-slate-400 bg-clip-text text-transparent">SportDay</span>
        </a>
        <div class="flex items-center gap-4">
            <span class="hidden sm:inline-flex bg-gradient-to-r from-indigo-500/10 to-purple-600/10 text-[#cbd5e1] border border-indigo-500/20 text-xs font-bold px-3.5 py-1.5 rounded-full shadow-md">ระบบจัดการอาจารย์</span>
            <div class="flex items-center gap-2 pl-3 border-l border-white/5">
                <span class="hidden md:inline text-xs text-slate-300 font-semibold"><?= htmlspecialchars($_SESSION['user']['name']) ?></span>
                <a href="index.php?route=login&action=logout" class="bg-white/5 hover:bg-white/10 text-white border border-white/5 hover:border-white/10 font-bold px-3 py-1.5 rounded-xl text-xs transition-colors duration-200">ออกจากระบบ</a>
            </div>
        </div>
    </div>
</header>

<div class="max-w-6xl mx-auto px-4 py-8 relative z-10">
    <div class="mb-8">
        <h2 class="text-2xl md:text-3xl font-black text-white mb-2">แผงควบคุมการจัดการกีฬาสี</h2>
        <p class="text-slate-400 text-sm md:text-base">จัดการคณะสีให้ห้องเรียน แต่งตั้งตัวแทนสีนักเรียน กำหนดตารางการแข่ง และบันทึกคะแนนสรุป</p>
    </div>

    <!-- Flash alerts rendered via UtilController (SweetAlert2) -->

    <!-- Responsive Tabs Navigation -->
    <div class="flex gap-1 overflow-x-auto border-b border-white/5 mb-8 pb-1 scrollbar-none">
        <button class="tab-btn active px-4 py-3 text-sm md:text-base font-bold whitespace-nowrap cursor-pointer flex items-center gap-2" onclick="switchTab('athletes-tab')">
            <i class="fa-solid fa-users-gear text-indigo-400"></i>
            จัดการคณะสีและตัวแทน
        </button>
        <button class="tab-btn px-4 py-3 text-sm md:text-base font-bold whitespace-nowrap cursor-pointer flex items-center gap-2" onclick="switchTab('enroll-tab')">
            <i class="fa-solid fa-file-signature text-purple-400"></i>
            ชนิดกีฬาและการสมัคร
        </button>
        <button class="tab-btn px-4 py-3 text-sm md:text-base font-bold whitespace-nowrap cursor-pointer flex items-center gap-2" onclick="switchTab('matches-tab')">
            <i class="fa-solid fa-calendar-check text-rose-400"></i>
            ตารางแข่งและลงคะแนน
        </button>
        <button class="tab-btn px-4 py-3 text-sm md:text-base font-bold whitespace-nowrap cursor-pointer flex items-center gap-2" onclick="switchTab('leaderboard-tab')">
            <i class="fa-solid fa-ranking-star text-[#d4af37]"></i>
            ตารางสรุปคะแนน
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
                        <div class="w-full sm:w-auto">
                            <select id="lookup-sport-select" class="w-full sm:w-auto bg-white/3 border border-white/5 focus:border-indigo-500 focus:bg-white/5 focus:ring-3 focus:ring-indigo-500/15 rounded-xl py-2 px-3 text-white text-xs outline-none transition-all duration-200" onchange="loadSportRegistrations(this.value)">
                                <option value="">-- กรองตามประเภทกีฬา --</option>
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
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Schedule Form -->
                <div class="lg:col-span-1 bg-slate-900/60 backdrop-blur-xl border border-white/5 rounded-[24px] p-6 shadow-lg h-fit">
                    <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-calendar-plus text-indigo-400"></i>
                        กำหนดตารางแข่งขัน
                    </h3>
                    <form action="index.php?route=dashboard&action=create_match" method="POST">
                        <div class="mb-4">
                            <label class="block mb-2 text-xs font-bold text-slate-400 uppercase tracking-wider" for="schedule_sport_id">รายการกีฬา</label>
                            <select name="sport_id" id="schedule_sport_id" class="w-full bg-white/3 border border-white/5 focus:border-indigo-500 focus:bg-white/5 focus:ring-3 focus:ring-indigo-500/15 rounded-xl py-2.5 px-3.5 text-white text-sm outline-none transition-all duration-200" required>
                                <option value="">-- กรุณาเลือกรายการกีฬา --</option>
                                <?php foreach ($sports as $sport): ?>
                                    <option value="<?= $sport['id'] ?>"><?= htmlspecialchars($sport['sport_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-6">
                            <label class="block mb-2 text-xs font-bold text-slate-400 uppercase tracking-wider" for="event_date">วันและเวลาที่แข่งขัน</label>
                            <input type="datetime-local" name="event_date" id="event_date" class="w-full bg-white/3 border border-white/5 focus:border-indigo-500 focus:bg-white/5 focus:ring-3 focus:ring-indigo-500/15 rounded-xl py-2.5 px-3.5 text-white text-sm outline-none transition-all duration-200" required>
                        </div>
                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 rounded-xl text-sm transition-colors cursor-pointer">บันทึกตารางการแข่ง</button>
                    </form>
                </div>

                <!-- Matches List -->
                <div class="lg:col-span-2 bg-slate-900/60 backdrop-blur-xl border border-white/5 rounded-[24px] p-6 md:p-8 shadow-lg">
                    <h3 class="text-xl font-bold mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-list-check text-indigo-400"></i>
                        ตารางแมตช์การแข่งขันทั้งหมด
                    </h3>
                    
                    <div class="overflow-x-auto w-full rounded-xl border border-white/5">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                                <tr class="bg-white/2 border-b border-white/5">
                                    <th class="p-3 text-slate-400 font-bold text-xs uppercase tracking-wider">รายการแข่งขัน</th>
                                    <th class="p-3 text-slate-400 font-bold text-xs uppercase tracking-wider">วันเวลา</th>
                                    <th class="p-3 text-slate-400 font-bold text-xs uppercase tracking-wider">สถานะ</th>
                                    <th class="p-3 text-slate-400 font-bold text-xs uppercase tracking-wider">ผลแข่งขัน</th>
                                    <th class="p-3 text-slate-400 font-bold text-xs uppercase tracking-wider">การจัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($matches)): ?>
                                    <tr>
                                        <td colspan="5" class="p-8 text-center text-slate-500">ยังไม่มีข้อมูลการแข่งขันลงกำหนดการ</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($matches as $match): ?>
                                        <tr class="border-b border-white/[0.03] hover:bg-white/[0.01] transition-colors">
                                            <td class="p-3.5">
                                                <strong class="block text-white"><?= htmlspecialchars($match['sport_name']) ?></strong>
                                                <span class="text-xs text-slate-400"><?= htmlspecialchars($match['category']) ?></span>
                                            </td>
                                            <td class="p-3.5">
                                                <span class="text-xs font-semibold text-slate-300"><?= $presenter->formatDate($match['event_date']) ?></span>
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
                                                <?php if ($match['status'] === 'Completed' && isset($matchResults[$match['id']])): ?>
                                                    <div class="text-xs flex flex-col gap-1">
                                                        <?php foreach ($matchResults[$match['id']] as $res): 
                                                            $houseNameTh = $presenter->getHouseNameTh($res['house_name']);
                                                        ?>
                                                            <?php if (!empty($res['medal'])): ?>
                                                                <div>
                                                                    <span class="house-text font-bold" <?= $presenter->getHouseStyle($res['color_code']) ?>><?= htmlspecialchars($houseNameTh) ?></span>: 
                                                                    <?= $presenter->getMedalBadge($res['medal']) ?> (+<?= $res['points'] ?>)
                                                                </div>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="text-slate-500 text-xs">รอผลแข่งขัน</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="p-3.5">
                                                <div class="flex gap-1">
                                                    <?php if ($match['status'] !== 'Completed'): ?>
                                                        <button class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-2.5 py-1 rounded-lg text-xs transition-colors cursor-pointer" onclick="openScoreModal(<?= $match['id'] ?>, '<?= htmlspecialchars($match['sport_name']) ?>')">ลงผล</button>
                                                    <?php endif; ?>
                                                    <form id="del-match-<?= $match['id'] ?>" action="index.php?route=dashboard&action=delete_match" method="POST">
                                                        <input type="hidden" name="match_id" value="<?= $match['id'] ?>">
                                                        <button type="button" class="bg-rose-500/10 hover:bg-rose-500/20 text-rose-300 border border-rose-500/20 px-2 py-1 rounded-lg text-xs transition-colors cursor-pointer"
                                                            onclick="swalConfirm('del-match-<?= $match['id'] ?>', '\u0e25\u0e1a\u0e01\u0e32\u0e23\u0e41\u0e02\u0e48\u0e07\u0e02\u0e31\u0e19', '\u0e15\u0e49\u0e2d\u0e07\u0e01\u0e32\u0e23\u0e25\u0e1a\u0e41\u0e21\u0e15\u0e0a\u0e4c\u0e01\u0e32\u0e23\u0e41\u0e02\u0e48\u0e07\u0e02\u0e31\u0e19\u0e19\u0e35\u0e49\u0e2b\u0e23\u0e37\u0e2d\u0e44\u0e21\u0e48? \u0e01\u0e32\u0e23\u0e01\u0e23\u0e30\u0e17\u0e33\u0e19\u0e35\u0e49\u0e44\u0e21\u0e48\u0e2a\u0e32\u0e21\u0e32\u0e23\u0e16\u0e22\u0e49\u0e2d\u0e19\u0e01\u0e25\u0e31\u0e1a\u0e44\u0e14\u0e49')"
                                                        >ลบ</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <?php endif; ?>
                        </table>
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
                        <?php $rank = 1; foreach ($leaderboard as $row): 
                            $houseNameTh = $presenter->getHouseNameTh($row['house_name']);
                        ?>
                            <div class="flex justify-between items-center p-4 rounded-xl border border-white/5 bg-white/[0.01] hover:bg-white/[0.02] border-l-4 border-[var(--house-color)] transition-all duration-200" <?= $presenter->getHouseStyle($row['color_code']) ?>>
                                <div class="flex items-center gap-3">
                                    <div class="text-xl font-black text-slate-400 w-6 text-center"><?= $rank++ ?></div>
                                    <div>
                                        <div class="font-bold text-white mb-0.5 text-base"><?= htmlspecialchars($houseNameTh) ?></div>
                                        <div class="flex gap-1.5">
                                            <span class="inline-flex bg-yellow-500/10 text-yellow-400 border border-yellow-500/20 text-[9px] font-bold px-2 py-0.5 rounded-full">ทอง: <?= $row['gold_count'] ?></span>
                                            <span class="inline-flex bg-slate-300/10 text-slate-300 border border-slate-300/20 text-[9px] font-bold px-2 py-0.5 rounded-full">เงิน: <?= $row['silver_count'] ?></span>
                                            <span class="inline-flex bg-orange-500/10 text-orange-400 border border-orange-500/20 text-[9px] font-bold px-2 py-0.5 rounded-full">ทองแดง: <?= $row['bronze_count'] ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-lg font-black text-indigo-400"><?= htmlspecialchars($row['total_points']) ?> คะแนน</div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
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
                                <span class="text-xs text-slate-400">รหัสประจำตัว: ${reg.student_id}</span>
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

    function escapeHtml(text) {
        return text
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
