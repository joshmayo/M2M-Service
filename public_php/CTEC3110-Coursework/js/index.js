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
                console.log("it Work");
                console.log(data);
                insertData(data);
            },
            error: function () {
              console.log("Failure: Ajax post failed");
            },
        });
    });

    function insertData (data) {

    }
});