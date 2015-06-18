var _num = document.URL.split("?num=")[1];
if(!_num){
	
	document.getElementById("desc").innerHTML = 'Error! Please restart from the top page.';
}else{
	document.getElementById("desc").innerHTML = ' It looks like your account already has Goodbye Unfollower installed.The following '+_num+' unfollowers will receive a Goodbye Poem from you.';
}