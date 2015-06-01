(function() {

// GENERATE PAGES
function setPages() {
	var windowHeight = $(window).height();
	var windowWidth = $(window).width();
	function addPages(){ $(".section").css({ "height": windowHeight, "width": windowWidth });}
	function addpageOne(){ $(".pageone").css({ "height": windowHeight, "width": windowWidth });}
	addPages();
	addpageOne();
}
setPages();

//RESIZE
$( window ).resize(function() { setPages(); });



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

$(window).scroll(function(){
	var scrollTop = $(window).scrollTop();
	var parralax = -scrollTop * 0.5 + 'px';
	$('.videowrapper').css({ "bottom": parralax });
	// console.log(scrollTop);
	// console.log(parralax);
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



})();
















// just querying the DOM
var links = document.querySelectorAll(".itemlink");
// var links = document.querySelectorAll(".sideselector");
var clickLeft = document.querySelector(".goleft");
var clickRight = document.querySelector(".goright");
var wrapper = document.querySelector("#tweetswrapper");
// var sideselector = document.querySelector('')
 
// the activeLink provides a pointer to the currently displayed item
var activeLink = 0;


 
// setup the event listeners
for (var i = 0; i < links.length; i++) {
    var link = links[i];
    link.addEventListener('click', setClickedItem, false);
   
    // identify the item for the activeLink
    link.itemID = i;
}


// ARROW FUNCTIONS

 clickRight.addEventListener('click', nextArrow, false);
 clickLeft.addEventListener('click', previousArrow, false);

 
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


	if ( link.classList.contains('dots')) {
    	var position = link.getAttribute("data-pos");
    	wrapper.style.left = position;
	}

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
	if(activeLink<=0){activeLink = 3;}

	var link = links[activeLink];
	changePosition(link);



}
































