<?php $active_page = (isset($router->match()['name']) ? $router->match()['name'] : ''); ?>


<div class="container-md pt-4">
    <div class="row">
        <div class="col-md-12">

            <div class="nav nav-pills">
                <?php if (\Auth\Permissions::check('osp_audit', $user['id']) != false) { ?>
                    <div class="nav-item me-2">
                        <a class="nav-link <?php if ($active_page === 'logs_audit') {echo 'active';} ?>" href="<?php echo $this->e($_ENV['PUBLIC_URL']) . $router->generate('logs_audit'); ?>">Audit</a>
                    </div>
                <?php } ?>
                <?php if (\Auth\Permissions::check('local_opensipslogs', $user['id']) != false) { ?>
                    <div class="nav-item me-2">
                        <a class="nav-link <?php if ($active_page === 'logs_opensips') {echo 'active';} ?>" href="<?php echo $this->e($_ENV['PUBLIC_URL']) . $router->generate('logs_opensips'); ?>">OpenSIPS</a>
                    </div>
                <?php } ?>
            </div>

        </div>
    </div>
</div>

