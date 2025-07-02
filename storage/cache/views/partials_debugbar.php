<?php
$debug = $debug ?? [];
$time = $debug['time_ms'] ?? '?';
$memory = $debug['memory'] ?? '?';
$queries = is_array($debug['queries'] ?? null) ? $debug['queries'] : [];
$errors = is_array($debug['errors'] ?? null) ? $debug['errors'] : [];

$session = is_array($_SESSION ?? null) ? $_SESSION : [];
$get = is_array($_GET ?? null) ? $_GET : [];
$post = is_array($_POST ?? null) ? $_POST : [];
?>

<div style="position: fixed; bottom: 0; left: 0; right: 0; background: #111827; color: #f9fafb; font-family: monospace; font-size: 14px; padding: 8px 12px; z-index: 9999; border-top: 2px solid #facc15;">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <strong>Temps</strong> : <?= htmlspecialchars($time) ?> ms
            <strong>Mémoire</strong> : <?= htmlspecialchars($memory) ?>
            <strong>SQL</strong> : <?= htmlspecialchars(count($queries)) ?> requêtes
            <strong>Session</strong> : <?= htmlspecialchars(count($session)) ?>
            <strong>GET</strong> : <?= htmlspecialchars(count($get)) ?>
            <strong>POST</strong> : <?= htmlspecialchars(count($post)) ?>
        </div>
        <button onclick="document.getElementById('debug-details').classList.toggle('hidden')"
                style="background: transparent; color: #facc15; border: none; cursor: pointer;">
            🔍 Détails
        </button>
    </div>

    <div id="debug-details" class="hidden" style="margin-top: 10px;">
        <?php if (count($errors) > 0): ?>
        <div style="margin-bottom: 10px; color: #f87171;">
            <strong>❌ Erreurs PHP :</strong>
            <ul>
                <?php foreach ($errors as $error): ?>
                <li>
                    <?= htmlspecialchars($error['type']) ?>: <?= htmlspecialchars($error['message']) ?> in <?= htmlspecialchars($error['file']) ?>:<?= htmlspecialchars($error['line']) ?>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <?php if (count($queries) > 0): ?>
        <div style="margin-bottom: 10px; color: #d1d5db;">
            <strong>📜 Requêtes SQL :</strong>
            <ul>
                <?php foreach ($queries as $q): ?>
                <li style="margin-bottom: 4px;">
                    <code><?= htmlspecialchars($q['sql'] ?? $q) ?></code>
                    <?php if (isset($q['bindings']) and $q['bindings']): ?>
                    <br><span style="color:#9ca3af;">Bindings :</span> <?= htmlspecialchars(json_encode($q['bindings'])) ?>
                    <?php endif; ?>
                    <?php if (isset($q['time'])): ?>
                    <br><span style="color:#9ca3af;">Durée :</span> <?= htmlspecialchars($q['time']) ?> ms
                    <?php endif; ?>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
    const details = document.getElementById('debug-details');
    if (details && details.classList && typeof details.classList.toggle === 'function') {
        details.classList.add('hidden');
    }
</script>

<style>
    .hidden {
        display: none;
    }

    .debugbar ul {
        padding-left: 20px;
    }

    .debugbar code {
        background: #1f2937;
        padding: 2px 4px;
        border-radius: 4px;
    }
</style>
