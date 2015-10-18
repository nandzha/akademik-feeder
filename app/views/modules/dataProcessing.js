define(function(){
	return {
		onAfterSync: function(id, text, data){
        	// var res = data.json();
        	var response = data.xml(), res = response.data;
		    webix.message({ type:"default",
		    	text: "[MSG] " + res.action.type,
		    	expire:5000
		    });
		},

		onAfterSaveError: function(id, status, res, details){
			// var operation = this.getItemState(id).operation; //operation that was performed
			// var response = details.text.xml();
			webix.modalbox({
		        type:"alert-error",
		        width: 500,
		        height: 200,
		        title:"[" + res.type+ "]" + " Error Messages",
		        text: res.details,
		        buttons:["Ok"],
		   	});
			// console.log(details);
		}
	}
});
