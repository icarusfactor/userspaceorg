const version_of_curator_init_works="5"
const works_site = "http://userspace.org";       

console.log("VERISON:"+version_of_curator_init_works);

function showLoadingWork() {

    const spcer = "&nbsp;</BR>".repeat(6);
    document.body.querySelector("#works").innerHTML = spcer;
    document.body.querySelector("#works").sparkle({
        position: "afterbegin",
        count: 30, 
        minAge: 500,
        maxAge: 2000,
        size: {
            width: "20px",
            height: "20px"
        },
	starsource: `<svg 
      width="100%" 
      height="100%" 
      viewBox="0 0 20 20" 
      version="1.1" 
      xmlns="http://www.w3.org/2000/svg"
      xmlns:xlink="http://www.w3.org/1999/xlink" 
      xml:space="preserve" 
      xmlns:serif="http://www.serif.com/"><path style="fill:#000000;stroke-width:0.04556199"
     d="m 9.1052369,20.324771 c -2.368311,-0.24993 -4.694585,-1.286 -6.3384028,-3.01666 -1.433065,-1.50455 -2.38207204,-3.47791 -2.68784404,-5.5281 -0.101621,-1.7243 -0.118186,-3.5161595 0.614163,-5.1222295 0.87031104,-2.28445 2.62089204,-4.22701 4.85420184,-5.24931 2.624933,-1.3271804 5.8494891,-1.3878304 8.5345551,-0.19381 0.988244,0.44006 1.945328,0.98655 2.729074,1.73934 -0.654838,0.29924 -1.830793,0.99043 -2.56639,0.35409 -2.187541,-1.276 -4.9623991,-1.55945 -7.3084791,-0.55313 -2.098601,0.8217 -3.8166188,2.53411 -4.5768858,4.65698 -1.003479,2.46055 -0.629085,5.3913495 0.868789,7.5772695 0.9683318,1.40683 2.3747228,2.48516 3.9847258,3.06575 3.2251871,1.24532 7.1532381,0.10952 9.2637001,-2.60612 1.152453,-1.47557 1.759641,-3.32889 1.764427,-5.19651 -0.13002,-1.0486395 0.807323,-1.8157295 1.343194,-2.6225595 0.432261,0.62995 0.402881,1.72039 0.430397,2.5473995 0.122003,2.07809 -0.578711,4.12521 -1.707001,5.85295 -1.968709,2.96861 -5.668351,4.61232 -9.2022241,4.29465 z m -0.128773,-4.23293 c -1.395882,-0.1746 -2.720091,-0.98847 -3.333866,-2.27849 -0.273728,-0.60034 -1.399868,-2.32234 0.02438,-1.98772 1.398134,-0.43174 1.271915,1.19915 2.106733,1.83042 1.556537,1.51906 4.5269281,0.84908 5.2465591,-1.21566 0.529076,-1.31194 0.472317,-2.8542095 -0.03718,-4.1620395 -0.544727,-1.25566 -1.94588,-1.9905 -3.2932941,-1.78716 -1.281076,0.11395 -2.345477,1.07352 -2.78241,2.25155 -0.354803,0.52471 -1.641839,0.47827 -1.989756,0.15096 0.311313,-1.87506 1.73378,-3.56937 3.639304,-3.9471 1.4667961,-0.31977 3.0816971,-0.25663 4.3956001,0.53485 1.66321,0.36804 3.275152,-0.42519 4.867416,-0.78823 0.896275,-0.28014 1.994634,0.40854 1.200891,1.33667 -1.011419,1.49873 -2.431456,2.70815 -3.196066,4.3762195 -0.560911,0.83121 -0.739708,1.80746 -1.055878,2.73622 -0.62226,1.72228 -2.348587,2.89745 -4.169092,2.95283 -0.540117,0.0276 -1.0840891,0.0498 -1.6233351,-0.003 z m 1.9844821,-5.81988 C 9.9023169,10.108521 9.7842439,8.4477515 10.801382,8.1214615 c 1.027848,-0.41353 1.85048,1.08887 1.092762,1.82023 -0.226952,0.2560995 -0.594701,0.3898695 -0.933198,0.3302695 z"></path></svg>`    
    }); 	

//    $("#loading").modal("show");
    //console.log("SHOW LOADING ");
}

function hideLoadingWork() {

//    $("#loading").modal("hide");
    //console.log("HIDE LOADING ");
}

// Loading taking too long. 
// Drop the hold on the page
// and tell user may have to try again
// and that a problem has taken place.
//function errorLoadingWork() {

 // let spcer = "&nbsp;</BR>".repeat(2);
 // spcer += "<IMG STYLE=\"display:block;margin-left:auto;margin-right:auto;\" SRC=\"http://userspace.org/wp-content/uploads/2019/12/USO_tooktoolong.png\"></BR>";
 // spcer += "&nbsp;</BR>".repeat(2);
 // document.body.querySelector("#collections").innerHTML = spcer;
 // $("#loading").modal("hide");
    //console.log("HIDE LOADING ");
//}


// Will resolve after 30,000ms
let timeout_works = new Promise((resolve, reject) => {
  let wait = setTimeout(() => {
    //clearTimout(wait);
    //errorLoadingWorks();
    console.log("TIMEOUT");
    resolve('TIMEOUT');
  }, 60000)
})

function embedwork( site_url ) {
showLoadingWork(); 

// Run a timeout on second promise via race if process takes to long.
let loading_wait = Promise.race([

fetch( works_site+site_url )
       .then(res=> res.text())
       .then((html) => { 
       var els = $(html).find('#primary,script,style');
       $('#works').html(els);         
       })
       .catch( hideLoadingWork )
       .finally( hideLoadingWork ),
timeout_this		
])


} 

//Setup CLICK event listener
$('.works').click(function(e){
e.stopImmediatePropagation();
e.preventDefault();

switch(e.target.id) {
  case "FOSS":
    //console.log("SELECT FOSS");
    embedwork( '/foss' );
    return false;       //You have to return false from CLICK event to activate AJAX.
    break;
  case "DISTRO":
    //console.log("SELECT DISTRO");
    embedwork( '/distro' );
    return false;
    break;
  case "DESKTOP":
    //console.log("SELECT DE-WM");
    embedwork( '/desktop' );
    return false;
    break;
  default:
    return false;

}

});


// When page is finished loading run this.
window.addEventListener('load', (event) => {
   // Init for wordpress plugins
   $("body").removeClass("animsition");
   $('.tablepress-id-1').DataTable();
   //Set default page to show Virtualspace
   //console.log("GET:"+site+"/ram");
   embedwork( "/distro" );
   return false;
});

