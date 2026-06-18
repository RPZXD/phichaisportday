<?php
/**
 * UtilController — Cross-cutting utilities for flash notifications and UI helpers.
 *
 * Flash lifecycle:
 *   1. Controllers call  UtilController::flash('success', 'Your message here.')
 *      (or 'error' / 'warning' / 'info')
 *   2. Views call        UtilController::renderFlashJS()  (once, near </body>)
 *      which pops the queued messages and emits a <script> block that fires
 *      a SweetAlert2 popup on page-load, styled to match the site theme.
 */
class UtilController {

    // ─── Session keys ──────────────────────────────────────────────────────────

    private const SESSION_KEY = 'swal_flash_queue';

    // ─── Public API ────────────────────────────────────────────────────────────

    /**
     * Queue a flash notification to be shown on the next page load via SweetAlert2.
     *
     * @param string $type    One of: 'success' | 'error' | 'warning' | 'info'
     * @param string $title   Main bold heading shown in the modal.
     * @param string $message Optional detail text shown below the title.
     */
    public static function flash(string $type, string $title, string $message = ''): void {
        self::ensureSession();
        if (!isset($_SESSION[self::SESSION_KEY])) {
            $_SESSION[self::SESSION_KEY] = [];
        }
        $_SESSION[self::SESSION_KEY][] = compact('type', 'title', 'message');
    }

    // ─── Convenience wrappers ──────────────────────────────────────────────────

    public static function flashSuccess(string $title, string $message = ''): void {
        self::flash('success', $title, $message);
    }

    public static function flashError(string $title, string $message = ''): void {
        self::flash('error', $title, $message);
    }

    public static function flashWarning(string $title, string $message = ''): void {
        self::flash('warning', $title, $message);
    }

    public static function flashInfo(string $title, string $message = ''): void {
        self::flash('info', $title, $message);
    }

    // ─── Rendering ─────────────────────────────────────────────────────────────

    /**
     * Emit the SweetAlert2 <script> block for all queued flash messages.
     * Call this ONCE per view, just before </body>.
     * Pops (clears) the queue after reading.
     */
    public static function renderFlashJS(): void {
        self::ensureSession();

        // Migrate legacy $_SESSION['flash_success'] / ['flash_error'] keys
        self::migrateOldFlash();

        $queue = $_SESSION[self::SESSION_KEY] ?? [];
        unset($_SESSION[self::SESSION_KEY]);

        if (empty($queue)) {
            return;
        }

        $json = json_encode(
            $queue,
            JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE
        );

        // Border color string kept as a PHP var to avoid heredoc/JS dollar-sign clash
        $borderColor = 'rgba(99,102,241,0.25)';

        echo <<<SCRIPT
<script>
(function () {
    // ── SweetAlert2 theme – matches the SportDay dark palette ─────────────────
    var iconColor = {
        success: '#22c55e',
        error:   '#f43f5e',
        warning: '#f59e0b',
        info:    '#6366f1',
    };

    var queue = {$json};

    function fireNext(index) {
        if (index >= queue.length) return;
        var item = queue[index];
        Swal.fire({
            icon:               item.type,
            title:              item.title,
            text:               item.message || undefined,
            background:         '#0d1022',
            color:              '#f1f5f9',
            iconColor:          iconColor[item.type] || iconColor.info,
            confirmButtonText:  'ตกลง',
            confirmButtonColor: '#4f46e5',
            customClass: {
                popup:         'swal-sportday-popup',
                title:         'swal-sportday-title',
                htmlContainer: 'swal-sportday-text',
                confirmButton: 'swal-sportday-btn',
            },
            showClass: { popup: 'swal-fade-in' },
            hideClass: { popup: 'swal-fade-out' },
        }).then(function () { fireNext(index + 1); });
    }

    // Inject styles once
    if (!document.getElementById('swal-sportday-style')) {
        var s = document.createElement('style');
        s.id = 'swal-sportday-style';
        s.textContent = [
            '.swal-sportday-popup{',
            '  border:1px solid {$borderColor};',
            '  border-radius:1rem!important;',
            '  box-shadow:0 0 60px rgba(99,102,241,.12),0 20px 40px rgba(0,0,0,.55)!important;',
            '  font-family:"Mali",cursive!important;',
            '}',
            '.swal-sportday-title{',
            '  font-weight:800!important;',
            '  font-size:1.1rem!important;',
            '  letter-spacing:-0.01em;',
            '}',
            '.swal-sportday-text{',
            '  font-size:.875rem!important;',
            '  color:#94a3b8!important;',
            '}',
            '.swal-sportday-btn{',
            '  font-family:"Mali",cursive!important;',
            '  font-weight:700!important;',
            '  border-radius:.75rem!important;',
            '  padding:.5rem 1.75rem!important;',
            '  font-size:.875rem!important;',
            '  box-shadow:0 4px 14px rgba(79,70,229,.35)!important;',
            '  transition:transform .15s,box-shadow .2s!important;',
            '}',
            '.swal-sportday-btn:hover{',
            '  background:#4338ca!important;',
            '  transform:translateY(-1px)!important;',
            '  box-shadow:0 6px 20px rgba(79,70,229,.5)!important;',
            '}',
            '.swal2-icon{border-width:2px!important;}',
            '@keyframes swalFadeIn{from{opacity:0;transform:translateY(-12px) scale(.97)}to{opacity:1;transform:none}}',
            '@keyframes swalFadeOut{to{opacity:0;transform:translateY(-12px) scale(.97)}}',
            '.swal-fade-in{animation:swalFadeIn .22s cubic-bezier(.21,1.02,.73,1) both}',
            '.swal-fade-out{animation:swalFadeOut .18s ease-in both}',
        ].join('');
        document.head.appendChild(s);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () { fireNext(0); });
    } else {
        fireNext(0);
    }
})();
</script>
SCRIPT;
    }

    // ─── Backward-compat bridge ────────────────────────────────────────────────

    /**
     * Migrate old-style $_SESSION['flash_success'] / ['flash_error'] keys
     * into the new SweetAlert2 queue. Called automatically inside renderFlashJS().
     */
    public static function migrateOldFlash(): void {
        self::ensureSession();
        if (!empty($_SESSION['flash_success'])) {
            self::flashSuccess($_SESSION['flash_success']);
            unset($_SESSION['flash_success']);
        }
        if (!empty($_SESSION['flash_error'])) {
            self::flashError($_SESSION['flash_error']);
            unset($_SESSION['flash_error']);
        }
    }

    // ─── Private helpers ───────────────────────────────────────────────────────

    private static function ensureSession(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
}
