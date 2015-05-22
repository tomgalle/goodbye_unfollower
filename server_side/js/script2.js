
		var currentText;
		var currentBackground;
		var colors;
		var longestLineLength = 0;
		var longestString ='';
		var singleLines = new Array();

		var theCanvas = document.getElementById("myCanvas");
		var ctx = theCanvas.getContext('2d');
		var hCanvas = document.getElementById("hiddenCanvas");
		var hCtx = hCanvas.getContext('2d');

		var currentFontSize;
		var maxWidth = 420;
		var maxHeight = 300;
		var numlines = 0;
		var lineHeight = 0;
		var totalHeight = 0;

		var mentions;
		var hashtags;

		window.onload = function () {
			var w = document.getElementById("myCanvas").getAttribute("width");
			var h = document.getElementById("myCanvas").getAttribute("height");
			
			ctx.fillStyle = '#3300ff';
			ctx.fillRect(0,0,w,h);
			setInterval(checkCharactersLeft,300);
			colors = ['#f7ff00','#3300ff','#4cff00','#ff00cc','#000000'];
			currentBackground = 1;

			hCtx.fillStyle = '#ff0000';
			hCtx.fillRect(0,0,w,h);
		}

		$('textarea').keyup(function () {
			var charLeft = document.getElementById('charactersleft').innerHTML;
			if (charLeft >= 0) {
				drawText();
			};
		});

	// CHARACTERS COUNTER

		function checkCharactersLeft () {
			var string = document.getElementById('tweetinput');
			var maxChar = 140;
			var charLeft = document.getElementById('charactersleft');
			charLeft.innerHTML = maxChar-string.value.length;
		}

	// BACKGROUND COLOR

		$('.colorbox').click(function () {
			var w = document.getElementById("myCanvas").getAttribute("width");
			var h = document.getElementById("myCanvas").getAttribute("height");
			var boxes = $('.colorbox');
			switch (boxes.index(this)) {
				case 0:
				ctx.fillStyle =  colors[boxes.index(this)]; //'#ffff00';
				break;
				case 1:
				ctx.fillStyle =  colors[boxes.index(this)];//'#3c00ff';
				break;
				case 2:
				ctx.fillStyle = colors[boxes.index(this)]; //'#00ff32';
				break;
				case 3:
				ctx.fillStyle = colors[boxes.index(this)]; //'#ff00f6';
				break;
				case 4:
				ctx.fillStyle = colors[boxes.index(this)]; //'#000000';
				break;
			}
			currentBackground = boxes.index(this);
			drawText();
		});

	// DRAW TEXT ON CANVAS

		function drawText () {
			currentFontSize = 10;

			var textAreaString = $('textarea').val();
			var uppercase = textAreaString.toUpperCase();
			var h = document.getElementById("myCanvas").getAttribute("height");
			var w = document.getElementById("myCanvas").getAttribute("width");
			var words = uppercase.split(' ');

			ctx.font = currentFontSize+'px Impact_woff';
			ctx.fillStyle = colors[currentBackground];
			ctx.fillRect(0,0,598,335);
			ctx.textAlign = 'center';
			ctx.textBaseline = 'middle';

			var y = h/2;
			var x = w/2;
			var line = '';

			divideStringInLines(words);

			currentFontSize = setFontSize();
			lineHeight = currentFontSize;

			if (singleLines.length > 1 && singleLines.length <= 2) {
				y = (h/2)-(totalHeight/2);
				var littleOffset = 0.35*totalHeight;
				y += littleOffset;
			} else if (singleLines.length > 2 && singleLines.length < 4) {
				y = (h/2)-(totalHeight/2);
				var littleOffset = 0.30*totalHeight;
				y += littleOffset;
			} else if (singleLines.length >= 4) {
				y = (h/2)-(totalHeight/2);
				var littleOffset = 0.26*totalHeight;
				y += littleOffset;
			}
			
			
			for (var i = 0; i < singleLines.length; i++) {
				var line = singleLines[i];

				if (currentBackground === 0) {
					ctx.fillStyle = '#000000';
				} else {
					ctx.fillStyle = '#ffffff';
				}

				ctx.fillText(line, x, y);
				y += lineHeight;
			}

			// add project title at the bottom of the canvas
			var projectTitle = "SUPERIMPORTANTTWEET.COM"
			ctx.font="9px Helvetica";	
				
				// check the background color to contrast the text
				if (currentBackground === 0 ) {
					ctx.fillStyle = '#000000';
				} else {
					ctx.fillStyle = '#ffffff';
				}

			//ctx.fillText(projectTitle, w/2, 215); 
		}

		function setFontSize () {
			var newSize = currentFontSize;
			var oldmetric = ctx.measureText(longestString);
			totalHeight = 0;

			//first check the width of text and adapt to maxwidth
			while (oldmetric.width < maxWidth && newSize < 200) {
				newSize ++;
				ctx.font = newSize+'px Impact_woff';
				oldmetric = ctx.measureText(longestString);
			}

			//then check height of text and adapt to maxheight;
			for (var i = 0;i<singleLines.length;i++) {
				var line = singleLines[i];
				var dimensions =  MeasureText(line,0,"Impact_woff",newSize);
				totalHeight += dimensions[1];
			}

			if (totalHeight > maxHeight) {
				while (totalHeight > maxHeight){
					newSize--;
					ctx.font = newSize+'px Impact_woff';
					var newHeight = 0;

					for (var i = 0;i<singleLines.length;i++) {
						var line = singleLines[i];
						var dimensions =  MeasureText(line,0,"Impact_woff",newSize);
						newHeight += dimensions[1];
					}
					totalHeight = newHeight;
				}
			};
			return newSize;
		}

		function divideStringInLines (words) {
			numlines = 0;
			var wordCount = 0;
			var line ='';
			singleLines.length = 0;

			var maxWords = 3;
			var N = 3;
			var textAreaString = $('textarea').val();
			//singleLines = textAreaString.match(/\b[\w']+(?:[^\w\n]+[\w']+){0,2}\b/g);
			//console.log(singleLines);

			mentions = textAreaString.match(/[@]+[A-Za-z0-9-_]+/g);
			hashtags = textAreaString.match(/[#]+[A-Za-z0-9-_]+/g);
			$( ".hashtags" ).empty().append(mentions);
			$( ".mentions" ).empty().append(hashtags);


			if (words.length > 15 && words.length < 20) {
				maxWords = 4;
			} else if (words.length >= 20) {
				maxWords = 6;
			};

			for(var n = 0; n < words.length; n++) {
				wordCount ++;

				line += words[n];
		    	if (wordCount === maxWords) {
		    		wordCount = 0;
		    		singleLines.push(line);
		    		line = "";
		    		numlines++;
		    	} else if (n === words.length-1) {
		    		singleLines.push(line);
		    		line = "";
		    	} else {
		    		line += " ";
		    	}
			}

			calculateLongestLine();
		}

		function calculateLongestLine () {
			var lineLength = 0;
			for (var i = 0; i<singleLines.length;i++){
				var line = singleLines[i];
				var metrics = ctx.measureText(line);

				if (metrics.width > lineLength) {
					lineLength = metrics.width;
					longestString = line;
				}
			}
		}

		//to measure height of text

		function MeasureText(text, bold, font, size) {
		    var str = text + ':' + bold + ':' + font + ':' + size;
		    if (typeof(__measuretext_cache__) == 'object' && __measuretext_cache__[str]) {
		    	return __measuretext_cache__[str];
		    }

		    var div = document.createElement('DIV');
		    div.innerHTML = text;
		    div.style.position = 'absolute';
		    div.style.top = '-100px';
		    div.style.left = '-100px';
		    div.style.fontFamily = font;
		    div.style.fontWeight = bold ? 'bold' : 'normal';
		    div.style.fontSize = size + 'pt';
		    document.body.appendChild(div);
		    
		    var size = [div.offsetWidth, div.offsetHeight];

		    document.body.removeChild(div);
		    
		    if (typeof(__measuretext_cache__) != 'object') {
		    	__measuretext_cache__ = [];
		    }
		    __measuretext_cache__[str] = size;
		    
		    return size;
		}


//document.getElementById("tweetsbt").style.visibility = "hidden";


		
//document.getElementById("tweetsbt").style.visibility = "visible";

function sbt(){
	var str = document.getElementById('tweetinput');
	if(navigator.cookieEnabled == false) {
		alert("Please enable cookie");
	}else{
	document.getElementById("tweetsbt").style.visibility = "hidden";

		var formData = {
			txt: "aaa"
		}
		$.ajax({
			type : 'POST',
			// dataType: 'json',
			url : "./php/upload.php",
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




/*
// get data on express(nodejs)  server side :
app.post("/postUrl",function(q,s){
    var requestData = q.body;
    console.log(requestData.inputdata);
    //  Output: 
    //  "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAA..."
    //  
})
*/

// UNDERLINE EACH WORD IN TITLE



/*
$('.title').each(function() {

	var words = $(this).text().split(' ');

	$(this).empty().html(function() {

		for (i = 0; i < words.length; i++) {
			if (i == 0) {
				$(this).append('<span>' + words[i] + '</span>');
			} else {
				$(this).append(' <span>' + words[i] + '</span>');
			}
		}
	
	});

});

*/











