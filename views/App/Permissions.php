<?php $this->layout('Components::template', ['page_title' => 'Permissions']) ?>

<div class="container-md py-4">
    <div class="row">
        <div class="col-md-8">
            <span data-message="permissions"></span>
            <table class="table align-middle" id="list_permissions">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Group</th>
                        <th>Entity</th>
                        <th>Read</th>
                        <th>Update</th>
                        <th>Create</th>
                        <th>Delete</th>
                        <th>Delete</th>
                    </tr>
                </thead>
            </table>
        </div>

        <div class="col-md-4">
            <div class="mb-4 card">
                <div class="list-group list-group-flush">
                    <div class="py-3 list-group-item">
                        <h5>Create new permission</h5>
                        <div style="opacity: 0.6; font-size: 0.9em;">Give the permission a group name, select an entity and select what that group can do to it.</div>
                    </div>
                    <div class="py-3 list-group-item">
                        <div class="mb-3 form-group">
                            <label for="user_name" class="mb-1">Group</label>
                            <input type="text" class="form-control custom-select" placeholder="Name" list="permission_groups" data-input="permission_group"/>
                            <datalist id="permission_groups"></datalist>
                        </div>

                        <div class="mb-3 form-group">
                            <label for="group_select" class="mb-1">Entity</label>
                            <select class="form-select" aria-label="Group" data-input="permission_entity">
                                <option disabled selected>- Select -</option>
                                <option value="usr_preferences">Subscribers (usr_preferences)</option>
                                <option value="osp_servers">Servers (osp_servers)</option>
                                <option value="osp_provisions">Provisions (osp_provisions)</option>
                                <option value="osp_users">Users (osp_users)</option>
                                <option value="osp_permissions">Permissions (osp_permissions)</option>
                                <option value="osp_tokens">API Tokens (osp_tokens)</option>
                                <option value="osp_audit">Audit Log (osp_audit)</option>
                                <option value="local_opensipslogs">OpenSIPS Log (local_opensipslogs)</option>
                            </select>
                        </div>

                        <div class="mb-3 form-group">
                            <label for="permission_read" class="mb-1">Read</label>
                            <select class="form-select" aria-label="Group" data-input="permission_read">
                                <option disabled selected>- Select -</option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>

                        <div class="mb-3 form-group">
                            <label for="permission_read" class="mb-1">Update</label>
                            <select class="form-select" aria-label="Group" data-input="permission_update">
                                <option disabled selected>- Select -</option>
                                <option value="2">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>

                        <div class="mb-3 form-group">
                            <label for="permission_create" class="mb-1">Create</label>
                            <select class="form-select" aria-label="Group" data-input="permission_create">
                                <option disabled selected>- Select -</option>
                                <option value="4">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>

                        <div class="mb-3 form-group">
                            <label for="permission_delete" class="mb-1">Delete</label>
                            <select class="form-select" aria-label="Group" data-input="permission_delete">
                                <option disabled selected>- Select -</option>
                                <option value="8">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary mb-3" data-action="create_permission">Create</button>

                        <span data-message="create_permission"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>