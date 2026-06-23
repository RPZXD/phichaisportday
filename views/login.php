<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ - Phichai SportDay</title>
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
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-4px); }
            75% { transform: translateX(4px); }
        }
        .animate-shake {
            animation: shake 0.4s ease-in-out;
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
    </style>
</head>
<body class="text-slate-100 font-body min-h-screen relative overflow-hidden">

<!-- Floating Blur Ambient BG Effect Orbs -->
<div class="absolute top-[10%] left-[-10%] w-72 h-72 md:w-[450px] md:h-[450px] rounded-full bg-indigo-500/[0.08] blur-[80px] md:blur-[110px] pointer-events-none animate-pulse-slow z-0"></div>
<div class="absolute bottom-[10%] right-[-10%] w-72 h-72 md:w-[450px] md:h-[450px] rounded-full bg-purple-500/[0.08] blur-[80px] md:blur-[110px] pointer-events-none animate-pulse-slow-reverse z-0"></div>

<div class="min-h-screen flex items-center justify-center p-6 relative z-10">
    <!-- Back to home -->
    <div class="absolute top-6 left-6">
        <a href="index.php?route=landing" class="inline-flex items-center gap-2 bg-white/5 hover:bg-white/10 text-white font-bold px-4 py-2.5 rounded-xl border border-white/5 hover:border-white/15 transition-all duration-200 shadow-md select-none">
            <i class="fa-solid fa-arrow-left"></i>
            กลับหน้าหลัก
        </a>
    </div>

    <!-- Login card -->
    <div class="w-full max-w-[440px] bg-slate-900/65 backdrop-blur-xl border border-white/5 p-8 sm:p-10 rounded-[28px] shadow-2xl animate-[slideUp_0.5s_cubic-bezier(0.34,1.56,0.64,1)_forwards]">
        <div class="text-center mb-8">
            <h1 class="text-4xl font-black mb-2 flex items-center justify-center gap-2 select-none font-heading">
                <i class="fa-solid fa-trophy text-[#d4af37] drop-shadow-[0_0_8px_rgba(212,175,55,0.45)]"></i>
                <span class="bg-gradient-to-r from-white via-slate-100 to-slate-400 bg-clip-text text-transparent">Phichai Game 2026</span>
            </h1>
            <p class="text-slate-400 text-xs sm:text-sm font-semibold">ลงชื่อเข้าใช้ระบบจัดการกีฬาสีโรงเรียนพิชัย</p>
        </div>


        <form action="index.php?route=login" method="POST">
            <!-- Hidden role input -->
            <input type="hidden" name="role" id="selected-role" value="<?= htmlspecialchars($old_role) ?>">

            <!-- Switcher Role Tabs -->
            <div class="grid grid-cols-2 bg-white/3 p-1 rounded-xl mb-6 border border-white/5">
                <button type="button" class="role-btn font-heading font-bold py-2.5 text-sm rounded-lg transition-all duration-200 flex items-center justify-center gap-1.5 cursor-pointer text-slate-400 select-none" onclick="setRole('student')">
                    <i class="fa-solid fa-user-graduate"></i>
                    นักเรียน
                </button>
                <button type="button" class="role-btn font-heading font-bold py-2.5 text-sm rounded-lg transition-all duration-200 flex items-center justify-center gap-1.5 cursor-pointer text-slate-400 select-none" onclick="setRole('teacher')">
                    <i class="fa-solid fa-user-shield"></i>
                    อาจารย์ผู้ดูแล
                </button>
            </div>

            <!-- Username Input -->
            <div class="mb-4">
                <label for="username" class="block mb-2 text-xs font-bold text-slate-400 uppercase tracking-wider" id="id-label">รหัสนักเรียน</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"><i class="fa-solid fa-user"></i></span>
                    <input type="text" name="username" id="username" class="w-full bg-white/3 border border-white/5 focus:border-indigo-500 focus:bg-white/5 focus:ring-3 focus:ring-indigo-500/15 rounded-xl py-3 pl-11 pr-4 text-white text-sm outline-none transition-all duration-200" placeholder="ระบุรหัสนักเรียน" value="<?= htmlspecialchars($old_username) ?>" required autofocus autocomplete="off">
                </div>
            </div>

            <!-- Password Input -->
            <div class="mb-6">
                <label for="password" class="block mb-2 text-xs font-bold text-slate-400 uppercase tracking-wider">รหัสผ่าน</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" name="password" id="password" class="w-full bg-white/3 border border-white/5 focus:border-indigo-500 focus:bg-white/5 focus:ring-3 focus:ring-indigo-500/15 rounded-xl py-3 pl-11 pr-4 text-white text-sm outline-none transition-all duration-200" placeholder="••••••••" required>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full bg-gradient-to-r from-indigo-500 via-purple-600 to-pink-500 hover:from-indigo-600 hover:via-purple-700 hover:to-pink-600 text-white font-bold py-3.5 rounded-xl shadow-lg hover:shadow-indigo-500/25 transition-all duration-300 hover:scale-101 hover:-translate-y-0.5 cursor-pointer">
                เข้าสู่ระบบ
            </button>
        </form>
    </div>
</div>

<script>
    function setRole(role) {
        document.getElementById('selected-role').value = role;
        
        const buttons = document.querySelectorAll('.role-btn');
        buttons.forEach(btn => {
            if (btn.innerText.trim().includes(role === 'student' ? 'นักเรียน' : 'อาจารย์')) {
                btn.classList.add('bg-indigo-600', 'text-white', 'shadow-md');
                btn.classList.remove('text-slate-400');
            } else {
                btn.classList.remove('bg-indigo-600', 'text-white', 'shadow-md');
                btn.classList.add('text-slate-400');
            }
        });

        const idLabel = document.getElementById('id-label');
        const usernameInput = document.getElementById('username');

        if (role === 'teacher') {
            idLabel.innerText = 'รหัสอาจารย์ผู้ดูแล';
            usernameInput.placeholder = 'ระบุรหัสอาจารย์ (เช่น 001)';
        } else {
            idLabel.innerText = 'รหัสนักเรียน';
            usernameInput.placeholder = 'ระบุรหัสนักเรียน (เช่น 20181)';
        }
        usernameInput.focus();
    }

    window.onload = function() {
        setRole(document.getElementById('selected-role').value);
    }
</script>

<?php UtilController::renderFlashJS(); ?>
</body>
</html>
