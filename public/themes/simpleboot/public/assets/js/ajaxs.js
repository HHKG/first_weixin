    function getinformation(method,action,data) {
        $.ajax({
            url: action,
            type: method,
            dataType: 'json',
            data: data,
            success: function (data) {
                console.log(4);
                if (data.code == 1) {
                    console.log(data);
                    return data;
                }
            }
        })
        //debugger;
    }



