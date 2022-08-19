<?php $this->layout('Components::template', ['page_title' => 'OpenSIPS Logs']) ?>

<?php $this->insert('Components::Logs/Menu'); ?>

<div class="container-md py-4">
    <div class="row">
        <div class="col-md-12">
            <pre class="bg-dark text-light p-3"><code><?php echo $this->e($content); ?></code></pre>
        </div>
    </div>
</div>