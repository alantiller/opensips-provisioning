<div style="border-bottom: 1px solid #dee2e6;">
    <div class="container-md pt-3">
        <div class="row">
            <div class="col">
                <div class="d-flex align-items-center" style="margin-bottom: 16px;">
                    <div class="flex-grow-1">
                        <div class="d-block d-md-flex">
                            <a href="/">
                                <?php if (isset($_ENV['SITE_LOGO']) && $_ENV['SITE_LOGO'] != "") { ?>
                                    <img src="<?php echo $this->e($_ENV['SITE_LOGO']); ?>" alt="" height="45" class="d-inline-block me-4">
                                <?php } else { ?>
                                    <span class="d-none d-sm-inline-block lh-1"><?php echo $this->e($_ENV['SITE_NAME']); ?></span>
                                <?php } ?>
                            </a>
                        </div>
                    </div>
                    <?php if (\Auth\Auth::check()) { ?>
                        <div>
                            <div class="dropdown">
                                <button type="button" class="dropdown-toggle btn btn-light" data-bs-toggle="dropdown" aria-expanded="false">
                                    <?php echo $this->e($user['name']); ?>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="<?php echo $this->e($_ENV['PUBLIC_URL']) . $router->generate('account'); ?>" class="dropdown-item">Account</a>
                                    <a data-action="logout" class="text-danger dropdown-item">Logout</a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <?php if (\Auth\Auth::check()) {
                $active_page = (isset($router->match()['name']) ? $router->match()['name'] : ''); ?>
                    <div class="nav nav-tabs" style="margin-bottom: -1px;">
                        <div class="nav-item">
                            <a class="menu nav-link <?php if ($active_page === 'dashboard') {echo 'active';} ?>" href="<?php echo $this->e($_ENV['PUBLIC_URL']) . $router->generate('dashboard'); ?>" aria-current="page">Dashboard</a>
                        </div>
            
                        <?php if (\Auth\Permissions::check('usr_preferences', $user['id']) != false) { ?>
                            <div class="nav-item">
                                <a class="menu nav-link <?php if ($active_page === 'subscribers') {echo 'active';} ?>" href="<?php echo $this->e($_ENV['PUBLIC_URL']) . $router->generate('subscribers'); ?>">Subscribers</a>
                            </div>
                        <?php } ?>
                        
                        <?php if (\Auth\Permissions::check('osp_servers', $user['id']) != false) { ?>
                            <div class="nav-item">
                                <a class="menu nav-link <?php if ($active_page === 'servers') {echo 'active';} ?>" href="<?php echo $this->e($_ENV['PUBLIC_URL']) . $router->generate('servers'); ?>">Servers</a>
                            </div>
                        <?php } ?>

                        <?php if (\Auth\Permissions::check('osp_provisions', $user['id']) != false) { ?>
                            <div class="nav-item">
                                <a class="menu nav-link <?php if ($active_page === 'provisions') {echo 'active';} ?>" href="<?php echo $this->e($_ENV['PUBLIC_URL']) . $router->generate('provisions'); ?>">Provisions</a>
                            </div>
                        <?php } ?>

                        <?php if (\Auth\Permissions::check('osp_users', $user['id']) != false) { ?>
                            <div class="nav-item">
                                <a class="menu nav-link <?php if ($active_page === 'users') {echo 'active';} ?>" href="<?php echo $this->e($_ENV['PUBLIC_URL']) . $router->generate('users'); ?>">Users</a>
                            </div>
                        <?php } ?>

                        <?php if (\Auth\Permissions::check('osp_permissions', $user['id']) != false) { ?>
                            <div class="nav-item">
                                <a class="menu nav-link <?php if ($active_page === 'permissions') {echo 'active';} ?>" href="<?php echo $this->e($_ENV['PUBLIC_URL']) . $router->generate('permissions'); ?>">Permissions</a>
                            </div>
                        <?php } ?>

                        <?php if (\Auth\Permissions::check('osp_audit', $user['id']) != false) { ?>
                            <div class="nav-item">
                                <a class="menu nav-link <?php if ($active_page === 'audit') {echo 'active';} ?>" href="<?php echo $this->e($_ENV['PUBLIC_URL']) . $router->generate('audit'); ?>">Audit Logs</a>
                            </div>
                        <?php } ?>

                        <?php if (\Auth\Permissions::check('local_opensipslogs', $user['id']) != false) { ?>
                            <div class="nav-item">
                                <a class="menu nav-link <?php if ($active_page === 'opensips_logs') {echo 'active';} ?>" href="<?php echo $this->e($_ENV['PUBLIC_URL']) . $router->generate('opensips_logs'); ?>">OpenSIPS Logs</a>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
