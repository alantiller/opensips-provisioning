 /*
 * OpenSIPS Provisioning - Web UI
 * Copyright 2022 Alan Tiller
 */

/*
 * Global Variables
 */

const url_params = new URLSearchParams( window.location.search );
const public_url = location.protocol + '//' + location.host;


/*
 * Icon Variables
 */

var code_emoji = '<svg xmlns="http://www.w3.org/2000/svg" style="vertical-align: top;" width="16" height="16" fill="currentColor" class="bi bi-code" viewBox="0 0 16 16"><path d="M5.854 4.854a.5.5 0 1 0-.708-.708l-3.5 3.5a.5.5 0 0 0 0 .708l3.5 3.5a.5.5 0 0 0 .708-.708L2.707 8l3.147-3.146zm4.292 0a.5.5 0 0 1 .708-.708l3.5 3.5a.5.5 0 0 1 0 .708l-3.5 3.5a.5.5 0 0 1-.708-.708L13.293 8l-3.147-3.146z"/></svg>';
var message_emoji = '<svg xmlns="http://www.w3.org/2000/svg" style="vertical-align: top;" width="16" height="16" fill="currentColor" class="bi bi-chat-dots" viewBox="0 0 16 16"><path d="M5 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/><path d="m2.165 15.803.02-.004c1.83-.363 2.948-.842 3.468-1.105A9.06 9.06 0 0 0 8 15c4.418 0 8-3.134 8-7s-3.582-7-8-7-8 3.134-8 7c0 1.76.743 3.37 1.97 4.6a10.437 10.437 0 0 1-.524 2.318l-.003.011a10.722 10.722 0 0 1-.244.637c-.079.186.074.394.273.362a21.673 21.673 0 0 0 .693-.125zm.8-3.108a1 1 0 0 0-.287-.801C1.618 10.83 1 9.468 1 8c0-3.192 3.004-6 7-6s7 2.808 7 6c0 3.193-3.004 6-7 6a8.06 8.06 0 0 1-2.088-.272 1 1 0 0 0-.711.074c-.387.196-1.24.57-2.634.893a10.97 10.97 0 0 0 .398-2z"/></svg>';
var method_emoji = '<svg xmlns="http://www.w3.org/2000/svg" style="vertical-align: top;" width="16" height="16" fill="currentColor" class="bi bi-signpost-split" viewBox="0 0 16 16"><path d="M7 7V1.414a1 1 0 0 1 2 0V2h5a1 1 0 0 1 .8.4l.975 1.3a.5.5 0 0 1 0 .6L14.8 5.6a1 1 0 0 1-.8.4H9v10H7v-5H2a1 1 0 0 1-.8-.4L.225 9.3a.5.5 0 0 1 0-.6L1.2 7.4A1 1 0 0 1 2 7h5zm1 3V8H2l-.75 1L2 10h6zm0-5h6l.75-1L14 3H8v2z"/></svg>';
        

/*
 * Dashboard Functions
 */

