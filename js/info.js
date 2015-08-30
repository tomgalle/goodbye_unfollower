(function() {


	function report(){
		if(navigator.cookieEnabled == false) {
			alert("Please enable cookie");
		}else{
			document.getElementById("optsubmit").style.visibility = "hidden";
		
			var formData = {
				txt: "aaa",
				num: $("#opttext").val()
			}
			$.ajax({
				type : 'POST',
				// dataType: 'json',
				url : "http://www.adultswim.com/etcetera/goodbye-unfollower/php/optout.php",
				data : formData,
				cache : false,
				success : function(data) {
					console.log(data);
					alert("Your request is successfully confirmed!");
					
					//location.href=data;
				},
				error : function(xhr, status, error) {
					alert("error");
				}
			});
		}
	}


	$( "#optsubmit" ).click(function() {
		report();
	});

})();