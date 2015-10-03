define(function(){
	var form =  {
		view: "form",
		id: "formTrnlm",
		dataFeed:"http://localhost/core/api/trlsm",
		elementsConfig:{
			labelWidth: 130
		},
		scroll: true,
		elements:[
			{view: "text", name: "NIMHSTRLSM", label: "NIM"},
			{view: "text", name: "name", label: "Name"},
			{view: "text", name: "price", label: "Price"},
			{view: "richselect", name: "category", label:"Category", vertical: true, options:[
				{id:2, value: "Home furniture"},
				{id:3, value: "Office furniture"},
				{id:1, value: "Wood furniture"}
			]},
			{view:"richselect", name:"status", value: "all",label: "Status", options:[
				{id:"1", value:"Published"},
				{id:"2", value:"Not published"},
				{id:"0", value:"Deleted"}
			]},
			{view: "checkbox", name: "in_stock", label: "In stock",value: 1},
			{ view: "label", label: "Full description", height:30},
			{}
		]
	}

	return {
		$ui:form
	};
});