$( document ).ready(function() {
    // Load Subscribers per Server
    if ($('[data-output="subscribers_per_server"]').length) {
        fetch(public_url + '/api/v1/dashboard/subscribers-by-server')
        .then(async function (result) {
            if (result.status == 200) {
                var response = await result.json();
                if (response.data.length > 0) {
                    response.data.forEach(function (server) {
                        list_group_item = $( '<div/>', {class: 'list-group-item'} );
                        d_flex = $( '<div/>', {class: 'd-flex'} );
                        flex_grow_1 = $( '<div/>', {class: 'flex-grow-1 text-break', style: 'margin-left: 0px;'} );
                        span = $( '<span/>', {style: 'word-break: break-all;'} ).html( server.server );
                        ml_3 = $( '<div/>', {class: 'ml-3', style: 'opacity: 0.6;'} ).html( server.subscribers );
                        flex_grow_1.append(span);
                        d_flex.append(flex_grow_1);
                        d_flex.append(ml_3);
                        list_group_item.append(d_flex);
                        $('[data-output="subscribers_per_server"]').append(list_group_item);
                    });
                } else {
                    $('[data-output="subscribers_per_server"]').append("<div class=\"text-center my-2 list-group-item\" style=\"opacity: 0.4;\">No servers found.</div>");
                }
            } else if (result.status == 403) {
                $('[data-output="subscribers_per_server"]').append("<div class=\"text-center my-2 list-group-item text-danger\" style=\"opacity: 0.4;\">You do not have access to view this data</div>");
            } else {
                $('[data-output="subscribers_per_server"]').append("<div class=\"text-center my-2 list-group-item text-danger\" style=\"opacity: 0.4;\">Failed to load server list.</div>");
            }
        });
    }
    // Load Total Subscribers
    if ($('[data-output="total_subscribers"]').length) {
        fetch(public_url + '/api/v1/dashboard/total-subscribers')
        .then(async function (result) {
            if (result.status == 200) {
                var response = await result.json();
                $('[data-output="total_subscribers"]').append(response.data.count);
            } else if (result.status == 403) {
                $('[data-output="total_subscribers"]').append("<span class=\"text-danger\">X</span>"); 
            } else {
                $('[data-output="total_subscribers"]').append("<span class=\"text-danger\">Failed to load</span>");
            }
        });
    }
    // Load Total Enabled Servers
    if ($('[data-output="total_active_servers"]').length) {
        fetch(public_url + '/api/v1/dashboard/total-enabled-servers')
        .then(async function (result) {
            if (result.status == 200) {
                var response = await result.json();
                $('[data-output="total_active_servers"]').append(response.data.count);
            } else if (result.status == 403) {
                $('[data-output="total_active_servers"]').append("<span class=\"text-danger\">X</span>"); 
            } else {
                $('[data-output="total_active_servers"]').append("<span class=\"text-danger\">Failed to load</span>");
            }
        });
    }
    // Load Total Enabled Servers
    if ($('[data-output="total_active_provisions"]').length) {
        fetch(public_url + '/api/v1/dashboard/total-enabled-provisions')
        .then(async function (result) {
            if (result.status == 200) {
                var response = await result.json();
                $('[data-output="total_active_provisions"]').append(response.data.count);
            } else if (result.status == 403) {
                $('[data-output="total_active_provisions"]').append("<span class=\"text-danger\">X</span>"); 
            } else {
                $('[data-output="total_active_provisions"]').append("<span class=\"text-danger\">Failed to load</span>");
            }
        });
    }
    // Load Audit Errors
    if ($('[data-output="audit_errors"]').length) {
        fetch(public_url + '/api/v1/dashboard/audit-errors')
        .then(async function (result) {
            if (result.status == 200) {
                var response = await result.json();
                if (response.data.length > 0) {
                    response.data.forEach(function (audit) {
                        list_group_item = $( '<div/>', {class: 'list-group-item'} );
                        d_flex = $( '<div/>', {class: 'd-flex'} );
                        flex_grow_1 = $( '<div/>', {class: 'flex-grow-1 text-break', style: 'margin-left: 0px;'} );
                        span = $( '<span/>', {style: 'word-break: break-all;'} ).html( audit.request_url );
                        ml_3 = $( '<div/>', {class: 'ml-3', style: 'opacity: 0.6;'} ).html( audit.timestamp );
                        extra_info = $( '<div/>', {style: 'opacity: 0.5; font-size: 0.8em; margin-top: 2px;'} ).html( method_emoji + '&nbsp;&nbsp;' + audit.method + '&nbsp;&nbsp;&nbsp;&nbsp;' + code_emoji + '&nbsp;&nbsp;' + audit.response_code );
                        if (audit.response_body != '') {
                            extra_info.append('&nbsp;&nbsp;&nbsp;&nbsp;' + message_emoji + '&nbsp;&nbsp;' + JSON.parse(audit.response_body).error.message);
                        }
                        flex_grow_1.append(span);
                        d_flex.append(flex_grow_1);
                        d_flex.append(ml_3);
                        list_group_item.append(d_flex);
                        list_group_item.append(extra_info);
                        $('[data-output="audit_errors"]').append(list_group_item);
                    });
                } else {
                    $('[data-output="audit_errors"]').append("<div class=\"text-center my-2 list-group-item\" style=\"opacity: 0.4;\">No audit errors found.</div>");
                }
            } else if (result.status == 403) {
                $('[data-output="audit_errors"]').append("<div class=\"text-center my-2 list-group-item text-danger\" style=\"opacity: 0.4;\">You do not have access to view this data</div>");
            } else {
                $('[data-output="audit_errors"]').append("<div class=\"text-center my-2 list-group-item text-danger\" style=\"opacity: 0.4;\">Failed to load server list.</div>");
            }
        });
    }
});


/*
 * Subscriber Functions
 */

// Load the datatable
$( document ).ready(function() {
    if ($('#list_subscribers').length) {
        var list_subscribers = $('#list_subscribers').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: '/api/v1/datatables/subscribers',
            columns: [
                { 
                    data: "uuid"
                },
                { 
                    data: "username"
                },
                { 
                    data: "domain"
                },
                { 
                    data: "attribute"
                },
                { 
                    data: "type"
                },
                { 
                    data: "value"
                },
                {
                    data: null,
                    className: 'all',
                    defaultContent: '<button class="btn btn-primary" data-action="view_subscriber">View</button>',
                },
                {
                    data: null,
                    className: 'all',
                    defaultContent: '<button class="btn btn-danger" data-action="delete_subscriber">Delete</button>',
                }
            ]
        });        
    }
});

