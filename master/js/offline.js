// JavaScript Document
function searchObject(o,s)
{
	for(i in o)
	{
		if(o[i]==s) return i;
	}
	return null;
}
function oGetJSON(url,callback)
{
	var time= new Date().getTime();
	var pr=new Object; var success='success'; var failed='failed'; 
	var index='index';
	pr[time]=url;
	localforage.getItem(index).then(function(value) {
    if(value==null) 
	{
		localforage.setItem(index,pr);	
	}else
	{
		var vl=searchObject(value,url);
		if(vl==null)
		{
			$.getJSON(url,function(result,status) 
			{
				value[time]=url;
				localforage.setItem(index,value);
				localforage.setItem(time,result);
				callback(result,status);
			})
			
		}else
		{
			localforage.getItem(vl).then(function(value) 
			{
				if(value !=null)
				{
					callback(value,success);	
				}else
				{
					$.getJSON(url,function(result,status) 
					{
						localforage.setItem(time,result);
						callback(result,status);
					});
				}
			})
		}
		
	}
    console.log(value);
}).catch(function(err) {
    // This code runs if there were any errors
    console.log(err);
});
	//$.getJSON(url,callback);
	
}
$.fn.extend({
	oLoad:function(url,callback)
	{
		
	var time= new Date().getTime();
	var pr=new Object; var success='success'; var failed='failed'; 
	var index='pages';
	pr[time]=url;
	localforage.getItem(index).then(function(value) {
											 
    if(value==null) 
	{
		localforage.setItem(index,pr);	
	}else
	{
		alert(index);
		var vl=searchObject(value,url);
		if(vl==null)
		{
			$.get(url,function(result,status) 
			{
				value[time]=url;
				localforage.setItem(index,value);
				localforage.setItem(time,result);
				callback(result,status);
			})
			
		}else
		{
			localforage.getItem(vl).then(function(value) 
			{
				if(value !=null)
				{
					alert(value);
					callback(value,success);	
				}else
				{
					$.get(url,function(result,status) 
					{
						localforage.setItem(time,result);
						callback(result,status);
					});
				}
			})
		}
		
	}
    console.log(value);
}).catch(function(err) {
    // This code runs if there were any errors
    console.log(err);
});
	//$.getJSON(url,callback);
	
	}
})

