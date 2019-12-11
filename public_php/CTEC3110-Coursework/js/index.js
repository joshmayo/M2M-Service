$(document).ready(function () {

    var rootURL = window.location.origin + '/CTEC3110-Coursework'

    $('#update-table-btn').on('click', function (e) {
        e.preventDefault();

        $.ajax({

            url: rootURL + "/updateTable",
            type: 'GET',
            dataType: "json",
            success: function (data)
            {
                console.log("Success: GET successful");
                console.log(data);
                insertData(data);
            },
            error: function () {
              console.log("Failure: GET failed");
            },
        });
    });

    function insertData (data) {

    }
});