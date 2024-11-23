<?php if (count($errors) > 0): ?>
    <div class="message error-message">
        <?php foreach ($errors as $error): ?>
            <li><?php echo $error; ?></li>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['message'])): ?>
    <div class="message <?php echo $_SESSION['type']; ?>">
        <li>
            <?php echo $_SESSION['message']; ?>
        </li>

        <?php
        unset($_SESSION['message']);
        unset($_SESSION['type']);
        ?>
    </div>
<?php endif; ?>