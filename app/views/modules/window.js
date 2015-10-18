define(function(){
	return {
		ui:function(id,label,ui){
			return {
				view:"window",
				id:id,
			    modal:true,
			    position:"center",
			    width:700,
			    height:500,
			    head:{
			        view:"toolbar", margin:-4, cols:[
			            { view:"label", id:"lbl_"+id, label: label },
			            { view:"icon", icon:"question-circle", click:"webix.message('About pressed')"},
			            { view:"icon", icon:"times-circle", click:function(){
			                this.getTopParentView().hide();
			            }}
			        ]
			    },
			    body: ui
			};
		}
	}
});
