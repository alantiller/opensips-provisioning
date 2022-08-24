<?php $this->layout('Components::template', ['page_title' => 'Subscribers']) ?>

<div class="container-md py-4">
    <div class="row">
        <!-- List all Subscribers -->
        <div class="col-md-9">
            <span data-message="subscribers"></span>
            <table class="table align-middle" id="list_subscribers">
                <thead>
                    <tr>
                        <th>UUID</th>
                        <th>Username</th>
                        <th>Domain</th>
                        <th>Attribute</th>
                        <th>Type</th>
                        <th>Server</th>
                        <th>View</th>
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

<!-- View a Subscriber -->
<div class="modal fade" id="view_subscriber">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Subscriber : <span data-label="subscriber_username"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3 form-group" style="display: none;">
                            <label for="user_name" class="mb-1">ID</label>
                            <input type="text" class="form-control" value="Loading . . ." data-input="subscriber_id" readonly/>
                        </div>
                        <div class="mb-3 form-group">
                            <label for="user_name" class="mb-1">Username</label>
                            <input type="text" class="form-control" value="Loading . . ." data-input="subscriber_username" readonly/>
                        </div>
                        <div class="mb-3 form-group">
                            <label for="user_name" class="mb-1">Attribute</label>
                            <input type="text" class="form-control" value="Loading . . ." data-input="subscriber_attribute" readonly/>
                        </div>
                        <div class="mb-3 form-group">
                            <label for="user_name" class="mb-1">Type</label>
                            <input type="text" class="form-control" value="Loading . . ." data-input="subscriber_type" readonly/>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3 form-group" style="display: none;">
                            <label for="user_name" class="mb-1">Last Modified</label>
                            <input type="text" class="form-control" value="Loading . . ." data-input="subscriber_last_modified" readonly/>
                        </div>
                        <div class="mb-3 form-group">
                            <label for="user_name" class="mb-1">UUID</label>
                            <input type="text" class="form-control" value="Loading . . ." data-input="subscriber_uuid" readonly/>
                        </div>
                        <div class="mb-3 form-group">
                            <label for="user_name" class="mb-1">Domain</label>
                            <input type="text" class="form-control" value="Loading . . ." data-input="subscriber_domain" readonly/>
                        </div>
                        <div class="mb-3 form-group">
                            <label for="user_name" class="mb-1">Server</label>
                            <input type="text" class="form-control" value="Loading . . ." data-input="subscriber_server" readonly/>
                        </div>
                    </div>
                </div>
                <h5 class="mt-2">Metadata</h5>
                <table class="table mb-0 mt-2">
                    <thead>
                        <tr>
                            <th scope="col">Label</th>
                            <th scope="col">Value</th>
                            <th scope="col">Created On</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider" data-table="subscriber_metadata">
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-action="subscriber_show_system_fields">Show System Fields</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>