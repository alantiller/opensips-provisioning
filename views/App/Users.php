<?php $this->layout('Components::template', ['page_title' => 'Users']) ?>

<div class="container-md py-4">
    <div class="row">
        <div class="col-md-8">
            <span data-message="users"></span>
            <table class="table align-middle" id="list_users">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Group</th>
                        <th>Created At</th>
                        <th>Delete</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div class="col-md-4">
            <div class="mb-4 card">
                <div class="list-group list-group-flush">
                    <div class="py-3 list-group-item">
                        <h5>Create user</h5>
                        <div style="opacity: 0.6; font-size: 0.9em;">You can give users their own login details unique to them, you'll also need a user for API tokens.</div>
                    </div>
                    <div class="py-3 list-group-item">
                        <div class="mb-3 form-group">
                            <label for="user_name" class="mb-1">Name</label>
                            <input type="text" class="form-control" placeholder="Name" data-input="user_name"/>
                        </div>

                        <div class="mb-3 form-group">
                            <label for="user_username" class="mb-1">Username</label>
                            <input type="text" class="form-control" placeholder="Username" data-input="user_username"/>
                        </div>

                        <div class="mb-3 form-group">
                            <label for="user_password" class="mb-1">Password</label>
                            <input type="password" class="form-control" placeholder="Password" data-input="user_password"/>
                        </div>

                        <div class="mb-3 form-group">
                            <label for="group_select" class="mb-1">Group</label>
                            <select class="form-select" aria-label="Group" data-input="user_group">
                                <option disabled selected>- Select -</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary mb-3" data-action="create_user">Create</button>

                        <span data-message="create_users"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>