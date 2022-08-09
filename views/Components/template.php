<!DOCTYPE html>
<html lang="en" class="h-100">
    <head>
        <?php $this->insert('Components::head', ['page_title' => $page_title]); ?>
    </head>
    <body class="d-flex flex-column h-100">
        <?php $this->insert('Components::header'); ?>

        <?php echo $this->section('content'); ?>

        <?php $this->insert('Components::footer'); ?>
    </body>
</html>