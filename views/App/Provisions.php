<?php $this->layout('Components::template', ['page_title' => 'Provisions']) ?>

<div class="container-md py-4">
    <div class="row">
        <div class="col-md-8">

            <!-- On Create -->
            <div class="mb-4 card">
                <div class="list-group list-group-flush" data-list="provision_create">
                    <div class="py-3 list-group-item">
                        <h5>On Create</h5>
                        <div style="opacity: 0.6; font-size: 0.9em;">These actions will run when a subscriber is created on the server you selected.</div>
                    </div>
                </div>
            </div>

            <!-- On Update -->
            <div class="mb-4 card">
                <div class="list-group list-group-flush" data-list="provision_update">
                    <div class="py-3 list-group-item">
                        <h5>On Update <span class="badge bg-secondary align-top">Currently Unsupported</span></h5>
                        <div style="opacity: 0.6; font-size: 0.9em;">These actions will run when a subscriber have been updated for the server you selected.</div>
                    </div>
                </div>
            </div>

            <!-- On Delete -->
            <div class="mb-4 card">
                <div class="list-group list-group-flush" data-list="provision_delete">
                    <div class="py-3 list-group-item">
                        <h5>On Delete</h5>
                        <div style="opacity: 0.6; font-size: 0.9em;">These actions will run when a subscriber is deleted from the server you selected.</div>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-md-4">

            <div class="mb-4 card">
                <div class="list-group list-group-flush">
                    <div class="py-3 list-group-item">
                        <h5>Provisions <span class="badge bg-secondary align-top">BETA</span></h5>
                        <div style="opacity: 0.6; font-size: 0.9em;">You can use provisions to automate other aspects of your SIP setup such as provisioning your call servers or routings.</div>
                    </div>
                    <div class="py-3 list-group-item d-grid">
                        <p>Initial creation needs to be done via API or database.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Provision Popup -->
<div class="modal fade" id="provision_popup">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Provision - <span data-label="provision_name"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">

                <span data-message="edit_provision"></span>

                <div class="nav nav-tabs mb-3" id="nav-tab" role="tablist">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#nav-information" type="button" role="tab">Information</button>
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#nav-request" type="button" role="tab">Request</button>
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#nav-response" type="button" role="tab">Response</button>
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#nav-variables" type="button" role="tab">Variables</button>
                </div>

                <div class="tab-content" id="nav-tabContent">

                    <div class="tab-pane fade show active" id="nav-information" role="tabpanel" tabindex="0">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3 form-group">
                                    <label for="user_name" class="mb-1">Description</label>
                                    <input type="text" class="form-control" value="" data-input="provision_description"/>
                                </div>
                            </div>
                            <div class="col-md-6">   
                                <div class="mb-3 form-group">
                                    <label for="user_name" class="mb-1">Enabled</label>
                                    <select class="form-select" data-input="provision_enabled">
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="nav-request" role="tabpanel" tabindex="0">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3 form-group">
                                    <label for="user_name" class="mb-1">Method</label>
                                    <select class="form-select" data-input="provision_request_method">
                                        <option value="GET">GET</option>
                                        <option value="POST">POST</option>
                                        <option value="PUT">PUT</option>
                                        <option value="DELETE">DELETE</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-9">   
                                <div class="mb-3 form-group">
                                    <label for="user_name" class="mb-1">URL</label>
                                    <input type="text" class="form-control" value="" data-input="provision_request_url"/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="provision_request_body" class="form-label">Body</label>
                                    <textarea class="form-control" style="font-family: monospace;font-size: 14px;" data-input="provision_request_body" rows="10"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="provision_request_auth" class="form-label">Authentication <i style="font-size: 13px;">Examples: <a class="p-0 pe-1" data-provision-auth-example="basic">Basic</a><a class="p-0 pe-1" data-provision-auth-example="bearer">Bearer</a><a class="p-0 pe-1" data-provision-auth-example="api-key">API Key</a></i></label>
                                    <textarea class="form-control" style="font-family: monospace;font-size: 14px;" data-input="provision_request_auth" rows="10"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="nav-response" role="tabpanel" tabindex="0">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="provision_on_success" class="form-label">On Success</label>
                                    <textarea class="form-control" style="font-family: monospace;font-size: 14px;" data-input="provision_on_success" rows="10" spellcheck="false"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="provision_on_failure" class="form-label">On Failure</label>
                                    <textarea class="form-control" style="font-family: monospace;font-size: 14px;" data-input="provision_on_failure" rows="10" spellcheck="false"></textarea>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="tab-pane fade" id="nav-variables" role="tabpanel" tabindex="0">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="list-group list-group-flush">
                                    <div class="py-1 list-group-item">
                                        <h6>Request Body Variables</h6>
                                    </div>
                                    <div class="list-group-item">
                                        <div class="d-flex">
                                            <div class="flex-grow-1 text-break" style="margin-left: 0px;">
                                                <span style="word-break: break-all;">{{OpenSIPS.Username}}</span>
                                            </div>
                                            <div class="ml-3" style="opacity: 0.6;">String</div>
                                        </div>
                                        <div style="opacity: 0.5; font-size: 0.8em; margin-top: 2px;">
                                            This variables outputs the OpenSIPS username field as a string which can be used to create the subscriber on your SIP server
                                        </div>
                                    </div>
                                    <div class="list-group-item">
                                        <div class="d-flex">
                                            <div class="flex-grow-1 text-break" style="margin-left: 0px;">
                                                <span style="word-break: break-all;">{{OpenSIPS.Password}}</span>
                                            </div>
                                            <div class="ml-3" style="opacity: 0.6;">String</div>
                                        </div>
                                        <div style="opacity: 0.5; font-size: 0.8em; margin-top: 2px;">
                                            This variables generates and outputs a random password as a string which can be used to create subscribers on multiple SIP servers with the same password
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="list-group list-group-flush">
                                    <div class="py-1 list-group-item">
                                        <h6>Response Variables</h6>
                                    </div>
                                    <div class="list-group-item">
                                        <div class="d-flex">
                                            <div class="flex-grow-1 text-break" style="margin-left: 0px;">
                                                <span style="word-break: break-all;">$response_body</span>
                                            </div>
                                            <div class="ml-3" style="opacity: 0.6;">String</div>
                                        </div>
                                        <div style="opacity: 0.5; font-size: 0.8em; margin-top: 2px;">
                                            This variables outputs the raw response body from the API request. It can be used in the On Success and On Failure code snippets
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-action="provision_save">Save</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>