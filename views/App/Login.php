<?php $this->layout('Components::template', ['page_title' => 'Login']) ?>

<div class="container-md">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="text-center py-5">
                <h3 class="mb-5">Login into your account</h3>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="username" data-input="username" placeholder="Username">
                    <label for="username">Username</label>
                </div>
                <div class="form-floating mb-4">
                    <input type="password" class="form-control" id="password" data-input="password" placeholder="Password">
                    <label for="password">Password</label>
                </div>
                <button class="w-100 btn btn-lg btn-primary" type="submit" data-action="login">Sign in</button>
                <div class="mt-4" data-message="login"></div>
            </div>
        </div>
    </div>
</div>