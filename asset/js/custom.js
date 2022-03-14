$("#contact_us_btn").click(function(){
    event.preventDefault();
    $('#form1').prepend('<input type="hidden" name="action" value="contact_us">');
    var btn  = $("#contact_us_btn"); 
    if ($('#form1').parsley().validate()) {
        if(btn.text() == 'Loading...'){
        return false;
        }
        btn.text('Loading...')
        $.ajax({
            url         : $('#form1').attr('action'),
            type        : "POST",
            dataType    : 'json',
            data        : $('#form1').serialize(),
            error   : function () {
                alert('error!');
                btn.text('Submit')
            },
            success     : function(ret){
                btn.text('Submit')
                alert(ret.message)
                if (ret.reload == true) {
                    location.reload()
                }
                $('#form1')[0].reset()
            }
        })
    }
});

$('select#sort').change(function () {
    var optionSelected = $(this).find("option:selected").val();
    window.location = window.location.href.split('?')[0] + "?sort="+optionSelected
});