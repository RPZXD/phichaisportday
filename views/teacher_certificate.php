<?php
/**
 * Teacher Certificate Layout Designer & Management
 * Features visual editor, real-time live preview coordinates, and mock athlete rendering.
 */
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ออกแบบและจัดการเกียรติบัตร - SportDay</title>
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

        .designer-container {
            min-height: calc(100vh - 80px);
        }

        .cert-preview-box {
            position: relative;
            width: 100%;
            aspect-ratio: 1.414 / 1;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            background-color: #ffffff;
            color: #0f172a;
            border-radius: 4px;
            overflow: hidden;
            user-select: none;
            transition: all 0.3s ease;
        }

        .slider-group {
            background-color: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 12px;
            margin-bottom: 12px;
            transition: all 0.2s ease;
        }
        
        .slider-group:hover {
            background-color: rgba(255, 255, 255, 0.04);
            border-color: rgba(99, 102, 241, 0.2);
        }
    </style>
</head>
<body class="bg-[#05070f] text-slate-100 font-body min-h-screen relative overflow-x-hidden">

<?php 
require_once __DIR__ . '/components/ambient_orbs.php'; 
require_once __DIR__ . '/components/header.php'; 
?>

<div class="max-w-7xl mx-auto px-4 py-8 designer-container">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left Panel: Controls Form -->
        <div class="lg:col-span-5 bg-slate-900/60 backdrop-blur-xl border border-white/5 rounded-[24px] p-6 shadow-lg flex flex-col gap-6">
            <div class="flex items-center justify-between pb-4 border-b border-white/5">
                <h2 class="text-xl font-bold flex items-center gap-2 font-heading text-white">
                    <i class="fa-solid fa-sliders text-indigo-400"></i>
                    เครื่องมือออกแบบเกียรติบัตร
                </h2>
                <span class="bg-indigo-500/10 border border-indigo-500/25 text-indigo-300 text-[10px] font-bold px-3 py-1 rounded-full uppercase">
                    Layout Editor
                </span>
            </div>

            <form action="index.php?route=teacher_certificate&action=save_settings" method="POST" id="certificate-settings-form" class="flex flex-col gap-5">
                
                <!-- Presets & Style -->
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">1. ธีมและสไตล์พื้นหลัง</h3>
                    <div class="grid grid-cols-2 gap-2.5">
                        <label class="flex flex-col gap-1 p-3 rounded-xl border border-white/5 bg-white/2 hover:bg-white/5 cursor-pointer transition-all">
                            <input type="radio" name="bg_style" value="classic-gold" class="hidden" <?= $settings['bg_style'] === 'classic-gold' ? 'checked' : '' ?> onchange="changeBgStyle('classic-gold')">
                            <span class="text-xs font-bold text-white flex items-center gap-1.5">
                                <span class="w-3 h-3 rounded-full bg-[#d4af37]"></span> Classic Gold
                            </span>
                            <span class="text-[10px] text-slate-400">กรอบทองสองเส้นดั้งเดิม</span>
                        </label>
                        
                        <label class="flex flex-col gap-1 p-3 rounded-xl border border-white/5 bg-white/2 hover:bg-white/5 cursor-pointer transition-all">
                            <input type="radio" name="bg_style" value="emerald-premium" class="hidden" <?= $settings['bg_style'] === 'emerald-premium' ? 'checked' : '' ?> onchange="changeBgStyle('emerald-premium')">
                            <span class="text-xs font-bold text-white flex items-center gap-1.5">
                                <span class="w-3 h-3 rounded-full bg-emerald-600"></span> Emerald Green
                            </span>
                            <span class="text-[10px] text-slate-400">กรอบเขียวมรกตคู่พรีเมียม</span>
                        </label>

                        <label class="flex flex-col gap-1 p-3 rounded-xl border border-white/5 bg-white/2 hover:bg-white/5 cursor-pointer transition-all">
                            <input type="radio" name="bg_style" value="royal-purple" class="hidden" <?= $settings['bg_style'] === 'royal-purple' ? 'checked' : '' ?> onchange="changeBgStyle('royal-purple')">
                            <span class="text-xs font-bold text-white flex items-center gap-1.5">
                                <span class="w-3 h-3 rounded-full bg-purple-700"></span> Royal Purple
                            </span>
                            <span class="text-[10px] text-slate-400">กรอบม่วงราชวงศ์โบราณ</span>
                        </label>

                        <label class="flex flex-col gap-1 p-3 rounded-xl border border-white/5 bg-white/2 hover:bg-white/5 cursor-pointer transition-all">
                            <input type="radio" name="bg_style" value="minimal-blue" class="hidden" <?= $settings['bg_style'] === 'minimal-blue' ? 'checked' : '' ?> onchange="changeBgStyle('minimal-blue')">
                            <span class="text-xs font-bold text-white flex items-center gap-1.5">
                                <span class="w-3 h-3 rounded-full bg-sky-500"></span> Minimal Blue
                            </span>
                            <span class="text-[10px] text-slate-400">กรอบฟ้าเดี่ยวทันสมัย</span>
                        </label>
                    </div>
                </div>

                <!-- Custom Border Color -->
                <div class="flex items-center justify-between p-3 rounded-xl border border-white/5 bg-white/2">
                    <div>
                        <span class="text-xs font-bold text-white block">กำหนดสีขอบและตราประทับเอง</span>
                        <span class="text-[10px] text-slate-400">กำหนดสี Accent หลักของเทมเพลต</span>
                    </div>
                    <input type="color" name="border_color" id="border_color_input" value="<?= htmlspecialchars($settings['border_color']) ?>" class="w-10 h-10 rounded-lg cursor-pointer bg-transparent border-0 outline-none" oninput="changeBorderColor(this.value)">
                </div>

                <!-- Text Customization -->
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">2. กำหนดข้อความหลัก</h3>
                    <div class="flex flex-col gap-3">
                        <div>
                            <label class="text-[10px] text-slate-400 font-bold block mb-1">หัวข้อเกียรติบัตร (Header Title)</label>
                            <input type="text" name="header_title" id="input-header-title" class="w-full bg-white/3 border border-white/5 focus:border-indigo-500 focus:bg-white/5 rounded-xl py-2 px-3 text-white text-xs outline-none transition-all" value="<?= htmlspecialchars($settings['header_title']) ?>" oninput="updatePreviewText('header_text_val', this.value)">
                        </div>
                        <div>
                            <label class="text-[10px] text-slate-400 font-bold block mb-1">ชื่อประเภทรางวัล (Certificate Title)</label>
                            <input type="text" name="cert_title" id="input-cert-title" class="w-full bg-white/3 border border-white/5 focus:border-indigo-500 focus:bg-white/5 rounded-xl py-2 px-3 text-white text-xs outline-none transition-all" value="<?= htmlspecialchars($settings['cert_title']) ?>" oninput="updatePreviewText('main_title_val', this.value)">
                        </div>
                        <div>
                            <label class="text-[10px] text-slate-400 font-bold block mb-1">คำนำหน้าชื่อ (Body Pattern 1)</label>
                            <input type="text" name="body_pattern_1" id="input-body-pat1" class="w-full bg-white/3 border border-white/5 focus:border-indigo-500 focus:bg-white/5 rounded-xl py-2 px-3 text-white text-xs outline-none transition-all" value="<?= htmlspecialchars($settings['body_pattern_1']) ?>" oninput="updatePreviewText('prefix_text_val', this.value)">
                        </div>
                        <div>
                            <label class="text-[10px] text-slate-400 font-bold block mb-1">ประโยคประกาศรางวัล (Body Pattern 2)</label>
                            <input type="text" name="body_pattern_2" id="input-body-pat2" class="w-full bg-white/3 border border-white/5 focus:border-indigo-500 focus:bg-white/5 rounded-xl py-2 px-3 text-white text-xs outline-none transition-all" value="<?= htmlspecialchars($settings['body_pattern_2']) ?>" oninput="updatePreviewText('body_line1_val_prefix', this.value)">
                        </div>
                        <div>
                            <label class="text-[10px] text-slate-400 font-bold block mb-1">รายละเอียดกีฬา (Body Pattern 3)</label>
                            <input type="text" name="body_pattern_3" id="input-body-pat3" class="w-full bg-white/3 border border-white/5 focus:border-indigo-500 focus:bg-white/5 rounded-xl py-2 px-3 text-white text-xs outline-none transition-all" value="<?= htmlspecialchars($settings['body_pattern_3']) ?>" oninput="updatePreviewPattern3(this.value)">
                            <span class="text-[9px] text-slate-500 mt-1 block">* รองรับโทเค็น: {sport_name}, {category}</span>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="text-[10px] text-slate-400 font-bold block mb-1">คำลงท้ายลายเซ็นซ้าย</label>
                                <input type="text" name="sig_left_title" id="input-sig-l" class="w-full bg-white/3 border border-white/5 focus:border-indigo-500 focus:bg-white/5 rounded-xl py-2 px-3 text-white text-xs outline-none transition-all" value="<?= htmlspecialchars($settings['sig_left_title']) ?>" oninput="updatePreviewText('sig_l_val', this.value)">
                            </div>
                            <div>
                                <label class="text-[10px] text-slate-400 font-bold block mb-1">คำลงท้ายลายเซ็นขวา</label>
                                <input type="text" name="sig_right_title" id="input-sig-r" class="w-full bg-white/3 border border-white/5 focus:border-indigo-500 focus:bg-white/5 rounded-xl py-2 px-3 text-white text-xs outline-none transition-all" value="<?= htmlspecialchars($settings['sig_right_title']) ?>" oninput="updatePreviewText('sig_r_val', this.value)">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Position Coordinates Sliders -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">3. จัดตำแหน่งข้อความต่างๆ (Y-Offset & Size)</h3>
                        <button type="button" onclick="resetLayoutDefaults()" class="text-[10px] text-indigo-400 hover:text-indigo-300 font-bold underline cursor-pointer">
                            คืนค่าเริ่มต้น
                        </button>
                    </div>
                    <div class="max-h-72 overflow-y-auto pr-1 flex flex-col gap-2 border border-white/5 rounded-xl p-3 bg-white/1">
                        
                        <?php 
                        $labels = [
                            'header_text' => 'หัวข้อเกียรติบัตรด้านบน',
                            'main_title' => 'ชื่อใบประกาศเกียรติยศ',
                            'prefix_text' => 'ข้อความ "ให้ไว้เพื่อแสดงว่า"',
                            'student_name' => 'ชื่อนักเรียนนักกีฬา',
                            'body_line1' => 'เนื้อหาผลงาน / สังกัดคณะสี',
                            'medal_badge' => 'ป้ายระดับเหรียญรางวัล',
                            'body_line2' => 'ประเภทกีฬาและหมวดหมู่',
                            'date_text' => 'วันที่ออกเกียรติบัตร',
                            'signatures' => 'แถบลายเซ็นกรรมการคู่'
                        ];
                        foreach ($labels as $key => $title): 
                            $top = isset($layout[$key]['top']) ? intval($layout[$key]['top']) : 0;
                            $size = isset($layout[$key]['fontSize']) ? intval($layout[$key]['fontSize']) : 12;
                            $color = isset($layout[$key]['color']) ? $layout[$key]['color'] : '#000000';
                            $weight = isset($layout[$key]['fontWeight']) ? $layout[$key]['fontWeight'] : 'normal';
                        ?>
                            <div class="slider-group">
                                <span class="text-xs font-bold text-white block mb-2"><?= $title ?></span>
                                <div class="flex flex-col gap-2">
                                    <div class="flex items-center justify-between text-[10px]">
                                        <span class="text-slate-400">ตำแหน่งแนวตั้ง (Top Offset)</span>
                                        <span class="font-bold text-indigo-300" id="val-top-<?= $key ?>"><?= $top ?>%</span>
                                    </div>
                                    <input type="range" name="pos[<?= $key ?>][top]" min="0" max="100" value="<?= $top ?>" class="w-full h-1 bg-white/10 rounded-lg appearance-none cursor-pointer accent-indigo-500" oninput="changeElementTop('<?= $key ?>', this.value)">
                                    
                                    <div class="flex items-center justify-between text-[10px] mt-1">
                                        <span class="text-slate-400">ขนาดตัวอักษร (Font Size)</span>
                                        <span class="font-bold text-indigo-300" id="val-size-<?= $key ?>"><?= $size ?>px</span>
                                    </div>
                                    <input type="range" name="pos[<?= $key ?>][fontSize]" min="10" max="80" value="<?= $size ?>" class="w-full h-1 bg-white/10 rounded-lg appearance-none cursor-pointer accent-indigo-500" oninput="changeElementSize('<?= $key ?>', this.value)">
                                    
                                    <div class="flex items-center justify-between text-[10px] mt-1">
                                        <span class="text-slate-400">สีตัวอักษร</span>
                                        <input type="color" name="pos[<?= $key ?>][color]" value="<?= $color ?>" class="w-6 h-6 rounded cursor-pointer bg-transparent border-0" oninput="changeElementColor('<?= $key ?>', this.value)">
                                    </div>
                                    
                                    <!-- Store weight mapping -->
                                    <input type="hidden" name="pos[<?= $key ?>][fontWeight]" value="<?= $weight ?>">
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <!-- Seal options separately -->
                        <?php 
                        $seal_top = isset($layout['seal']['top']) ? intval($layout['seal']['top']) : 75;
                        $seal_scale = isset($layout['seal']['scale']) ? floatval($layout['seal']['scale']) : 1.0;
                        ?>
                        <div class="slider-group">
                            <span class="text-xs font-bold text-white block mb-2">ตราประทับสีทอง (Seal)</span>
                            <div class="flex flex-col gap-2">
                                <div class="flex items-center justify-between text-[10px]">
                                    <span class="text-slate-400">ตำแหน่งแนวตั้ง</span>
                                    <span class="font-bold text-indigo-300" id="val-top-seal"><?= $seal_top ?>%</span>
                                </div>
                                <input type="range" name="seal_top" min="0" max="100" value="<?= $seal_top ?>" class="w-full h-1 bg-white/10 rounded-lg appearance-none cursor-pointer accent-indigo-500" oninput="changeElementTop('seal', this.value)">
                                
                                <div class="flex items-center justify-between text-[10px] mt-1">
                                    <span class="text-slate-400">ขนาดสเกล (Scale Ratio)</span>
                                    <span class="font-bold text-indigo-300" id="val-scale-seal"><?= $seal_scale ?>x</span>
                                </div>
                                <input type="range" name="seal_scale" min="0.5" max="2.0" step="0.1" value="<?= $seal_scale ?>" class="w-full h-1 bg-white/10 rounded-lg appearance-none cursor-pointer accent-indigo-500" oninput="changeSealScale(this.value)">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 pt-3 border-t border-white/5">
                    <a href="index.php?route=dashboard" class="w-1/3 bg-white/5 hover:bg-white/10 text-white font-bold py-3 rounded-xl text-xs flex items-center justify-center transition-colors">
                        ยกเลิก
                    </a>
                    <button type="submit" class="w-2/3 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-bold py-3 rounded-xl text-xs flex items-center justify-center gap-1.5 shadow-lg shadow-indigo-500/10 cursor-pointer">
                        <i class="fa-solid fa-circle-check"></i>
                        บันทึกและเปิดใช้งาน
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Right Panel: Live Preview Visual Canvas -->
        <div class="lg:col-span-7 flex flex-col gap-6">
            
            <!-- Dynamic Preview Selector Control bar -->
            <div class="bg-slate-900/60 backdrop-blur-xl border border-white/5 rounded-2xl p-4 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-md">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-eye text-[#d4af37] animate-pulse"></i>
                    <span class="text-xs font-bold text-white">ทดสอบพรีวิวตามลำดับและรางวัล</span>
                </div>
                <div class="flex gap-2 w-full sm:w-auto">
                    <!-- Award level filter preview -->
                    <select id="preview-award-select" class="bg-white/3 border border-white/5 focus:border-indigo-500 focus:bg-white/5 rounded-xl py-1.5 px-3 text-white text-xs outline-none transition-all cursor-pointer w-full sm:w-auto" onchange="previewAwardChange(this.value)">
                        <option value="Gold">ชนะเลิศ (Gold) 🥇</option>
                        <option value="Silver">รองชนะเลิศอันดับที่ 1 (Silver) 🥈</option>
                        <option value="Bronze">รองชนะเลิศอันดับที่ 2 (Bronze) 🥉</option>
                        <option value="RunnerUp3">รองชนะเลิศอันดับที่ 3 (RunnerUp3) 🏅</option>
                        <option value="Participant">เข้าร่วมการแข่งขัน (Participant) 🌟</option>
                    </select>
                </div>
            </div>

            <!-- Visual Simulated A4 Canvas -->
            <div class="cert-preview-box" id="preview-canvas-box" style="border-color: <?= htmlspecialchars($settings['border_color']) ?>;">
                
                <!-- Inner Border -->
                <div class="absolute inset-[10px] border-2 pointer-events-none z-10" id="preview-inner-border" style="border-color: <?= htmlspecialchars($settings['border_color']) ?>55;"></div>
                
                <!-- Corners -->
                <div class="absolute w-10 h-10 border-5 z-20 top-[20px] left-[20px] border-r-0 border-b-0 orn-corner" style="border-color: <?= htmlspecialchars($settings['border_color']) ?>;"></div>
                <div class="absolute w-10 h-10 border-5 z-20 top-[20px] right-[20px] border-l-0 border-b-0 orn-corner" style="border-color: <?= htmlspecialchars($settings['border_color']) ?>;"></div>
                <div class="absolute w-10 h-10 border-5 z-20 bottom-[20px] left-[20px] border-r-0 border-t-0 orn-corner" style="border-color: <?= htmlspecialchars($settings['border_color']) ?>;"></div>
                <div class="absolute w-10 h-10 border-5 z-20 bottom-[20px] right-[20px] border-l-0 border-t-0 orn-corner" style="border-color: <?= htmlspecialchars($settings['border_color']) ?>;"></div>

                <!-- Watermark -->
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-48 h-48 opacity-[0.03] pointer-events-none z-0 flex items-center justify-center" id="preview-watermark" style="color: <?= htmlspecialchars($settings['border_color']) ?>;">
                    <i class="fa-solid fa-trophy text-[220px]"></i>
                </div>

                <!-- 1. Header Text -->
                <div class="absolute left-0 right-0 text-center select-none font-heading uppercase" id="preview-header_text">
                    <span id="header_text_val"><?= htmlspecialchars($settings['header_title']) ?></span>
                </div>

                <!-- 2. Main Title -->
                <div class="absolute left-0 right-0 text-center select-none font-heading tracking-wider uppercase" id="preview-main_title">
                    <span id="main_title_val"><?= htmlspecialchars($settings['cert_title']) ?></span>
                </div>

                <!-- 3. Prefix Text -->
                <div class="absolute left-0 right-0 text-center select-none text-slate-500 uppercase font-semibold" id="preview-prefix_text">
                    <span id="prefix_text_val"><?= htmlspecialchars($settings['body_pattern_1']) ?></span>
                </div>

                <!-- 4. Student Name -->
                <div class="absolute left-0 right-0 text-center select-none font-heading" id="preview-student_name">
                    <span class="border-b-2 border-dashed border-[#d4af37]/45 px-12 pb-1" id="preview-student_name_val">นาย สมศักดิ์ รักกีฬา</span>
                </div>

                <!-- 5. Body Line 1 (House name details) -->
                <div class="absolute left-0 right-0 text-center select-none leading-relaxed" id="preview-body_line1">
                    <span id="body_line1_val_prefix"><?= htmlspecialchars($settings['body_pattern_2']) ?></span>
                    <span class="font-black underline decoration-[#d4af37]/45 underline-offset-4" id="preview-house-span" style="color: #6366f1;">คณะสีชมพู</span>
                </div>

                <!-- 6. Medal Badge -->
                <div class="absolute left-0 right-0 text-center select-none" id="preview-medal_badge">
                    <span class="inline-flex items-center gap-2 px-6 py-1 rounded-full font-black border uppercase shadow-sm select-none" id="preview-badge-pill" style="color: #8a6d1c; background-color: rgba(245, 158, 11, 0.1); border-color: rgba(245, 158, 11, 0.2);">
                        <span id="preview-medal-emoji">🥇</span> <span id="preview-medal-name">ชนะเลิศ</span>
                    </span>
                </div>

                <!-- 7. Body Line 2 (Sport name & Category) -->
                <div class="absolute left-0 right-0 text-center select-none leading-relaxed" id="preview-body_line2">
                    <span id="preview-body-pat3-render">ในประเภทกีฬา ฟุตซอล (หมวดหมู่: ชาย ม.ปลาย)</span>
                </div>

                <!-- 8. Date Text -->
                <div class="absolute left-0 right-0 text-center select-none font-semibold text-slate-600" id="preview-date_text">
                    ให้ไว้ ณ วันที่ 26 เดือน มิถุนายน พ.ศ. 2569
                </div>

                <!-- 9. Signatures Block -->
                <div class="absolute left-0 right-0 px-16 flex justify-between items-end font-semibold" id="preview-signatures">
                    <!-- Left Signature -->
                    <div class="w-36 text-center shrink-0">
                        <div class="w-full border-t border-slate-300 pt-1 text-[10px] text-slate-500 uppercase tracking-wider" id="sig_l_val">
                            <?= htmlspecialchars($settings['sig_left_title']) ?>
                        </div>
                    </div>
                    
                    <!-- Center spacing for Gold Seal -->
                    <div class="w-20 h-20 select-none pointer-events-none"></div>

                    <!-- Right Signature -->
                    <div class="w-36 text-center shrink-0">
                        <div class="w-full border-t border-slate-300 pt-1 text-[10px] text-slate-500 uppercase tracking-wider" id="sig_r_val">
                            <?= htmlspecialchars($settings['sig_right_title']) ?>
                        </div>
                    </div>
                </div>

                <!-- 10. Gold Seal -->
                <div class="absolute left-1/2 -translate-x-1/2 rounded-full bg-gradient-to-r from-amber-200 via-amber-300 to-[#d4af37] border-2 border-dashed border-amber-600 flex flex-col items-center justify-center text-[10px] font-black text-[#8a6d1c] shadow-lg shadow-yellow-500/20 -rotate-12 select-none" id="preview-seal">
                    <span class="text-[5px] tracking-widest text-[#8a6d1c]/80">★ ★ ★</span>
                    <span class="my-0.5 text-[8px]">ชนะเลิศ</span>
                    <span class="text-[6px] font-heading font-black tracking-wider">ตราประทับ</span>
                    <div class="absolute bottom-[-10px] z-[-1] w-12 h-8 flex justify-between ribbon-tails">
                        <div class="w-4 h-8 bg-[#d4af37] opacity-85 rotate-[20deg] -translate-x-[3px] [clip-path:polygon(0_0,100%_0,100%_100%,50%_80%,0_100%)]"></div>
                        <div class="w-4 h-8 bg-[#d4af37] opacity-85 -rotate-[20deg] translate-x-[3px] [clip-path:polygon(0_0,100%_0,100%_100%,50%_80%,0_100%)]"></div>
                    </div>
                </div>
            </div>

            <!-- List of Medal-Winning Students -->
            <div class="bg-slate-900/60 backdrop-blur-xl border border-white/5 rounded-[24px] p-6 shadow-lg">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4 pb-3 border-b border-white/5">
                    <h3 class="text-base font-bold flex items-center gap-2 text-white">
                        <i class="fa-solid fa-users text-indigo-400"></i>
                        รายชื่อนักเรียนที่มีสิทธิ์รับเกียรติบัตร (<?= count($winners) ?> คน)
                    </h3>
                    <input type="text" id="athlete-search-input" placeholder="ค้นหาชื่อหรือคณะสี..." class="w-full sm:w-48 bg-white/3 border border-white/5 focus:border-indigo-500 focus:bg-white/5 rounded-xl py-1.5 px-3 text-white text-xs outline-none transition-all" oninput="filterAthletes(this.value)">
                </div>
                
                <div class="overflow-y-auto max-h-56 pr-1">
                    <?php if (empty($winners)): ?>
                        <div class="text-center py-8 text-slate-500 text-xs">ยังไม่มีบันทึกผลรางวัลเหรียญใดๆ ในระบบ</div>
                    <?php else: ?>
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="bg-white/2 border-b border-white/5 text-slate-400 font-bold">
                                    <th class="p-2.5">ชื่อนักเรียนนักกีฬา</th>
                                    <th class="p-2.5">ประเภทการแข่ง / ชนิดกีฬา</th>
                                    <th class="p-2.5">ระดับรางวัล</th>
                                    <th class="p-2.5 text-center">ตัวเลือก</th>
                                </tr>
                            </thead>
                            <tbody id="athletes-list-body">
                                <?php foreach ($winners as $row): 
                                    $awardDetails = CertificateModel::getAwardDetails($row['medal']);
                                    $houseNameTh = $presenter->getHouseNameTh($row['house_name']);
                                ?>
                                    <tr class="border-b border-white/[0.03] hover:bg-white/[0.01] transition-colors athlete-winner-row" data-search-name="<?= htmlspecialchars($row['Stu_name'] . ' ' . $row['Stu_sur'] . ' ' . $houseNameTh) ?>">
                                        <td class="p-2.5">
                                            <strong class="text-white block"><?= htmlspecialchars($row['Stu_name'] . ' ' . $row['Stu_sur']) ?></strong>
                                            <span class="text-[10px] text-slate-400">ม.<?= $row['grade_level'] ?>/<?= $row['room_number'] ?> | คณะ<?= htmlspecialchars($houseNameTh) ?></span>
                                        </td>
                                        <td class="p-2.5 text-slate-300">
                                            <span><?= htmlspecialchars($row['sport_name']) ?></span>
                                            <span class="block text-[10px] text-slate-500"><?= htmlspecialchars($row['category']) ?></span>
                                        </td>
                                        <td class="p-2.5">
                                            <span class="inline-flex items-center gap-1 font-bold text-amber-400">
                                                <?= $awardDetails['emoji'] ?> <?= $awardDetails['name'] ?>
                                            </span>
                                        </td>
                                        <td class="p-2.5 text-center">
                                            <button type="button" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-3 py-1 rounded-lg text-[10px] transition-colors cursor-pointer" onclick="selectAthleteForPreview('<?= htmlspecialchars($row['Stu_name'] . ' ' . $row['Stu_sur']) ?>', '<?= htmlspecialchars($houseNameTh) ?>', '<?= htmlspecialchars($row['color_code']) ?>', '<?= htmlspecialchars($row['sport_name']) ?>', '<?= htmlspecialchars($row['category']) ?>', '<?= htmlspecialchars($row['medal']) ?>')">
                                                โหลดพรีวิว
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</div>

<?php require_once __DIR__ . '/components/footer.php'; ?>

<!-- Initial dynamic layout configuration data -->
<script>
    const layoutSettings = <?= $settings['layout_json'] ?>;
    
    // Seed original default values in case user resets
    const defaultLayoutSettings = {
        "header_text": { "top": 12, "fontSize": 18, "color": "#8a6d1c", "fontWeight": "black" },
        "main_title":  { "top": 20, "fontSize": 36, "color": "#1e293b", "fontWeight" : "black" },
        "prefix_text": { "top": 36, "fontSize": 14, "color" : "#64748b", "fontWeight" : "semibold" },
        "student_name":{ "top": 42, "fontSize": 44, "color" : "#0f172a", "fontWeight" : "black" },
        "body_line1":  { "top": 54, "fontSize": 16, "color" : "#475569", "fontWeight" : "normal" },
        "medal_badge": { "top": 62, "fontSize": 18, "color" : "#8a6d1c", "fontWeight" : "black" },
        "body_line2":  { "top": 72, "fontSize": 16, "color" : "#475569", "fontWeight" : "normal" },
        "date_text":   { "top": 80, "fontSize": 12, "color" : "#64748b", "fontWeight" : "semibold" },
        "signatures":  { "top": 86, "fontSize": 12, "color" : "#64748b", "fontWeight" : "semibold" },
        "seal":        { "top": 78, "scale": 1.0 }
    };

    // State mapping for award preview
    const awardPresets = {
        'Gold': { name: 'ชนะเลิศ', emoji: '🥇', color: '#8a6d1c', bg: 'rgba(245, 158, 11, 0.1)', border: 'rgba(245, 158, 11, 0.2)' },
        'Silver': { name: 'รองชนะเลิศอันดับที่ 1', emoji: '🥈', color: '#475569', bg: 'rgba(100, 116, 139, 0.1)', border: 'rgba(100, 116, 139, 0.2)' },
        'Bronze': { name: 'รองชนะเลิศอันดับที่ 2', emoji: '🥉', color: '#7c2d12', bg: 'rgba(251, 146, 60, 0.1)', border: 'rgba(251, 146, 60, 0.2)' },
        'RunnerUp3': { name: 'รองชนะเลิศอันดับที่ 3', emoji: '🏅', color: '#334155', bg: 'rgba(71, 85, 105, 0.1)', border: 'rgba(71, 85, 105, 0.2)' },
        'Participant': { name: 'เข้าร่วมการแข่งขัน', emoji: '🌟', color: '#0369a1', bg: 'rgba(14, 165, 233, 0.1)', border: 'rgba(14, 165, 233, 0.2)' }
    };

    let activePreviewMedal = 'Gold';
    let mockSportName = 'ฟุตซอล';
    let mockCategory = 'ชาย ม.ปลาย';
    let customBodyPattern3 = document.getElementById('input-body-pat3').value;

    // Load initial layout sizes and coordinates into preview container
    window.addEventListener('DOMContentLoaded', () => {
        applyAllLayouts(layoutSettings);
        applyTheme('<?= $settings['bg_style'] ?>', '<?= $settings['border_color'] ?>');
    });

    function applyAllLayouts(layout) {
        for (const key in layout) {
            if (layout.hasOwnProperty(key)) {
                applySingleLayout(key, layout[key]);
            }
        }
    }

    function applySingleLayout(key, item) {
        const el = document.getElementById('preview-' + key);
        if (!el) return;

        if (key === 'seal') {
            el.style.top = item.top + '%';
            el.style.width = (64 * item.scale) + 'px';
            el.style.height = (64 * item.scale) + 'px';
            el.style.transform = `translateX(-50%) scale(${item.scale})`;
            return;
        }

        if (item.top !== undefined) el.style.top = item.top + '%';
        if (item.fontSize !== undefined) el.style.fontSize = Math.round(item.fontSize * 0.72) + 'px'; // Scale preview font size to fit canvas box
        if (item.color !== undefined) el.style.color = item.color;
        if (item.fontWeight !== undefined) {
            el.style.fontWeight = item.fontWeight === 'black' ? '900' : (item.fontWeight === 'bold' ? '700' : '500');
        }
    }

    function changeElementTop(key, value) {
        document.getElementById('val-top-' + key).innerText = value + '%';
        const el = document.getElementById('preview-' + key);
        if (el) el.style.top = value + '%';
    }

    function changeElementSize(key, value) {
        document.getElementById('val-size-' + key).innerText = value + 'px';
        const el = document.getElementById('preview-' + key);
        if (el) el.style.fontSize = Math.round(value * 0.72) + 'px';
    }

    function changeElementColor(key, value) {
        const el = document.getElementById('preview-' + key);
        if (el) el.style.color = value;
    }

    function changeSealScale(value) {
        document.getElementById('val-scale-seal').innerText = value + 'x';
        const el = document.getElementById('preview-seal');
        if (el) {
            el.style.width = (64 * value) + 'px';
            el.style.height = (64 * value) + 'px';
            el.style.transform = `translateX(-50%) scale(${value})`;
        }
    }

    function updatePreviewText(elementId, value) {
        const span = document.getElementById(elementId);
        if (span) span.innerText = value;
    }

    function updatePreviewPattern3(value) {
        customBodyPattern3 = value;
        renderPattern3();
    }

    function renderPattern3() {
        const target = document.getElementById('preview-body-pat3-render');
        if (!target) return;

        let output = customBodyPattern3
            .replace('{sport_name}', mockSportName)
            .replace('{category}', mockCategory);
        target.innerText = output;
    }

    function changeBgStyle(style) {
        const box = document.getElementById('preview-canvas-box');
        const innerBorder = document.getElementById('preview-inner-border');
        const corners = document.querySelectorAll('.orn-corner');
        const watermark = document.getElementById('preview-watermark');
        const colorInput = document.getElementById('border_color_input');
        
        box.className = "cert-preview-box";
        box.style.borderWidth = '20px';
        box.style.borderStyle = 'double';
        
        let newColor = '#d4af37';
        switch (style) {
            case 'emerald-premium':
                box.classList.add('bg-[#fcfdfa]', 'text-slate-900');
                newColor = '#059669'; // emerald-600
                break;
            case 'royal-purple':
                box.classList.add('bg-[#faf9fc]', 'text-slate-900');
                newColor = '#7e22ce'; // purple-700
                break;
            case 'minimal-blue':
                box.classList.add('bg-[#fafcfe]', 'text-slate-900');
                box.style.borderWidth = '16px';
                box.style.borderStyle = 'solid';
                newColor = '#0284c7'; // sky-600
                break;
            case 'classic-gold':
            default:
                box.classList.add('bg-white', 'text-slate-900');
                newColor = '#d4af37'; // gold
                break;
        }

        colorInput.value = newColor;
        applyTheme(style, newColor);
    }

    function changeBorderColor(color) {
        applyTheme(document.querySelector('input[name="bg_style"]:checked').value, color);
    }

    function applyTheme(style, color) {
        const box = document.getElementById('preview-canvas-box');
        const innerBorder = document.getElementById('preview-inner-border');
        const corners = document.querySelectorAll('.orn-corner');
        const watermark = document.getElementById('preview-watermark');

        box.style.borderColor = color;
        if (innerBorder) innerBorder.style.borderColor = color + '55';
        corners.forEach(corner => corner.style.borderColor = color);
        if (watermark) watermark.style.color = color;
    }

    function previewAwardChange(awardKey) {
        activePreviewMedal = awardKey;
        const preset = awardPresets[awardKey];
        
        document.getElementById('preview-medal-emoji').innerText = preset.emoji;
        document.getElementById('preview-medal-name').innerText = preset.name;
        
        const pill = document.getElementById('preview-badge-pill');
        pill.style.color = preset.color;
        pill.style.backgroundColor = preset.bg;
        pill.style.borderColor = preset.border;
    }

    function selectAthleteForPreview(name, houseName, colorCode, sportName, category, medal) {
        document.getElementById('preview-student_name_val').innerText = name;
        
        const houseSpan = document.getElementById('preview-house-span');
        houseSpan.innerText = 'คณะ' + houseName;
        houseSpan.style.color = colorCode;
        
        mockSportName = sportName;
        mockCategory = category;
        renderPattern3();

        // Sync and change the award level preview selection
        document.getElementById('preview-award-select').value = medal;
        previewAwardChange(medal);

        // Smooth scroll preview canvas into view for mobile users
        document.getElementById('preview-canvas-box').scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    function filterAthletes(search) {
        search = search.toLowerCase().trim();
        const rows = document.querySelectorAll('.athlete-winner-row');
        rows.forEach(row => {
            const content = row.getAttribute('data-search-name').toLowerCase();
            if (content.includes(search)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    function resetLayoutDefaults() {
        applyAllLayouts(defaultLayoutSettings);
        
        // Sync sliders
        for (const key in defaultLayoutSettings) {
            if (defaultLayoutSettings.hasOwnProperty(key)) {
                if (key === 'seal') {
                    document.querySelector('input[name="seal_top"]').value = defaultLayoutSettings.seal.top;
                    document.getElementById('val-top-seal').innerText = defaultLayoutSettings.seal.top + '%';
                    document.querySelector('input[name="seal_scale"]').value = defaultLayoutSettings.seal.scale;
                    document.getElementById('val-scale-seal').innerText = defaultLayoutSettings.seal.scale + 'x';
                    continue;
                }
                const topInput = document.querySelector(`input[name="pos[${key}][top]"]`);
                if (topInput) {
                    topInput.value = defaultLayoutSettings[key].top;
                    document.getElementById('val-top-' + key).innerText = defaultLayoutSettings[key].top + '%';
                }
                const sizeInput = document.querySelector(`input[name="pos[${key}][fontSize]"]`);
                if (sizeInput) {
                    sizeInput.value = defaultLayoutSettings[key].fontSize;
                    document.getElementById('val-size-' + key).innerText = defaultLayoutSettings[key].fontSize + 'px';
                }
                const colorInput = document.querySelector(`input[name="pos[${key}][color]"]`);
                if (colorInput) {
                    colorInput.value = defaultLayoutSettings[key].color;
                }
            }
        }
    }
</script>

</body>
</html>
