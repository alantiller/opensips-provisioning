<?php $this->layout('Components::template', ['page_title' => 'Account']) ?>

<div class="container-md py-4">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5>Change Password</h5>
                    <div style="opacity: 0.6; font-size: 0.9em;">Change the password used to log into your account.</div>
                    <div class="mt-4">
                        <div class="mb-3 form-group">
                            <label for="password_current" class="mb-1">Current Password</label>
                            <input type="text" class="form-control" placeholder="" data-input="password_current"/>
                        </div>

                        <div class="mb-3 form-group">
                            <label for="password_current" class="mb-1">New Password</label>
                            <input type="text" class="form-control" placeholder="" data-input="password_new"/>
                        </div>

                        <div class="mb-3 form-group">
                            <label for="password_current" class="mb-1">Repeat New Password</label>
                            <input type="text" class="form-control" placeholder="" data-input="password_repeat"/>
                        </div>

                        <button type="submit" class="btn btn-primary mb-3" data-action="change_password">Change Passowrd</button>

                        <span data-message="change_password"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>