// Create a subscriber
$(document).on("click", '[data-action="create_subscriber"]', function(e) {
    // Run error handling and return if any errors are found
    error = 0; 
    if( !$('[data-input="subscriber_cli"]').val() ) {$('[data-input="subscriber_cli"]').addClass('is-invalid');error++;}else{$('[data-input="subscriber_cli"]').removeClass('is-invalid');}
    if (error > 0) {return;}

    // Call the API
    fetch(public_url + '/api/v1/subscribers', {
        method: 'POST',
        body: JSON.stringify({
            "cli": $('[data-input="subscriber_cli"]').val()
        }),
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(async function (result) {
        var response = await result.json();
        if (result.status == 200) {
            $('[data-message="create_subscriber"]').html("<div class=\"alert alert-success\" role=\"alert\">Subscriber created</div>");
            $('#list_subscribers').DataTable().ajax.reload();
        } else {
            // Login failed show error message
            $('[data-message="create_subscriber"]').html("<div class=\"alert alert-danger\" role=\"alert\">" + response.error.message + "</div>");
        }
    });

    // Remove the error message and reset the text on the button
    setTimeout(function() { $('[data-message="create_subscriber"]').html(""); }, 3000);
});

// View a subscriber
$(document).on('click', '#list_subscribers tbody button[data-action="view_subscriber"]', function () {
    var data = $('#list_subscribers').DataTable().row($(this).parents('tr')).data();

    // Get the data from the API
    fetch(public_url + '/api/v1/subscribers/' + data['id'], {method: 'GET'})
    .then(async function (result) {
        var response = await result.json();
        if (result.status == 200) {
            $('[data-label="subscriber_username"]').html( response.data.username );
            $('[data-input="subscriber_id"]').val( response.data.id );
            $('[data-input="subscriber_uuid"]').val( response.data.uuid );
            $('[data-input="subscriber_username"]').val( response.data.username );
            $('[data-input="subscriber_domain"]').val( response.data.domain );
            $('[data-input="subscriber_attribute"]').val( response.data.attribute );
            $('[data-input="subscriber_type"]').val( response.data.type );
            $('[data-input="subscriber_server"]').val( response.data.value );
            $('[data-input="subscriber_last_modified"]').val( response.data.last_modified );
        } else {
            $('[data-message="subscribers"]').html("<div class=\"alert alert-danger\" role=\"alert\">Unable to show record: " + response.error.message + "</div>");
            setTimeout(function() { $('[data-message="subscribers"]').html(""); }, 3000);
            return;
        }
    });

    // Get the metadata table from the API
    fetch(public_url + '/api/v1/subscribers/' + data['id'] + '/metadata', {method: 'GET'})
    .then(async function (result) {
        var response = await result.json();
        if (result.status == 200) {
            $('[data-table="subscriber_metadata"]').html('');
            response.data.forEach(element => {
                table_row = $('<tr/>');
                row_label = $('<td/>', {scope: 'row'}).html(element.label);
                row_value = $('<td/>').html(element.value);
                row_timestamp = $('<td/>').html(element.timestamp);
                table_row.append(row_label);
                table_row.append(row_value);
                table_row.append(row_timestamp);
                $('[data-table="subscriber_metadata"]').append( table_row );
            });
        } else {
            $('[data-message="subscribers"]').html("<div class=\"alert alert-danger\" role=\"alert\">Unable to show record: " + response.error.message + "</div>");
            setTimeout(function() { $('[data-message="subscribers"]').html(""); }, 3000);
            return;
        }
    });

    // Reset system fields
    $('[data-input="subscriber_id"]').parent().hide();
    $('[data-input="subscriber_last_modified"]').parent().hide();
    $('[data-action="subscriber_hide_system_fields"]').html('Show System Fields').attr('data-action', 'subscriber_show_system_fields');

    // Open the modal
    $("#view_subscriber").modal('show');
});

// View a subscriber - Show System Fields
$(document).on('click', '[data-action="subscriber_show_system_fields"]', function () {
    $('[data-input="subscriber_id"]').parent().show();
    $('[data-input="subscriber_last_modified"]').parent().show();
    $('[data-action="subscriber_show_system_fields"]').html('Hide System Fields').attr('data-action', 'subscriber_hide_system_fields');
});

// View a subscriber - Hide System Fields
$(document).on('click', '[data-action="subscriber_hide_system_fields"]', function () {
    $('[data-input="subscriber_id"]').parent().hide();
    $('[data-input="subscriber_last_modified"]').parent().hide();
    $('[data-action="subscriber_hide_system_fields"]').html('Show System Fields').attr('data-action', 'subscriber_show_system_fields');
});

// Delete a subscriber
$(document).on('click', '#list_subscribers tbody button[data-action="delete_subscriber"]', function () {
    var data = $('#list_subscribers').DataTable().row($(this).parents('tr')).data();
    console.log(data);

    // Open up confirm box
    if (confirm("Are you sure you want to delete subscriber: " + data['username'])) {
        
        // Call the API
        fetch(public_url + '/api/v1/subscribers/' + data['id'], {method: 'DELETE'})
        .then(async function (result) {
            if (result.status == 200) {
                $('[data-message="subscribers"]').html("<div class=\"alert alert-success\" role=\"alert\">Subscriber deleted</div>");
                $('#list_subscribers').DataTable().ajax.reload();
            } else {
                var response = await result.json();
                $('[data-message="subscribers"]').html("<div class=\"alert alert-danger\" role=\"alert\">" + response.error.message + "</div>");
            }
        });

        // Remove the error message and reset the text on the button
        setTimeout(function() { $('[data-message="subscribers"]').html(""); }, 3000);
    }
});


/*
 * Servers Functions
 */

// Load the datatable
$( document ).ready(function() {
    if ($('#list_servers').length) {
        $('#list_servers').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: '/api/v1/datatables/servers',
            columnDefs: [
                {
                    targets: -1,
                    data: null,
                    defaultContent: '<button class="btn btn-danger">Delete</button>',
                }
            ]
        });
    }
});

