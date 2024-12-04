<header>
    <div class="header-container">
        <div class="header-buttons">
            <button
                onclick="window.location.href='<?php echo $user_type === 'govt_worker' ? BASE_URL . '/govt-homepage.php' : BASE_URL . '/user-homepage.php'; ?>'">
                Home
            </button>
            <?php if ($user_type !== 'govt_worker'): ?>
                <button onclick="window.location.href='<?php echo BASE_URL; ?>/pageview/reports/index.php'">Create
                    Report</button>

            <?php endif; ?>
            <button onclick="window.location.href='<?php echo BASE_URL; ?>/pageview/events/index.php'">View
                Events</button>
        </div>




        <!-- Profile Dropdown -->
        <div class="profile-dropdown">
            <button class="profile-button">
                <div>
                    Hi, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!
                </div>
            </button>
            <div class="dropdown-menu">
                <a href="<?php echo BASE_URL; ?>/view_profile.php">View Profile</a>
                <a href="<?php echo BASE_URL; ?>/settings.php">Settings</a>
                <a href="<?php echo BASE_URL; ?>/logout.php">Logout</a>
            </div>
        </div>
    </div>
</header>