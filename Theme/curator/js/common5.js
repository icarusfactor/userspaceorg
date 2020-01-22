//sitehost variable with trailing slash
var wphost="http://userspace.org/"; 



//Progmatically load page content to active section of home page. Need to add LOADING section.
// Functions to process async loading operation visually.
function JaxLoader( site_page ){
if( typeof site_page !== 'undefined' ) {
$('#collections').html( "<IMG WIDTH=300 HEIGHT=600 SRC='http://userspace.org/wp-content/uploads/2019/12/1x1transparent.png' >" );     
$('span.loadmedia').show();
console.log("SHOW LOADING "+site_page );
loadContent( site_page);
} else {  
console.log("PASS");
}
return false;
}

function loadContent(site_page) {
    siteh = wphost+site_page;	
    console.log("GET:"+siteh);
    fetch( siteh )
       .then(res=> res.text())
       .then((html) => { 
       
       var els = $(html).find('#primary,script,style')
       $('#collections').html(els);     

       });
    console.log("DONE");
    return false;
}


//Setup CLICK event listener
$('.collections').click(function(e){
e.stopImmediatePropagation();	
e.preventDefault();

switch(e.target.id) {
  case "RAM":
    console.log("SELECT RAM");
    JaxLoader( 'ram' );
    return false;       //You have to return false from CLICK event to activate AJAX.
    break;
  case "NEWS":
    console.log("SELECT NEWS");
    JaxLoader( 'newsfeeds' );
    return false;
    break;
  case "VIDEO":
    console.log("SELECT VIDEO");
    JaxLoader( 'videocast' );
    return false;
    break;
  case "AUDIO":
    console.log("SELECT AUDIO");
    JaxLoader( 'podcast' );
    return false;
    break;
  case "INFOSEC":
    console.log("SELECT INFOSEC");
    JaxLoader( 'infosec' );
    return false;
    break;
  default:
    $('span.loadmedia').hide();
    return false;

}

});


//For page transitions this fixes another plugin putting it in. 
$("body").removeClass("animsition");

//Load init for Tablepress tables plugin
$(document).ready( function () {
    $('.tablepress-id-1').DataTable();
} );

