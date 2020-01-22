$( document ).ready(function() {
    console.log( "ready!" );

$("#sec1v").click(function(e){
        e.preventDefault();
        var btn_id = "sec1v";jaxit( btn_id );
//        jQuery.ajax({
//            url : apfm_ajax.ajax_url,
//            type : 'post',
//            data : {
//                action : 'extractprocess',
//                post_id : btn_id
//            },
//            success : function( response ) {
//                jQuery('#extractview').html(response);
//            }
//        });

    }); 

	
$("#sec1p").click(function(e){
        e.preventDefault();
        var btn_id = "sec1p";jaxit( btn_id );
    }); 

$("#sec1d").click(function(e){
        e.preventDefault();
        var btn_id = "sec1d";jaxit( btn_id );
    }); 



$("#sec2v").click(function(e){
        e.preventDefault();
        var btn_id = "sec2v";jaxit( btn_id );
    }); 

$("#sec2p").click(function(e){
        e.preventDefault();
        var btn_id = "sec2p";jaxit( btn_id );
    }); 

$("#sec2d").click(function(e){
        e.preventDefault();
        var btn_id = "sec2d";jaxit( btn_id );
    }); 




$("#sec3v").click(function(e){
        e.preventDefault();
        var btn_id = "sec3v";jaxit( btn_id );
    }); 

$("#sec3p").click(function(e){
        e.preventDefault();
        var btn_id = "sec3p";jaxit( btn_id );
    }); 

$("#sec3d").click(function(e){
        e.preventDefault();
        var btn_id = "sec3d";jaxit( btn_id );
    }); 




$("#sec4v").click(function(e){
        e.preventDefault();
        var btn_id = "sec4v";jaxit( btn_id );
    }); 

$("#sec4p").click(function(e){
        e.preventDefault();
        var btn_id = "sec4p";jaxit( btn_id );
    }); 

$("#sec4d").click(function(e){
        e.preventDefault();
        var btn_id = "sec4d";jaxit( btn_id );
    }); 





//$("#extractview").html("TablePress database extract and process for Jaxslider.");   
function jaxit( btn_id ) {
        jQuery.ajax({
            url : apfm_ajax.ajax_url,
            type : 'post',
            data : {
                action : 'extractprocess',
                post_id : btn_id
            },
            success : function( response ) {
                jQuery('#extractview').html(response);
            }
        });
}

});
