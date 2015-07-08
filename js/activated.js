var _num = document.URL.split("?num=")[1];
if(!_num){
	
	document.getElementsByClassName("description")[0].innerHTML = 'Error! Please restart from the top page.';
}else if(_num==1){
	document.getElementsByClassName("description")[0].innerHTML = 'You will tweet a goodbye poem to the next unfollower. To deactivate, just surf back this website.';
}else{
	document.getElementsByClassName("description")[0].innerHTML = 'You will tweet a goodbye poem to the next '+_num+' unfollowers. To deactivate, just surf back this website.';
}