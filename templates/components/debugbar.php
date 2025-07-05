<style>
    #debugbar-container {
        transition: height 0.3s ease;
        height: 40px;
        overflow: hidden;
    }

    #debugbar-container.expanded {
        height: 45vh;
    }

    .debugbar-header {
        background-color: #64748B;
        color: white;
        font-family: monospace;
        font-size: 0.875rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 1rem;
        height: 40px;
        cursor: pointer;
    }

    .debugbar-content {
        background-color: #f3f4f6;
        color: #111827;
        padding: 1rem;
        font-size: 0.75rem;
        overflow-y: auto;
        height: calc(45vh - 40px);
    }

    .debugbar-line {
        display: inline-flex;
        align-items: center;
        gap: 0.35em;
    }

    .lucide {
        width: 1em;
        height: 1em;
        vertical-align: middle;
        stroke-width: 1.5;
        display: inline-block;
        position: relative;
        top: -0.05em;
    }

    .tab-button {
        padding: 0.25rem 0.75rem;
        font-size: 0.75rem;
        border-radius: 0.375rem;
        background-color: #e5e7eb;
        color: #1f2937;
        margin-right: 0.5rem;
        cursor: pointer;
    }

    .tab-button.active {
        background-color: #2563eb;
        color: white;
    }

    .debug-tab {
        display: none;
    }

    .debug-tab.active {
        display: block;
    }

    pre {
        background: #e5e7eb;
        padding: 0.5rem;
        border-radius: 0.375rem;
        overflow-x: auto;
    }
</style>

<div id="debugbar-container" class="fixed bottom-0 left-0 right-0 z-50 shadow-lg border-t border-gray-300">
    <div class="debugbar-header" onclick="toggleDebugBar()">
        <div class="flex items-center gap-4 flex-wrap">
            <span class="debugbar-line"><i data-lucide="clock" class="lucide"></i>
                <?= isset($time['duration_ms']) ? (($time['duration_ms'] >= 1000) ? number_format($time['duration_ms'] / 1000, 2) . ' s' : number_format($time['duration_ms'], 0) . ' ms') : 'N/A' ?>
            </span>

            <span class="debugbar-line"><i data-lucide="file-text" class="lucide"></i><?= $included_files['count'] ?? 0 ?> fichiers</span>

            <span class="debugbar-line"><i data-lucide="settings" class="lucide"></i>PHP <?= $phpinfo['version'] ?? 'N/A' ?> (<?= $phpinfo['sapi'] ?? '' ?>)</span>

            <span class="debugbar-line"><i data-lucide="database" class="lucide"></i><?= $database['query_count'] ?? 0 ?> requêtes (<?= number_format($database['total_time'] ?? 0, 4) ?>s)</span>

            <span class="debugbar-line">
    <i data-lucide="memory-stick" class="lucide"></i>
    <?php
    if (isset($memory['used'])) {
        $usedBytes = $memory['used'];
        echo ($usedBytes < 1024 * 1024)
            ? number_format($usedBytes) . ' octets'
            : number_format($usedBytes / (1024 * 1024), 2) . ' Mo';
    } else {
        echo 'Mémoire : N/A';
    }
    ?>
