$(document).ready(function () {

    var tableRefresh;
    var rootURL = window.location.origin + '/CTEC3110-Coursework';

    var table = $('#message-table').DataTable();

    $('#auto-refresh-switch').change(function (e) {
        if (this.checked) {
            tableRefresh = setInterval(function () {
                fetchMessages();
            }, 30000);
        }
        else {
            clearInterval(tableRefresh);
        }
    });

    $('#update-table-btn').on('click', function (e) {
        e.preventDefault();
        $('#load-spinner').css('display', 'inline-block');
        fetchMessages();
    });

    function fetchMessages() {
        $('#error-msg').empty();
        console.log('Fetching Messages...');
        $.ajax({
            url: rootURL + "/updateTable",
            type: 'GET',
            dataType: "json",
            success: function (data) {
                console.log("Success: GET successful");
                $('#load-spinner').hide();
                insertData(data);
            },
            error: function (e) {
                console.log("Failure: GET failed");
                $('#load-spinner').hide();
                displayError(e);
            },
        });
    }

    function displayError(error) {
        $('#error-msg').append(`
            <div>${error.responseText}</div>
        `);
    };

    function insertData(data) {
        table.clear().draw().destroy();

        $.each(data, function (index, message) {
            $('#homepage-message-tbl-body').append(`
            <tr>
                <td>${message.heater + "°C"}</td>
                <td>${message.keypad}</td>
                <td>${(parseInt(message.fan) === 1) ? 'Forwards' : 'Backwards'}</td>
                <td>${(parseInt(message.switch_1) === 1) ? 'on' : 'off'}</td>
                <td>${(parseInt(message.switch_2) === 1) ? 'on' : 'off'}</td>
                <td>${(parseInt(message.switch_3) === 1) ? 'on' : 'off'}</td>
                <td>${(parseInt(message.switch_4) === 1) ? 'on' : 'off'}</td>
                <td>${message.received_time}</td>
                <td>${message.source_msisdn}</td>
                <td>${message.destination_msisdn}</td>
            </tr>
            `);
        });

        table = $('#message-table').DataTable();
    };
});