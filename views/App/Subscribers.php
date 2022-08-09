<?php $this->layout('Components::template', ['page_title' => 'Subscribers']) ?>

<div class="container-md py-4">
    <div class="row">
        <!-- List all Subscribers -->
        <div class="col-md-9">
            <span data-message="subscribers"></span>
            <table class="table align-middle" id="list_subscribers">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>UUID</th>
                        <th>Username</th>
                        <th>Domain</th>
                        <th>Attribute</th>
                        <th>Type</th>
                        <th>Server</th>
                        <th>Delete</th>
                    </tr>
                </thead>
            </table>
        </div>

        <!-- Create a Subscriber -->
        <div class="col-md-3">
            <div class="mb-4 card">
                <div class="list-group list-group-flush">
                    <div class="py-3 list-group-item">
                        <h5>Create a Subscriber</h5>
                        <div style="opacity: 0.6; font-size: 0.9em;">Enter the CLI of the Subscriber you wish to create and we'll do the rest.</div>
                    </div>
                    <div class="py-3 list-group-item">
                        <div class="mb-3 form-group">
                            <label for="user_name" class="mb-1">CLI</label>
                            <input type="text" class="form-control" placeholder="01234567890" data-input="subscriber_cli"/>
                        </div>

                        <button type="submit" class="btn btn-primary mb-3" data-action="create_subscriber">Create</button>

                        <span data-message="create_subscriber"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>