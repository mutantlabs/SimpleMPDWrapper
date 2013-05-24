$(document).ready(function(){ //put everything here:
	var	id,
		wellText = $('.well').html(),
		type,
		rank = 0;
	$("#verb").keydown(function() {
		 var value = $(this).val();
	});
	$("#verb").keyup(function() {
		 var value = $(this).val();
		 if(value)
			 {
			 updateWell(value);
			 }
		 else {
			 resetWell(wellText);
		 }
		
		});	
	
	function updateWell(value)
	{
		type = "get";
		$.ajax({
		     url:url + type +  "/" + method + "/" + value,
		     dataType: 'json',
		     cache: false,
		     success:function(json){
		    	 $('.well').html(json.taunt);
		    	 console.log(url + type + "/" + method + "/" + value);
		    	 console.log("response" , json);
		    	 $('.suffix').html(json.suffix_used);
		    	 $('.rank').html(json.rank);		    	 
		    	 id = json.suffix_id;
		        console.log(id);
		     },
		     error:function(){
		    	 console.log("no reponse");
		     },
		});
		//$('.well').load(url + "/" + method + "/" + value);
		//console.log(url + "/" + method + "/" + value);
		
		  
	}
	
	 $('.icon-thumbs-up').click(function(){
    	 voteUp(id);
    	
     });
	 
	 $('.icon-thumbs-down').click(function(){
    	 voteDown(id);
    	
     });
	
	function resetWell(wellText)
	{
		var wellText = $('.well').html(wellText);
	}
	
	function voteUp(id)
	{
		rank = $('.rank').html();
		type="vote"
		$.ajax({
		     url:url + type + "/voteUp/true/append/" + id,
		     dataType: 'json',
		     type: "POST",
		     cache: false,
		     success:function(json){
		    	 console.log(url + type + "/voteUp/true/append/" + id);
		    	 console.log("response" , json);
		    	 rank++;
		    	 $('.rank').html(rank)
		     },
		     error:function(){
		    	 console.log("no reponse");
		     },
		});
	}
	
	function voteDown(id)
	{
		rank = $('.rank').html();
		type="vote"
		$.ajax({
		     url:url + type + "/voteDown/true/append/" + id,
		     dataType: 'json',
		     type: "POST",
		     cache: false,
		     success:function(json){
		    	 console.log(url + type + "/voteDown/true/append/" + id);
		    	 console.log("response" , json);
		    	 rank--;
		    	 $('.rank').html(rank)
		     },
		     error:function(){
		    	 console.log("no reponse");
		     },
		});
	}
	
	
});
  		
