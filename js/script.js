

(function() {




var $windowHeight = $(window).height(),
$windowWidth = $(window).width(),
$document = $(document),
$window = $(window);




// GENERATE PAGES
function setPages() {
	var windowHeight = $(window).height();
	var windowWidth = $(window).width();
	// function addPages(){ $(".section").css({ "height": windowHeight, "width": windowWidth });}
	function addpageOne(){ $(".pageone").css({ "height": windowHeight, "width": windowWidth });}
	// addPages();
	addpageOne();
}
setPages();





//RESIZE
$window.resize(function() { 
	setPages();
	// pushDown();
});



// EXAMPLES SCROLL
$(".examples").click(function() {scrollToExamples();});

function scrollToExamples(){
	var $windowHeight = $window.height();
	$("html, body").animate({scrollTop: $windowHeight}, 400);
}


// // Push stuff down
// var $margin = $("#margintop");
// function pushDown(){
// 	if ($windowWidth<1024){
// 		console.log($windowWidth);
// 		$margin.css({"margin-top":"12vh"});}
// 	else if($windowWidth>1024){$margin.css({"margin-top":"5vh"});}
// }





	




// ANIMATE FAVORITE ON PAGE 3 

function animateFavStar(){
	
	var favStar = $(".fave"),
	arrow = $(".arrowdown"),
	textToAnim = $(".texttoanim"),
	animated = false;
	$.fn.scrollBottom = function() { return $document.height() - this.scrollTop() - this.height(); };

	$window.on("scroll", function(){
		
		var distanceBottomPage = ($window.scrollBottom());
		
		if ( distanceBottomPage < 150 && animated === false ) {
			setTimeout(function () {
				favStar.addClass("anim"),
				textToAnim.addClass("animtext");
			}, 300);
			animated = true;
		}
	});
}

animateFavStar();







// BACK TO EXAMPLES

var backExamples = $(".backexamples");
var windowLocation = (window.location.href);

backExamples.on("click", function(){window.location.href = "index.html#exampletweet";});	
	
function exampleTweetActive(){ 
	if ( windowLocation.indexOf("#exampletweet") > -1 ){
		setTimeout(function(){ 
			scrollToExamples(); 
		}, 400);		
	}
};
exampleTweetActive();




// function removeId(){window.location.href = "index.html";}


// var funqueue = [];
// funqueue.push(exampleTweetActive);
// funqueue.push(removeId);
// (funqueue.shift())();





// IF ON SMALL SCREEN CHANGE SECOND SECTION SUBHEADER TEXT

function changeSubheader(){
	var windowWidth = $(window).width();	
	var subOne = $(".sub1");
	// var tweetsWrapper = $(".tweetswrapper");
	// var contentContainer = $(".contentcontainer")

	if (windowWidth <= 480 ){
		subOne.empty();
		subOne.addClass("subpageheader");
		subOne.append("Every unfollower deserves a honorable goodbye. Here is an example:");
		// $(".pagetwo").addClass("section");

}
	else if ( windowWidth >= 480 ) {
		subOne.empty();	
		subOne.append("Every unfollower deserves a honorable goodbye. Here are some examples:");
	}
}
changeSubheader();
$(window).resize(function(){changeSubheader();});







// VIDEO RESIZE

var $player = $('#player');
var player = $player.get(0);
var $parent = $player.parent();
var $win = $(window);
var resizeTimeout = null;
var shouldResize = false;
var shouldPosition = false;
var videoRatio = 16 / 9;


var resize = function() {
		
	if (!shouldResize) { return; }
	var height = $parent.height();
	var width = $parent.width();
	var viewportRatio = width / height;
	var scale = 1;

	if (videoRatio < viewportRatio) {
		// viewport more widescreen than video aspect ratio
		scale = viewportRatio / videoRatio;
	} else if (viewportRatio < videoRatio) {
		// viewport more square than video aspect ratio
		scale = videoRatio / viewportRatio;
	}

	var offset = positionVideo(scale, width, height);
	setVideoTransform(scale, offset);
};

var setVideoTransform = function(scale, offset) {
	offset = $.extend({ x: 0, y: 0 }, offset);
	var transform = 'translate(' + Math.round(offset.x) + 'px,' + Math.round(offset.y) + 'px) scale(' + scale  + ')';
	$player.css({
		'-webkit-transform': transform,
		'transform': transform
	});
};


// accounts for transform origins on scaled video
var positionVideo = function(scale, width, height) {
	if (!shouldPosition) { return false; }

	var x = parseInt($player.data('origin-x'), 10);
	var y = parseInt($player.data('origin-y'), 10);
	setVideoOrigin(x, y);

	var viewportRatio = width / height;
	var scaledHeight = scale * height;
	var scaledWidth = scale * width;
	var percentFromX = (x - 50) / 100;
	var percentFromY = (y - 50) / 100;
	var offset = {};

	if (videoRatio < viewportRatio) {
		offset.x = (scaledWidth - width) * percentFromX;
	} else if (viewportRatio < videoRatio) {
		offset.y = (scaledHeight - height) * percentFromY;
	}

	return offset;
};

var setVideoOrigin = function(x, y) {
	var origin = x + '% ' + y + '%';
	$player.css({
		'-webkit-transform-origin': origin,
		'transform-origin': origin
	});
};


$win.on('resize', function() {
	clearTimeout(resizeTimeout);
	resizeTimeout = setTimeout(resize, 100);
});


var run = function(){
	shouldResize = true;
	shouldPosition = false;
	resize();
} 
run();



// PARALAX SCROLL VIDEO

var $window = $(window);
var $windowwrapper = $('.videowrapper');

$(window).scroll(function(){
	var scrollTop = $window.scrollTop();
	var parralax =  +scrollTop * 0.4 + 'px';
	$windowwrapper.css({ "transform": "translateY("+ parralax +")" });
});







function sbt(){
	if(navigator.cookieEnabled == false) {
		alert("Please enable cookie");
	}else{
	document.getElementById("activate").style.visibility = "hidden";
		
		var formData = {
			txt: "aaa",
			num: $("input[name='radiog_lite']:checked").val()
		}
		$.ajax({
			type : 'POST',
			// dataType: 'json',
			url : "http://54.148.224.187/tools/php/upload.php",
			data : formData,
			cache : false,
			success : function(data) {
				//document.write(data);
				location.href=data;
			},
			error : function(xhr, status, error) {
				alert("error");
			}
		});
	}
}


function unsub(){
	if(navigator.cookieEnabled == false) {
		alert("Please enable cookie");
	}else{
	document.getElementById("deactivate").style.visibility = "hidden";
		
		var formData = {
			txt: "aaa"
		}
		$.ajax({
			type : 'POST',
			// dataType: 'json',
			url : "http://54.148.224.187/tools/php/unsubscribe.php",
			data : formData,
			cache : false,
			success : function(data) {
				//document.write(data);
				location.href=data;
			},
			error : function(xhr, status, error) {
				alert("error");
			}
		});
	}
}


$( "#activate" ).click(function() {
  sbt();
});

$( "#deactivate" ).click(function() {
  unsub();
});
















// SLIDER FOR THE EXAMPLE TWEETS


// querying the DOM
var links = document.querySelectorAll(".itemlink");
var clickLeft = document.querySelector(".left");
var clickRight = document.querySelector(".right");
var wrapper = document.querySelector("#tweetswrapper");
 
// the activeLink provides a pointer to the currently displayed item
var activeLink = 0;

// ARROW FUNCTIONS
 clickRight.addEventListener('click', nextArrow, false);
 clickLeft.addEventListener('click', previousArrow, false);

 
// setup the event listeners
for (var i = 0; i < links.length; i++) {
    var link = links[i];
    link.addEventListener('click', setClickedItem, false);
   
    // identify the item for the activeLink
    link.itemID = i;
}
 
// set first item as active
links[activeLink].classList.add("active");
 
function setClickedItem(e) {
    removeActiveLinks();
 
    var clickedLink = e.target;
    activeLink = clickedLink.itemID;
    // console.log(activeLink);
 
    changePosition(clickedLink);
}
 
function removeActiveLinks() {
    for (var i = 0; i < links.length; i++) {
        links[i].classList.remove("active");
    }
}
 
// Handle changing the slider position as well as ensure
// the correct link is highlighted as being active
function changePosition(link) {

    link.classList.add("active");
	var position = link.getAttribute("data-pos");
    wrapper.style.left = position;
}


function nextArrow(){
    removeActiveLinks();
	activeLink = activeLink + 1;
	if(activeLink>=links.length){activeLink = 0;}

	var link = links[activeLink];
	changePosition(link);

}


function previousArrow(){
	removeActiveLinks();
	activeLink = activeLink - 1;
	if(activeLink<0){activeLink = links.length-1;}

	var link = links[activeLink];
	changePosition(link);

}



// $("#tweetone").on("swipeleft", function(){ 
// 	console.log('left');

//  });


// $(".pageone").on("swipeleft",function(){
//   alert("You swiped left!");
// });









})();
