</span>
        </div>

        <button class="ml-4 p-1 rounded hover:bg-gray-700" id="debugbar-toggle-icon-wrapper">
            <i id="debugbar-toggle-icon" data-lucide="chevron-up" class="lucide"></i>
        </button>
    </div>

    <div class="debugbar-content">
        <div class="mb-4">
            <?php
            $tabs = [
                'controller' => 'shield-check',
                'request'    => 'form-input',
                'sql'        => 'database',
                'session'    => 'lock',
                'errors'     => 'alert-triangle',
                'warnings'   => 'alert-circle',
                'http'       => 'server',
            ];
            ?>

            <?php foreach ($tabs as $tab => $icon): ?>
                <button class="tab-button" onclick="showTab('<?= $tab ?>', this)">
                    <i data-lucide="<?= $icon ?>" class="lucide"></i> <?= ucfirst($tab) ?>
                </button>
            <?php endforeach; ?>
        </div>

        <div id="tab-controller" class="debug-tab">
            <h4><i data-lucide="shield-check" class="lucide"></i> Contrôleur</h4>
            <p><?= $controller['controller'] ?? 'N/A' ?></p>
        </div>

        <div id="tab-request" class="debug-tab">
            <h4><i data-lucide="form-input" class="lucide"></i> Request</h4>
            <div><strong>Méthode :</strong> <?= $request['method'] ?? 'N/A' ?></div>
            <div><strong>URI :</strong> <?= htmlspecialchars($request['uri'] ?? '') ?></div>
            <div><strong>Paramètres ($_REQUEST) :</strong>
                <pre><?= json_encode($request['params'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?></pre>
            </div>
            <div><strong>Cookies :</strong>
                <pre><?= json_encode($request['cookies'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?></pre>
            </div>
            <div><strong>Session :</strong>
                <pre><?= json_encode($request['session'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?></pre>
            </div>
            <div><strong>En-têtes HTTP :</strong>
                <pre><?= json_encode($request['headers'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?></pre>
            </div>
        </div>

        <div id="tab-sql" class="debug-tab">
            <h4><i data-lucide="database" class="lucide"></i> Requêtes SQL</h4>
            <?php if (!empty($database['queries'])): ?>
                <?php foreach ($database['queries'] as $query): ?>
                    <div class="mb-2">
                        <strong>⏱ <?= number_format($query['duration'], 4) ?>s</strong>
                        <pre><?= htmlspecialchars($query['query']) ?></pre>
                        <?php if (!empty($query['bindings'])): ?>
                            <pre>Bindings : <?= htmlspecialchars(json_encode($query['bindings'])) ?></pre>
                        <?php endif; ?>
                        <hr class="my-1 border-gray-300" />
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucune requête SQL exécutée.</p>
            <?php endif; ?>
        </div>

        <div id="tab-session" class="debug-tab">
            <h4><i data-lucide="lock" class="lucide"></i> Session</h4>
            <pre><?= json_encode($_SESSION ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?></pre>
        </div>

        <div id="tab-errors" class="debug-tab">
            <h4><i data-lucide="alert-triangle" class="lucide"></i> Erreurs</h4>
            <?php if (($errors['count'] ?? 0) > 0): ?>
                <?php foreach ($errors['list'] as $error): ?>
                    <div class="mb-2">
                        <strong>📍 <?= htmlspecialchars($error['file']) ?> : <?= $error['line'] ?></strong><br>
                        <div class="text-red-700 font-semibold"><?= htmlspecialchars($error['message']) ?></div>
                        <?php if (!empty($error['trace'])): ?>
                            <details class="mt-1">
                                <summary class="cursor-pointer text-gray-700">Afficher la stack trace</summary>
                                <pre class="bg-gray-100 p-2 rounded"><?= htmlspecialchars($error['trace']) ?></pre>
                            </details>
                        <?php endif; ?>
                        <hr>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucune erreur détectée.</p>
            <?php endif; ?>
        </div>

        <div id="tab-warnings" class="debug-tab">
            <h4><i data-lucide="alert-circle" class="lucide"></i> Warnings</h4>
            <?php if (!empty($warnings)): ?>
                <?php foreach ($warnings as $warn): ?>
                    <div class="mb-2 text-yellow-600">
                        ⚠️ <?= $warn['message'] ?> in <code><?= $warn['file'] ?></code> on line <?= $warn['line'] ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucun avertissement détecté.</p>
            <?php endif; ?>
        </div>

        <div id="tab-http" class="debug-tab">
            <h4><i data-lucide="server" class="lucide"></i> HTTP</h4>
            <pre><?= json_encode([
                    'code' => $_SERVER['REDIRECT_STATUS'] ?? http_response_code(),
                    'method' => $_SERVER['REQUEST_METHOD'] ?? 'N/A',
                    'uri' => $_SERVER['REQUEST_URI'] ?? '',
                    'protocol' => $_SERVER['SERVER_PROTOCOL'] ?? '',
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?></pre>
        </div>
    </div>
</div>

<script>
    function toggleDebugBar() {
        const container = document.getElementById('debugbar-container');
        const icon = document.getElementById('debugbar-toggle-icon');
        container.classList.toggle('expanded');
        const expanded = container.classList.contains('expanded');
        icon.setAttribute('data-lucide', expanded ? 'chevron-down' : 'chevron-up');
        if (window.lucide) lucide.createIcons();
    }

    // Fonction pour afficher un onglet spécifique
    function showTab(tabId, btn) {
        // Désactiver tous les onglets
        document.querySelectorAll('.debug-tab').forEach(tab => tab.classList.remove('active'));

        // Activer l'onglet ciblé (sécurité : vérifier s'il existe)
        const targetTab = document.getElementById(`tab-${tabId}`);
        if (targetTab) {
            targetTab.classList.add('active');
        } else {
            console.warn(`Aucun onglet trouvé avec l'ID : tab-${tabId}`);
        }

        // Désactiver tous les boutons
        document.querySelectorAll('.tab-button').forEach(b => b.classList.remove('active'));

        // Activer le bouton actuel (sécurité : vérifier si btn est valide)
        if (btn && btn.classList) {
            btn.classList.add('active');
        } else {
            console.warn('Impossible d\'ajouter la classe active au bouton.');
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        if (window.lucide) lucide.createIcons();
        // Affiche le premier onglet par défaut
        const firstTab = document.querySelector('.tab-button');
        if (firstTab) showTab(firstTab.textContent.toLowerCase(), firstTab);
    });
</script>
