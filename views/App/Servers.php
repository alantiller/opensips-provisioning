<?php $this->layout('Components::template', ['page_title' => 'Servers']) ?>

<div class="container-md py-3">
    <div class="row">
        <div class="col-md-8">
            <span data-message="servers"></span>
            <table class="table align-middle" id="list_servers">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Enabled</th>
                        <th>Description</th>
                        <th>IP</th>
                        <th>Domain</th>
                        <th>Attribute</th>
                        <th>Type</th>
                        <th>Delete</th>
                    </tr>
                </thead>
            </table>
        </div>

        <div class="col-md-4">
            <div class="mb-4 card">
                <div class="list-group list-group-flush">
                    <div class="py-3 list-group-item">
                        <h5>Create server</h5>
                        <div style="opacity: 0.6; font-size: 0.9em;">Create and Delete provisioning servers, these are different to gateways. These are used solely for provisioning.</div>
                    </div>
                    <div class="py-3 list-group-item">
                        <div class="mb-3 form-group">
                            <label for="user_name" class="mb-1">Description</label>
                            <input type="text" class="form-control" placeholder="Voice Server 1" data-input="server_description"/>
                        </div>

                        <div class="mb-3 form-group">
                            <label for="user_username" class="mb-1">IP Address</label>
                            <input type="text" class="form-control" placeholder="192.168.0.1" data-input="server_address"/>
                        </div>

                        <div class="mb-3 form-group">
                            <label for="user_password" class="mb-1">Default Domain</label>
                            <input type="text" class="form-control" placeholder="opensips.domain.com" data-input="server_domain"/>
                        </div>

                        <div class="mb-3 form-group">
                            <label for="user_password" class="mb-1">Default Attribute</label>
                            <input type="text" class="form-control" placeholder="fs" data-input="server_attribute"/>
                        </div>

                        <div class="mb-3 form-group">
                            <label for="user_password" class="mb-1">Default Type</label>
                            <input type="text" class="form-control" placeholder="2" data-input="server_type"/>
                        </div>

                        <div class="mb-3 form-group">
                            <label for="permission_read" class="mb-1">Enabled</label>
                            <select class="form-select" aria-label="Group" data-input="server_enabled">
                                <option disabled selected>- Select -</option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary mb-3" data-action="create_server">Create</button>

                        <span data-message="create_servers"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>