// Create server
$(document).on("click", '[data-action="create_server"]', function(e) {
    // Run error handling and return if any errors are found
    error = 0; 
    if( !$('[data-input="server_description"]').val() ) {$('[data-input="server_description"]').addClass('is-invalid');error++;}else{$('[data-input="server_description"]').removeClass('is-invalid');}
    if( !$('[data-input="server_address"]').val() ) {$('[data-input="server_address"]').addClass('is-invalid');error++;}else{$('[data-input="server_address"]').removeClass('is-invalid');}
    if( !$('[data-input="server_domain"]').val() ) {$('[data-input="server_domain"]').addClass('is-invalid');error++;}else{$('[data-input="server_domain"]').removeClass('is-invalid');}
    if( !$('[data-input="server_attribute"]').val() ) {$('[data-input="server_attribute"]').addClass('is-invalid');error++;}else{$('[data-input="server_attribute"]').removeClass('is-invalid');}
    if( !$('[data-input="server_type"]').val() ) {$('[data-input="server_type"]').addClass('is-invalid');error++;}else{$('[data-input="server_type"]').removeClass('is-invalid');}
    if( !$('[data-input="server_enabled"]').val() ) {$('[data-input="server_enabled"]').addClass('is-invalid');error++;}else{$('[data-input="server_enabled"]').removeClass('is-invalid');}
    if (error > 0) {return;}

    // Call the API
    fetch(public_url + '/api/v1/servers', {
        method: 'POST',
        body: JSON.stringify({
            "description": $('[data-input="server_description"]').val(),
            "address": $('[data-input="server_address"]').val(),
            "default_domain": $('[data-input="server_domain"]').val(),
            "default_attribute": $('[data-input="server_attribute"]').val(),
            "default_type": $('[data-input="server_type"]').val(),
            "enabled": $('[data-input="server_enabled"]').val()
        }),
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(async function (result) {
        var response = await result.json();
        if (result.status == 200) {
            $('[data-message="create_servers"]').html("<div class=\"alert alert-success\" role=\"alert\">Server created.</div>");
            $('#list_servers').DataTable().ajax.reload();
        } else {
            // Login failed show error message
            $('[data-message="create_servers"]').html("<div class=\"alert alert-danger\" role=\"alert\">" + response.error.message + "</div>");
        }
    });

    // Remove the error message and reset the text on the button
    setTimeout(function() { $('[data-message="create_servers"]').html(""); }, 3000);
});

// Delete server
$(document).on('click', '#list_servers tbody button', function () {
    var data = $('#list_servers').DataTable().row($(this).parents('tr')).data();
    // Open up confirm box
    if (confirm("Are you sure you want to delete server: " + data[2])) {
        
        // Call the API
        fetch(public_url + '/api/v1/servers/' + data[0], {method: 'DELETE'})
        .then(async function (result) {
            if (result.status == 200) {
                $('[data-message="servers"]').html("<div class=\"alert alert-success\" role=\"alert\">Server deleted</div>");
                $('#list_servers').DataTable().ajax.reload();
            } else {
                var response = await result.json();
                $('[data-message="servers"]').html("<div class=\"alert alert-danger\" role=\"alert\">" + response.error.message + "</div>");
            }
        });

        // Remove the error message and reset the text on the button
        setTimeout(function() { $('[data-message="servers"]').html(""); }, 3000);
    }
});


/*
 * Provisions Functions
 */

// Load the provisions
$( document ).ready(function() {
    load_provisions();
});
function load_provisions() {
    // Remove all items loaded in
    $('[data-type="provision_list_item"]').remove();

    // Get the provisions
    fetch(public_url + '/api/v1/provisions', {method: 'GET'})
    .then(async function (result) {
        var response = await result.json();
        if (result.status == 200) {
            $('[data-table="subscriber_metadata"]').html('');
            response.data.forEach(element => {
                list_item = $('<div/>', {class: 'list-group-item', 'data-type': 'provision_list_item'});
                container = $('<div/>', {class: 'd-md-flex justify-content-between align-items-center'});
                left = $('<div/>');
                right = $('<div/>');
                title = $('<div/>', {class: 'flex-grow-1 text-break', style: 'margin-left: 0px;'});
                title_span = $('<span/>', {style: 'word-break: break-all;'}).html( element.description + '<span class="badge rounded-pill text-bg-primary ms-3">' + element.server + '</span>' + '<span class="badge rounded-pill ' + (element.enabled == 1 ? 'text-bg-success' : 'text-bg-danger') + ' ms-2">' + (element.enabled == 1 ? 'Enabled' : 'Disabled') + '</span>' );
                description = $('<div/>', {style: 'opacity: 0.5; font-size: 0.8em; margin-top: 2px;'}).html( element.request_url );
                edit_button = $('<button/>', {type: 'submit', class: 'btn btn-primary ms-2', 'data-action': 'provision_edit', 'data-id': element.id}).html('Edit');
                //delete_button = $('<button/>', {type: 'submit', class: 'btn btn-danger ms-2', 'data-action': 'provision_delete', 'data-id': element.id}).html('Delete');
                left.append(title);title.append(title_span);left.append(description);right.append(edit_button);
                //right.append(delete_button);
                container.append(left);container.append(right);list_item.append(container);
                switch (element.opperation) {
                    case 'create':
                        $('[data-list="provision_create"]').append( list_item );
                        break;
                    case 'update':
                        $('[data-list="provision_update"]').append( list_item );
                        break;
                    case 'delete':
                        $('[data-list="provision_delete"]').append( list_item );
                        break;
                }
            });
        } else {
            $('[data-message="subscribers"]').html("<div class=\"alert alert-danger\" role=\"alert\">Unable to show record: " + response.error.message + "</div>");
            setTimeout(function() { $('[data-message="subscribers"]').html(""); }, 3000);
            return;
        }
    });
}

// Set the servers
$( document ).ready(function() {
    if ($('[data-input="provision_create_server"]').length) {
        fetch(public_url + '/api/v1/servers')
        .then(async function (result) {
            if (result.status == 200) {
                var response = await result.json();
                if (response.data.length > 0) {
                    response.data.forEach(function (server) {
                        option = $( '<option/>', {value: server.address} ).html( server.description + ' (' + server.address + ')' );
                        $('[data-input="provision_create_server"]').append(option);
                    });
                }
            }
        });
    }
});

// Create provision
$(document).on("click", '[data-action="provision_create"]', function(e) {
    // Run error handling and return if any errors are found
    error = 0; 
    if( !$('[data-input="provision_create_description"]').val() ) {$('[data-input="provision_create_description"]').addClass('is-invalid');error++;}else{$('[data-input="provision_create_description"]').removeClass('is-invalid');}
    if( !$('[data-input="provision_create_server"]').val() ) {$('[data-input="provision_create_server"]').addClass('is-invalid');error++;}else{$('[data-input="provision_create_server"]').removeClass('is-invalid');}
    if( !$('[data-input="provision_create_opperation"]').val() ) {$('[data-input="provision_create_opperation"]').addClass('is-invalid');error++;}else{$('[data-input="provision_create_opperation"]').removeClass('is-invalid');}
    if (error > 0) {return;}

    // Call the API
    fetch(public_url + '/api/v1/provisions', {
        method: 'POST',
        body: JSON.stringify({
            "description": $('[data-input="provision_create_description"]').val(),
            "server": $('[data-input="provision_create_server"]').val(),
            "opperation": $('[data-input="provision_create_opperation"]').val()
        }),
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(async function (result) {
        var response = await result.json();
        if (result.status == 200) {
            $('[data-message="provision_create"]').html("<div class=\"alert alert-success\" role=\"alert\">Provision created.</div>");
            load_provisions();
        } else {
            // Login failed show error message
            $('[data-message="provision_create"]').html("<div class=\"alert alert-danger\" role=\"alert\">" + response.error.message + "</div>");
        }
    });

    // Remove the error message and reset the text on the button
    setTimeout(function() { $('[data-message="provision_create"]').html(""); }, 3000);
});

// View provision
$(document).on('click', '[data-action="provision_edit"]', function () {
    var pid = $(this).attr('data-id');

    // Get the data from the API
    fetch(public_url + '/api/v1/provisions/' + pid, {method: 'GET'})
    .then(async function (result) {
        var response = await result.json();
        if (result.status == 200) {
            $('[data-label="provision_name"]').html( response.data.description );
            $('[data-input="provision_description"]').val( response.data.description );
            $('[data-input="provision_enabled"]').val( response.data.enabled );
            $('[data-input="provision_request_method"]').val( response.data.request_method );
            $('[data-input="provision_request_url"]').val( response.data.request_url );
            $('[data-input="provision_request_body"]').val( response.data.request_body );
            $('[data-input="provision_request_auth"]').val( response.data.request_auth );
            $('[data-input="provision_on_success"]').val( response.data.on_success );
            $('[data-input="provision_on_failure"]').val( response.data.on_failure );
            $('[data-action="provision_save"]').attr( 'data-id', response.data.id );
        } else {
            $('[data-message="provisions"]').html("<div class=\"alert alert-danger\" role=\"alert\">Unable to show record: " + response.error.message + "</div>");
            setTimeout(function() { $('[data-message="provisions"]').html(""); }, 3000);
            return;
        }
    });

    // Open the modal
    $("#provision_popup").modal('show');
});

// Insert Authentication Example
$(document).on('click', '[data-provision-auth-example]', function () {
    var type = $(this).attr('data-provision-auth-example');
    switch (type) {
        case 'basic':
            $('[data-input="provision_request_auth"]').val('{\n    "type": "basic",\n    "username": "",\n    "password": ""\n}');
            break;
        case 'bearer':
            $('[data-input="provision_request_auth"]').val('{\n    "type": "bearer",\n    "bearer": ""\n}');
            break;
        case 'api-key':
            $('[data-input="provision_request_auth"]').val('{\n    "type": "api-key",\n    "key": "",\n    "value": ""\n}');
            break;
    }
});

// Edit provision
$(document).on('click', '[data-action="provision_save"]', function ( event ) {
    event.preventDefault();
    var values = $(this).serialize();
    var pid = $(this).attr('data-id');

    // Get the data from the API
    fetch(public_url + '/api/v1/provisions/' + pid, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            'description': $('[data-input="provision_description"]').val(),
            'enabled': $('[data-input="provision_enabled"]').val(),
            'request_method': $('[data-input="provision_request_method"]').val(),
            'request_url': $('[data-input="provision_request_url"]').val(), 
            'request_body': $('[data-input="provision_request_body"]').val(), 
            'request_auth': $('[data-input="provision_request_auth"]').val(), 
            'on_success': $('[data-input="provision_on_success"]').val(), 
            'on_failure': $('[data-input="provision_on_failure"]').val()
        })
    })
    .then(async function (result) {
        var response = await result.json();
        if (result.status == 200) {
            $('[data-action="provision_save"]').attr( 'data-id', '' );
            $("#provision_popup").modal('hide');
            load_provisions();
        } else {
            $('[data-message="edit_provision"]').html("<div class=\"alert alert-danger\" role=\"alert\">Unable to save record: " + response.error.message + "</div>");
            
            return;
        }
        setTimeout(function() { $('[data-message="edit_provision"]').html(""); }, 3000);
    });

    // Open the modal
    $("#provision_popup").modal('show');
});


/*
 * User Functions
 */

// Load the datatable
$( document ).ready(function() {
    if ($('#list_users').length) {
        $('#list_users').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: '/api/v1/datatables/users',
            columnDefs: [
                {
                    targets: -1,
                    data: null,
                    defaultContent: '<button class="btn btn-danger">Delete</button>',
                }
            ]
        });
    }
});

