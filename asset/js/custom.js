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

$("#register_btn").click(function(){
    event.preventDefault();
    var btn  = $("#register_btn"); 
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
                // $('#form1')[0].reset()
            }
        })
    }
});


$("#login_btn").click(function(){
    event.preventDefault();
    var btn  = $("#login_btn"); 
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
                // $('#form1')[0].reset()
            }
        })
    }
});


$("#update_profile_btn").click(function(){
    event.preventDefault();
    var btn  = $("#update_profile_btn"); 
    if ($('#form-profile').parsley().validate()) {
        if(btn.text() == 'Loading...'){
        return false;
        }
        btn.text('Loading...')
        $.ajax({
            url         : $('#form-profile').attr('action'),
            type        : "POST",
            dataType    : 'json',
            data        : $('#form-profile').serialize(),
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
                // $('#form-profile')[0].reset()
            }
        })
    }
});

$('.owl-product-dtl').owlCarousel({
    loop: true,
    margin: 10,
    nav: true,
    dots: true,
    items: 1
});


$('.owl-carousel-promotions').owlCarousel({
    loop: true,
    margin: 10,
    nav: true,
    dots: true,
    items: 1
})

$('.owl-testimonials').owlCarousel({
    loop: true,
    margin: 0,
    nav: false,
    dots: true,
    items: 2,
    center: true,
    responsive : {
        0 : {
            center: false,
            items: 1,
        },
        767 : {
            center: true,
        }
    }
});

$('.owl-promotions').owlCarousel({
    loop: true,
    margin: 0,
    nav: true,
    dots: true,
    items: 1,
    navText: ["<span></span>","<span></span>"]
})

function get_data_contract(){
    $.ajax({
        url         : base_url_lang+'clientarea/get_data_contract/'+$('#contract-sort').val(),
        type        : "GET",
        dataType    : 'json',
        error   : function () {
            alert('error!');
        },
        success     : function(ret){
            $('#data-contract').html(ret.html);
            $('#contract-total-requested').html(ret.total);
        }
    })
};

$(document).ready(function(){
    $("#create_new").click(function(){
      $(".f-table").toggle();
      $(".f-form").toggle();
    });
    $("#contract-cancel").click(function(){
      $(".f-table").toggle();
      $(".f-form").toggle();
    });
    
    $("#v-pills-contract-tab").click(function(){
        get_data_contract();
    });
    $("#contract-sort").change(function(){
        get_data_contract();
    });
}); 
$("#contract-submit").click(function(){
    event.preventDefault();
    var btn  = $("#contract-submit"); 
    if ($('#form-contract').parsley().validate()) {
        if(btn.text() == 'LOADING...'){
        return false;
        }
        btn.text('LOADING...')
        $.ajax({
            url         : $('#form-contract').attr('action'),
            type        : "POST",
            dataType    : 'json',
            data        : $('#form-contract').serialize(),
            error   : function () {
                alert('error!');
                btn.text('SUBMIT')
            },
            success     : function(ret){
                btn.text('SUBMIT')
                alert(ret.message)
                if (ret.reload == true) {
                    location.reload()
                }
                if(ret.error==0){
                    $('#form-contract')[0].reset();
                    $(".f-table").toggle();
                    $(".f-form").toggle();
                    get_data_contract();
                }
            }
        })
    }
});