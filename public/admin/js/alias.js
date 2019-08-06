function change_alias( alias ){
	var str = alias; 
	str = str.replace(/ /g,"-");
	str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ|A|À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ/g,"a");
	str = str.replace(/B/g,"b");
	str = str.replace(/C/g,"c");
	str = str.replace(/đ|D|Đ/g,"d");
	str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ|E|È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ/g,"e");
	str = str.replace(/F/g,"f");
	str = str.replace(/G/g,"g");
	str = str.replace(/H/g,"h");
	str = str.replace(/ì|í|ị|ỉ|ĩ|I|Ì|Í|Ị|Ỉ|Ĩ/g,"i");
	str = str.replace(/J/g,"j");
	str = str.replace(/K/g,"k");
	str = str.replace(/L/g,"l");
	str = str.replace(/M/g,"m");
	str = str.replace(/N/g,"n");
	str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ|O|Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ/g,"o");
	str = str.replace(/P/g,"p");
	str = str.replace(/Q/g,"q");
	str = str.replace(/R/g,"r");
	str = str.replace(/S/g,"s");
	str = str.replace(/T/g,"t");
	str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ|U|Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ/g,"u");
	str = str.replace(/V/g,"v");
	str = str.replace(/W/g,"w");
	str = str.replace(/X/g,"x");
	str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ|Y|Ỳ|Ý|Ỵ|Ỷ|Ỹ/g,"y");
	str = str.replace(/Z/g,"z");	
	str = str.replace(/!|@|%|\^|\*|\(|\)|\+|\=|<|>|\?|\/|,|\.|\:|\;|\'|\"|\“|\”|\&|\#|\[|\]|~|‘|’|–|\$|_/g,"");
	str = str.replace(/--+/gi,"-");
	str = str.replace(/^-/i,"");
	str = str.replace(/-$/gi,"");
	return str;
}