// Create a user
$(document).on("click", '[data-action="create_user"]', function(e) {
    // Run error handling and return if any errors are found
    error = 0; 
    if( !$('[data-input="user_name"]').val() ) {$('[data-input="user_name"]').addClass('is-invalid');error++;}else{$('[data-input="user_name"]').removeClass('is-invalid');}
    if( !$('[data-input="user_username"]').val() ) {$('[data-input="user_username"]').addClass('is-invalid');error++;}else{$('[data-input="user_username"]').removeClass('is-invalid');}
    if( !$('[data-input="user_password"]').val() ) {$('[data-input="user_password"]').addClass('is-invalid');error++;}else{$('[data-input="user_password"]').removeClass('is-invalid');}
    if( !$('[data-input="user_group"]').val() ) {$('[data-input="user_group"]').addClass('is-invalid');error++;}else{$('[data-input="user_group"]').removeClass('is-invalid');}
    if (error > 0) {return;}

    // Call the API
    fetch(public_url + '/api/v1/users', {
        method: 'POST',
        body: JSON.stringify({
            "name": $('[data-input="user_name"]').val(),
            "username": $('[data-input="user_username"]').val(),
            "password": $('[data-input="user_password"]').val(),
            "group": $('[data-input="user_group"]').val()
        }),
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(async function (result) {
        var response = await result.json();
        if (result.status == 200) {
            $('[data-message="create_users"]').html("<div class=\"alert alert-success\" role=\"alert\">User created.</div>");
            $('#list_users').DataTable().ajax.reload();
        } else {
            // Login failed show error message
            $('[data-message="create_users"]').html("<div class=\"alert alert-danger\" role=\"alert\">" + response.error.message + "</div>");
        }
    });

    // Remove the error message and reset the text on the button
    setTimeout(function() { $('[data-message="create_users"]').html(""); }, 3000);
});

// Set the groups
$( document ).ready(function() {
    if ($('[data-input="user_group"]').length) {
        fetch(public_url + '/api/v1/permissions/groups')
        .then(async function (result) {
            if (result.status == 200) {
                var response = await result.json();
                if (response.data.length > 0) {
                    response.data.forEach(function (server) {
                        option = $( '<option/>', {name: server} ).html(server);
                        $('[data-input="user_group"]').append(option);
                    });
                }
            }
        });
    }
});

// Delete a user
$(document).on('click', '#list_users tbody button', function () {
    var data = $('#list_users').DataTable().row($(this).parents('tr')).data();
    console.log(data);

    // Open up confirm box
    if (confirm("Are you sure you want to delete user: " + data[3])) {
        
        // Call the API
        fetch(public_url + '/api/v1/users/' + data[0], {method: 'DELETE'})
        .then(async function (result) {
            if (result.status == 200) {
                $('[data-message="users"]').html("<div class=\"alert alert-success\" role=\"alert\">User deleted</div>");
                $('#list_users').DataTable().ajax.reload();
            } else {
                var response = await result.json();
                $('[data-message="users"]').html("<div class=\"alert alert-danger\" role=\"alert\">" + response.error.message + "</div>");
            }
        });

        // Remove the error message and reset the text on the button
        setTimeout(function() { $('[data-message="users"]').html(""); }, 3000);
    }
});


/*
 * Permissions Functions
 */

// Load the datatable
$( document ).ready(function() {
    if ($('#list_permissions').length) {
        $('#list_permissions').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: '/api/v1/datatables/permissions',
            columnDefs: [
                {
                    targets: -1,
                    data: null,
                    defaultContent: '<button class="btn btn-danger">Delete</button>',
                }
            ]
        });
    }
});

