<?php $this->layout('Components::template', ['page_title' => 'Dashboard']) ?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-4">
            <div class="text-center py-2 mb-4 card">
                <div class="card-body">
                    <div style="font-size: 2em; line-height: 1.1em; font-weight: 500;" data-output="total_subscribers"></div>
                    <div style="font-size: 0.9em; opacity: 0.5;">Total Subscribers</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="text-center py-2 mb-4 card">
                <div class="card-body">
                    <div style="font-size: 2em;line-height: 1.1em;font-weight: 500;" data-output="total_active_servers"></div>
                    <div style="font-size: 0.9em; opacity: 0.5;">Active Servers</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="text-center py-2 mb-4 card">
                <div class="card-body">
                    <div style="font-size: 2em;line-height: 1.1em;font-weight: 500;" data-output="total_active_provisions"></div>
                    <div style="font-size: 0.9em; opacity: 0.5;">Active Provisions</div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <div class="mb-4 card">
                <div class="list-group list-group-flush" data-output="subscribers_per_server">
                    <div class="py-3 list-group-item">
                        <h5>Subscribers per Server</h5>
                        <div style="opacity: 0.6; font-size: 0.9em;">Here you can view a breakdown of each server and how many subscribers are being routed to it.</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="mb-4 card">
                <div class="list-group list-group-flush" data-output="audit_errors">
                    <div class="py-3 list-group-item">
                        <h5>API Failures</h5>
                        <div style="opacity: 0.6; font-size: 0.9em;">This shows API calls that have failed, this allows you to catch potential problems before they get too big.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>