<?php
declare(strict_types=1);

define('NEXORA_BOOTSTRAPPED', true);

require_once dirname(__DIR__) . '/includes/cookies.php';
require_once dirname(__DIR__) . '/includes/database.php';

$userId = (int) ($_SESSION['nexora_user_id'] ?? $_SESSION['user_id'] ?? 0);

if ($userId < 1) {
    header('Location: /login.php?redirect=' . rawurlencode('/users/dashboard.php'));
    exit;
}

$pageTitle = 'Nexora Command Center';
$pageDescription = 'Your private Nexora identity, community presence, reputation, activity, connections, and achievements.';
$pageRobots = 'noindex,nofollow';

require_once dirname(__DIR__) . '/includes/header.php';
?>

<link rel="stylesheet" href="/assets/css/dashboard.css">

<section class="nx-dashboard" id="nexora-dashboard" data-dashboard-endpoint="/users/api/dashboard.php">

    <header class="nx-command-hero">
        <div class="nx-hero-grid" aria-hidden="true"></div>
        <div class="nx-hero-orbit nx-orbit-one" aria-hidden="true"></div>
        <div class="nx-hero-orbit nx-orbit-two" aria-hidden="true"></div>

        <div class="nx-hero-content">
            <div class="nx-hero-copy">
                <div class="nx-eyebrow">
                    <span class="nx-pulse"></span>
                    PERSONAL COMMAND CENTER
                </div>
                <h1>Welcome back<span id="hero-name">, Operator</span>.</h1>
                <p>
                    Your private Nexora operating picture — identity, reputation,
                    community activity, connections, and achievements in one place.
                </p>
                <div class="nx-hero-actions">
                    <a class="nx-command-btn nx-command-primary" href="/index.php">
                        <i class="fa-solid fa-house"></i> Return to Nexora
                    </a>
                    <a class="nx-command-btn" href="/contact.php">
                        <i class="fa-solid fa-headset"></i> Operations
                    </a>
                </div>
            </div>

            <div class="nx-system-panel">
                <div class="nx-system-panel-top">
                    <span>SYSTEM STATUS</span>
                    <strong id="dashboard-live-text">CONNECTING</strong>
                </div>
                <div class="nx-system-ring" aria-hidden="true">
                    <span id="system-ring-value">--</span>
                </div>
                <div class="nx-system-meta">
                    <span><i class="fa-solid fa-lock"></i> PRIVATE SESSION</span>
                    <span><i class="fa-solid fa-database"></i> LIVE DATA</span>
                </div>
            </div>
        </div>
    </header>

    <div class="nx-loading" id="dashboard-loading">
        <div class="nx-loader"></div>
        <div>
            <strong>Establishing secure telemetry link</strong>
            <span>Reading your authenticated account state and community signals…</span>
        </div>
    </div>

    <div class="nx-error d-none" id="dashboard-error" role="alert">
        <i class="fa-solid fa-triangle-exclamation"></i>
        <div>
            <strong>Telemetry link interrupted</strong>
            <span id="dashboard-error-message">Your dashboard could not be synchronized.</span>
        </div>
        <button type="button" id="dashboard-retry">Retry synchronization</button>
    </div>

    <div class="nx-dashboard-content d-none" id="dashboard-content">

        <section class="nx-identity-card nx-panel">
            <div class="nx-identity-banner">
                <div class="nx-banner-noise" aria-hidden="true"></div>
                <div class="nx-banner-label">NEXORA IDENTITY / AUTHENTICATED</div>
                <div class="nx-banner-code">USER::<span id="identity-id">----</span></div>
            </div>

            <div class="nx-identity-body">
                <div class="nx-avatar-stack">
                    <img id="identity-avatar"
                         class="nx-avatar"
                         src="/assets/img/media/ChatGPT_Homepage.png"
                         alt="Nexora profile avatar">
                    <span id="identity-status-dot" class="nx-status-dot" title="Account status"></span>
                </div>

                <div class="nx-identity-copy">
                    <div class="nx-eyebrow">OPERATOR PROFILE</div>
                    <h2 id="identity-name">—</h2>
                    <div class="nx-handle" id="identity-handle">@—</div>

                    <div class="nx-identity-tags">
                        <span id="identity-account-type">ACCOUNT</span>
                        <span id="identity-account-status">ACTIVE</span>
                        <span id="identity-verification">VERIFICATION PENDING</span>
                    </div>

                    <div class="nx-identity-meta">
                        <span><i class="fa-solid fa-calendar"></i><b id="identity-joined">—</b></span>
                        <span><i class="fa-solid fa-clock"></i><b id="identity-last-login">Last login —</b></span>
                        <span><i class="fa-solid fa-wave-square"></i><b id="identity-last-activity">Activity —</b></span>
                    </div>
                </div>

                <div class="nx-reputation-core">
                    <span>REPUTATION</span>
                    <strong id="reputation-points">0</strong>
                    <small>community points</small>
                </div>
            </div>
        </section>

        <section class="nx-stat-grid" aria-label="Account telemetry">
            <article class="nx-stat nx-panel">
                <div class="nx-stat-icon"><i class="fa-solid fa-feather-pointed"></i></div>
                <div><span>POSTS</span><strong id="metric-posts-total">0</strong><small>authored</small></div>
                <em id="metric-posts-period">0 / 30d</em>
            </article>
            <article class="nx-stat nx-panel">
                <div class="nx-stat-icon"><i class="fa-solid fa-comment-dots"></i></div>
                <div><span>COMMENTS</span><strong id="metric-comments-total">0</strong><small>authored</small></div>
                <em id="metric-comments-period">0 / 30d</em>
            </article>
            <article class="nx-stat nx-panel">
                <div class="nx-stat-icon"><i class="fa-solid fa-users"></i></div>
                <div><span>CONNECTIONS</span><strong id="metric-connections">0</strong><small>established</small></div>
                <em id="metric-requests">0 pending</em>
            </article>
            <article class="nx-stat nx-panel">
                <div class="nx-stat-icon"><i class="fa-solid fa-award"></i></div>
                <div><span>ACHIEVEMENTS</span><strong id="metric-badges">0</strong><small>earned</small></div>
                <em id="metric-badges-locked">0 locked</em>
            </article>
        </section>

        <section class="nx-section">
            <div class="nx-section-heading">
                <div>
                    <span class="nx-eyebrow">INFLUENCE ENGINE</span>
                    <h2>Your presence, quantified.</h2>
                </div>
                <div class="nx-influence-score">
                    <span>INFLUENCE INDEX</span>
                    <strong id="influence-score">0</strong>
                    <small>/ 1000</small>
                </div>
            </div>

            <div class="nx-influence-layout">
                <article class="nx-panel nx-influence-card">
                    <div class="nx-influence-header">
                        <div>
                            <span>CURRENT TIER</span>
                            <h3 id="influence-tier">SIGNAL DETECTED</h3>
                        </div>
                        <i class="fa-solid fa-chart-line"></i>
                    </div>

                    <div class="nx-meter-list">
                        <div class="nx-meter-row">
                            <div><span>Publishing</span><b id="score-posting">0%</b></div>
                            <div class="nx-meter"><i id="bar-posting"></i></div>
                        </div>
                        <div class="nx-meter-row">
                            <div><span>Conversation</span><b id="score-discussion">0%</b></div>
                            <div class="nx-meter"><i id="bar-discussion"></i></div>
                        </div>
                        <div class="nx-meter-row">
                            <div><span>Engagement</span><b id="score-engagement">0%</b></div>
                            <div class="nx-meter"><i id="bar-engagement"></i></div>
                        </div>
                        <div class="nx-meter-row">
                            <div><span>Networking</span><b id="score-networking">0%</b></div>
                            <div class="nx-meter"><i id="bar-networking"></i></div>
                        </div>
                    </div>

                    <div class="nx-influence-note">
                        <i class="fa-solid fa-circle-info"></i>
                        <span>Influence is derived from sustained activity and community participation, not a single event.</span>
                    </div>
                </article>

                <article class="nx-panel nx-activity-card">
                    <div class="nx-card-heading">
                        <div>
                            <span class="nx-eyebrow">ACTIVITY PULSE</span>
                            <h3 id="range-title">LAST 30 DAYS</h3>
                        </div>
                        <span id="telemetry-updated">LIVE</span>
                    </div>

                    <div class="nx-range-tabs">
                        <button class="nx-range-tab active" data-range="30">30D</button>
                        <button class="nx-range-tab" data-range="60">60D</button>
                        <button class="nx-range-tab" data-range="180">180D</button>
                        <button class="nx-range-tab" data-range="365">1Y</button>
                    </div>

                    <div class="nx-range-total">
                        <strong id="range-total">0</strong>
                        <span>posts + comments in selected window</span>
                    </div>

                    <div class="nx-range-breakdown">
                        <span><i></i> POSTS <b id="range-posts">0</b></span>
                        <span><i></i> COMMENTS <b id="range-comments">0</b></span>
                    </div>

                    <div id="activity-bars" class="nx-activity-bars" aria-label="Activity comparison"></div>
                </article>
            </div>
        </section>

        <section class="nx-section nx-command-grid">
            <article class="nx-panel nx-network-card">
                <div class="nx-card-heading">
                    <div><span class="nx-eyebrow">NETWORK SIGNAL</span><h3>Community graph</h3></div>
                    <i class="fa-solid fa-network-wired"></i>
                </div>

                <div class="nx-network-stats">
                    <div><strong id="network-friends">0</strong><span>Connections</span></div>
                    <div><strong id="network-incoming">0</strong><span>Incoming</span></div>
                    <div><strong id="network-outgoing">0</strong><span>Outgoing</span></div>
                </div>

                <div class="nx-network-status">
                    <span class="nx-status-dot"></span>
                    <div>
                        <b id="network-status-title">Network ready</b>
                        <p id="network-status-copy">Your community graph is ready to grow.</p>
                    </div>
                </div>
            </article>

            <article class="nx-panel nx-badges-card">
                <div class="nx-card-heading">
                    <div><span class="nx-eyebrow">ACHIEVEMENT MATRIX</span><h3>Badge vault</h3></div>
                    <span id="badge-count">0 earned</span>
                </div>
                <div id="earned-badges" class="nx-badge-grid"></div>
                <div id="earned-empty" class="nx-empty d-none">
                    <i class="fa-regular fa-star"></i>
                    <b>Your first badge is waiting.</b>
                    <span>Participate, contribute, connect, and build your Nexora identity.</span>
                </div>
            </article>

            <article class="nx-panel nx-badges-card">
                <div class="nx-card-heading">
                    <div><span class="nx-eyebrow">NEXT OBJECTIVES</span><h3>Unlock matrix</h3></div>
                    <i class="fa-solid fa-lock"></i>
                </div>
                <div id="locked-badges" class="nx-locked-grid"></div>
                <div id="locked-empty" class="nx-empty d-none">
                    <i class="fa-solid fa-trophy"></i>
                    <b>Achievement matrix cleared.</b>
                    <span>You've earned every currently available badge.</span>
                </div>
            </article>

            <article class="nx-panel nx-quick-actions">
                <div class="nx-card-heading">
                    <div><span class="nx-eyebrow">COMMAND DECK</span><h3>Launch actions</h3></div>
                    <i class="fa-solid fa-terminal"></i>
                </div>
                <div class="nx-action-grid">
                    <a href="/index.php"><i class="fa-solid fa-plus"></i><span>New post</span></a>
                    <a href="/users/profile.php"><i class="fa-solid fa-user"></i><span>My profile</span></a>
                    <a href="/users/friends.php"><i class="fa-solid fa-users"></i><span>Friends</span></a>
                    <a href="/users/messages.php"><i class="fa-solid fa-message"></i><span>Messages</span></a>
                    <a href="/users/settings.php"><i class="fa-solid fa-sliders"></i><span>Settings</span></a>
                    <a href="/contact.php"><i class="fa-solid fa-headset"></i><span>Operations</span></a>
                </div>
            </article>
        </section>

        <section class="nx-section">
            <div class="nx-section-heading">
                <div>
                    <span class="nx-eyebrow">ACTIVITY STREAM</span>
                    <h2>Recent account signals</h2>
                </div>
            </div>
            <article class="nx-panel nx-stream">
                <div id="recent-activity"></div>
                <div id="activity-empty" class="nx-empty d-none">
                    <i class="fa-solid fa-satellite"></i>
                    <b>No recent activity recorded.</b>
                    <span>Your next contribution, connection, reputation event, or achievement will appear here.</span>
                </div>
            </article>
        </section>

        <section class="nx-section">
            <article class="nx-security-strip">
                <div class="nx-security-icon"><i class="fa-solid fa-shield-halved"></i></div>
                <div>
                    <span class="nx-eyebrow">ACCOUNT SECURITY TELEMETRY</span>
                    <h3 id="account-status-title">Account operating normally</h3>
                    <p id="account-status-copy">This command center is scoped to your authenticated Nexora account.</p>
                </div>
                <div class="nx-security-state">
                    <span id="security-state-dot"></span>
                    <b id="security-state-text">SECURE</b>
                </div>
            </article>
        </section>

    </div>
</section>

<script src="/assets/js/dashboard.js" defer></script>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