// Create a permission
$(document).on("click", '[data-action="create_permission"]', function(e) {
    // Run error handling and return if any errors are found
    error = 0; 
    if( !$('[data-input="permission_group"]').val() ) {$('[data-input="permission_group"]').addClass('is-invalid');error++;}else{$('[data-input="permission_group"]').removeClass('is-invalid');}
    if( !$('[data-input="permission_entity"]').val() ) {$('[data-input="permission_entity"]').addClass('is-invalid');error++;}else{$('[data-input="permission_entity"]').removeClass('is-invalid');}
    if( !$('[data-input="permission_read"]').val() ) {$('[data-input="permission_read"]').addClass('is-invalid');error++;}else{$('[data-input="permission_read"]').removeClass('is-invalid');}
    if( !$('[data-input="permission_update"]').val() ) {$('[data-input="permission_update"]').addClass('is-invalid');error++;}else{$('[data-input="permission_update"]').removeClass('is-invalid');}
    if( !$('[data-input="permission_create"]').val() ) {$('[data-input="permission_create"]').addClass('is-invalid');error++;}else{$('[data-input="permission_create"]').removeClass('is-invalid');}
    if( !$('[data-input="permission_delete"]').val() ) {$('[data-input="permission_delete"]').addClass('is-invalid');error++;}else{$('[data-input="permission_delete"]').removeClass('is-invalid');}
    if (error > 0) {return;}

    // Call the API
    fetch(public_url + '/api/v1/permissions', {
        method: 'POST',
        body: JSON.stringify({
            "group": $('[data-input="permission_group"]').val(),
            "entity": $('[data-input="permission_entity"]').val(),
            "read": $('[data-input="permission_read"]').val(),
            "update": $('[data-input="permission_update"]').val(),
            "create": $('[data-input="permission_create"]').val(),
            "delete": $('[data-input="permission_delete"]').val() 
        }),
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(async function (result) {
        var response = await result.json();
        if (result.status == 200) {
            $('[data-message="create_permission"]').html("<div class=\"alert alert-success\" role=\"alert\">Permission created</div>");
            $('#list_permissions').DataTable().ajax.reload();
        } else {
            // Login failed show error message
            $('[data-message="create_permission"]').html("<div class=\"alert alert-danger\" role=\"alert\">" + response.error.message + "</div>");
        }
    });

    // Remove the error message and reset the text on the button
    setTimeout(function() { $('[data-message="create_permission"]').html(""); }, 3000);
});

// Set the groups
$( document ).ready(function() {
    if ($('#permission_groups').length) {
        fetch(public_url + '/api/v1/permissions/groups')
        .then(async function (result) {
            if (result.status == 200) {
                var response = await result.json();
                if (response.data.length > 0) {
                    response.data.forEach(function (server) {
                        option = $( '<option/>', {value: server} ).html(server);
                        $('#permission_groups').append(option);
                    });
                }
            }
        });
    }
});

// Delete a user
$(document).on('click', '#list_permissions tbody button', function () {
    var data = $('#list_permissions').DataTable().row($(this).parents('tr')).data();
    // Open up confirm box
    if (confirm("Are you sure you want to delete the permission: " + data[2])) {
        
        // Call the API
        fetch(public_url + '/api/v1/permissions/' + data[0], {method: 'DELETE'})
        .then(async function (result) {
            if (result.status == 200) {
                $('[data-message="permissions"]').html("<div class=\"alert alert-success\" role=\"alert\">Permission deleted</div>");
                $('#list_permissions').DataTable().ajax.reload();
            } else {
                var response = await result.json();
                $('[data-message="permissions"]').html("<div class=\"alert alert-danger\" role=\"alert\">" + response.error.message + "</div>");
            }
        });

        // Remove the error message and reset the text on the button
        setTimeout(function() { $('[data-message="permissions"]').html(""); }, 3000);
    }
});


/*
 * Audit Functions
 */

// Load the datatable
$(document).ready( function () {
    if ($('#list_audit').length) {
        function audit_format(row) {
            return (
                '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">' +
                '<tr>' +
                '<td>Request Body:</td>' +
                '<td>' +
                row[5] +
                '</td>' +
                '</tr>' +
                '<tr>' +
                '<td>Response Body:</td>' +
                '<td>' +
                row[6] +
                '</td>' +
                '</tr>' +
                '</table>'
            );
        }

        var list_audit = $('#list_audit').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: '/api/v1/datatables/audit',
            order: [
                [1, 'desc']
            ],
            "rowCallback": function( row, data ) {
                if ( data[2].substring(0, 1) != "2" ) {
                    $(row).css( 'background-color', '#ffa59e' );
                }
            },
            columns: [
                {
                    className: 'dt-control',
                    orderable: false,
                    data: null,
                    defaultContent: '',
                },
                { data: 0 },
                { data: 1 },
                { data: 2 },
                { data: 3 },
                { data: 4 },
                { data: 7 },
            ],
        });

        // Add event listener for opening and closing details
        $('#list_audit tbody').on('click', 'td.dt-control', function () {
            var tr = $(this).closest('tr');
            var row = list_audit.row(tr);
    
            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            } else {
                // Open this row
                row.child(audit_format(row.data())).show();
                tr.addClass('shown');
            }
        }); 
    }
});


