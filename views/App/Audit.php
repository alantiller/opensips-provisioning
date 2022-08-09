<?php $this->layout('Components::template', ['page_title' => 'Audit Logs']) ?>

<div class="container-md py-3">
    <div class="row">
        <div class="col-md-12">
            <table class="table align-middle" id="list_audit">
                <thead>
                    <tr>
                        <th></th>
                        <th>ID</th>
                        <th>Response Code</th>
                        <th>Method</th>
                        <th>Request URL</th>
                        <th>Created At</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>