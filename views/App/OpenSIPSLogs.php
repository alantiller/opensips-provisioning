<?php $this->layout('Components::template', ['page_title' => 'OpenSIPS Logs']) ?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-12">
            <pre class="bg-dark text-light p-3"><code><?php echo $this->e($content); ?></code></pre>
        </div>
    </div>
</div>