define(function(){
	return {
		rows:[
			{
				view: "form",
				paddingX:5,
				paddingY:5,
				margin: 2,
				borderless:true,
				rows:[
					{
						view: "search" ,
						placeholder:"Find Mahasiswa by Nim or Name",
						on:{
							"onTimedKeyPress":function(){
								var value = this.getValue().toLowerCase();
							    webix.$$("listmsmhs").filter(function(obj){
							    	console.log(obj.nipd);
							    	if (obj.nipd.toLowerCase().indexOf(value)== 0 ) return true;
							     	if (obj.nm_pd.toLowerCase().indexOf(value)!== -1 ) return true;
							        return false;
							    })
							}
						}
					}
				]
			},
			{
				view: "dataview",
				id: "listmsmhs",
				select: true,
				type:{ width:350,height: 35 },
				xCount:1,
				template: "<div class='marker prodi_#nipd#''></div>#nipd# / #nm_pd#",
				url:"./lst"
			}
		]
	}
});