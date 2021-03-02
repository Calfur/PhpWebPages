/**
 * JS for component: `index`
 */

$(function() {
    // footer text with year
    var date = new Date();
    $('#footer-text').html('© ' + date.getFullYear() + ' Robin Nater');

    // onclick events
    $('data-table').load("pages/list.html", function() {
        console.log('INFO: data table loaded');
        $.getScript("js/list.js", function() {
            console.log('INFO: data table JS loaded');
        });
    });

    $('.mode-new').click(function(e) {
        e.preventDefault();
        showModal(0);
    });
});

// show toasts from ajax response
function showResponseToasts(data) {
    // * Message handling
    if (data['success'].length > 0) {
        data['success'].forEach(element => {
            console.log(element);
            M.toast({ html: element, classes: 'green' });
        });
    }
    if (data['error'].length > 0) {
        console.log("err");
        data['error'].forEach(element => {
            M.toast({ html: element, classes: 'red' });
            console.error(element);
        });
    }
    // * END Message handling
}

// manual toast trigger
function showToast(text, type) {
    switch (type) {
        case 'error':
            M.toast({ html: text, classes: 'red' });
            break;
        case 'success':
            M.toast({ html: text, classes: 'green' });
            break;
        default:
            M.toast({ html: text, classes: 'blue lighten-1' });
            break;
    }
}

function showModal(id) {
    // new or update
    if (id == 0) {
        $('data-modal').load("pages/form.html", function() {
            console.log('INFO: form loaded')
            $.getScript("js/form.js", function() {
                console.log('INFO: form JS loaded');

                $('#modal-title').html('New');
                $('#field-id').val(0);

                // update the text fields
                M.updateTextFields();
                $('select').formSelect();

                // open modal after all
                var instance = M.Modal.getInstance($('#modal'));
                instance.open();
            });
        });
    } else {
        $('data-modal').load("pages/form.html", function() {
            console.log('INFO: form loaded')
            $.getScript("js/form.js", function() {
                console.log('INFO: form JS loaded');
                // set modal html
                $('#modal-title').html('Edit - ID: ' + id); // * Get parent and read attr `data-id` => data('id')

                // fetch data
                $.ajax({
                    type: "GET",
                    url: "data/api.php?action=get&id=" + id,
                    data: "",
                    dataType: "json",
                    success: function(response) {

                        // * Set data
                        $('#field-id').val(response['data'][0]['id']);
                        $('#field-name').val(response['data'][0]['name']);
                        $('#field-bauart').val(response['data'][0]['bauart']);
                        $('#field-kraftstoff').val(response['data'][0]['kraftstoff']);
                        $('#field-farbe').val(response['data'][0]['color']);
                        $('#field-tank').val(response['data'][0]['tank']);
                        $('#field-email').val(response['data'][0]['email']);

                        // IMPORTANT
                        // update the text fields
                        M.updateTextFields();
                        $('select').formSelect();

                        // open modal after all
                        var instance = M.Modal.getInstance($('#modal'));
                        instance.open();
                    }
                });
            });
        });
    }
}

function showDeleteModal(id) {
    $('data-modal-delete').load("pages/form-delete.html", function() {
        console.log('INFO: delete form loaded');
        $.getScript("js/form-delete.js", function() {
            console.log('INFO: delete form JS loaded');
            // set id to reference
            $('#modal-delete').data('id', id);

            // set modal html
            $('#modal-delete-title').html('Delete - ID: ' + id);

            // change html structure
            $('#alert-text').html(`Do you want to delete the record: <b>${id}</b>`);

            // open modal after all
            var instance = M.Modal.getInstance($('#modal-delete'));
            instance.open();
        });
    });
}