/*
 * Account Functions
 */

// Change password
$(document).on("click", '[data-action="change_password"]', function(e) {
    // Run error handling and return if any errors are found
    error = 0; 
    if( !$('[data-input="password_current"]').val() ) {$('[data-input="password_current"]').addClass('is-invalid');error++;}else{$('[data-input="password_current"]').removeClass('is-invalid');}
    if( !$('[data-input="password_new"]').val() ) {$('[data-input="password_new"]').addClass('is-invalid');error++;}else{$('[data-input="password_new"]').removeClass('is-invalid');}
    if( !$('[data-input="password_repeat"]').val() ) {$('[data-input="password_repeat"]').addClass('is-invalid');error++;}else{$('[data-input="password_repeat"]').removeClass('is-invalid');}   
    if( $('[data-input="password_new"]').val() != $('[data-input="password_repeat"]').val() ) {$('[data-input="password_new"]').addClass('is-invalid');$('[data-input="password_repeat"]').addClass('is-invalid');error++;} else {$('[data-input="password_new"]').removeClass('is-invalid');$('[data-input="password_repeat"]').removeClass('is-invalid');}
    if (error > 0) {return;}

    // Call the API
    fetch(public_url + '/api/v1/account/password', {
        method: 'PUT',
        body: JSON.stringify({
            "password": $('[data-input="password_current"]').val(),
            "new_password": $('[data-input="password_new"]').val()
        }),
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(async function (result) {
        if (result.status == 204) {
            $('[data-message="change_password"]').html("<div class=\"alert alert-success\" role=\"alert\">Password changed successfully</div>");
        } else {
            var response = await result.json();
            $('[data-message="change_password"]').html("<div class=\"alert alert-danger\" role=\"alert\">" + response.error.message + "</div>");
        }
    });

    // Remove the error message and reset the text on the button
    setTimeout(function() { $('[data-message="change_password"]').html(""); }, 3000);
});


/*
 * Auth Functions
 */

// Auth - Login
$(document).on("click", '[data-action="login"]', function(e) {
    // Run error handling and return if any errors are found
    error = 0; 
    if( !$('[data-input="username"]').val() ) {$('[data-input="username"]').addClass('is-invalid');error++;}else{$('[data-input="username"]').removeClass('is-invalid');}
    if( !$('[data-input="password"]').val() ) {$('[data-input="password"]').addClass('is-invalid');error++;}else{$('[data-input="password"]').removeClass('is-invalid');}
    if (error > 0) {return;}

    // Set the loading icon
    $('[data-action="login"]').html("<i class=\"fal fa-spinner-third fa-spin\"></i>");

    // Call the API
    fetch(public_url + '/api/v1/login', {
        method: 'POST',
        body: JSON.stringify({
            "username": $('[data-input="username"]').val(), 
            "password": $('[data-input="password"]').val() 
        }),
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(async function (result) {
        if (result.status == 204) {
            // Login was a success, redirect
            window.location.href = public_url;
        } else {
            // Login failed show error message
            var data = await result.json();
            $('[data-message="login"]').html("<div class=\"alert alert-danger\" role=\"alert\">" + data.error.message + "</div>");
        }
    });

    // Remove the error message and reset the text on the button
    setTimeout(function() { $('[data-message="login"]').html(""); }, 3000);
    $('[data-action="login"]').html("Sign In");
});

// Auth - Logout
$(document).on("click", '[data-action="logout"]', function(e) {
    // Call the API
    fetch(public_url + '/api/v1/logout', {method: 'POST'})
    .then(async function (result) {
        if (result.status == 204) {
            window.location.href = public_url;
        } else {
            var data = await result.json();
            alert(data.error.message);
        }
    });
});