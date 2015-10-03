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
					view: "search", 
					placeholder:"Find Matakuliah by Kode or Name",
					on:{
						"onTimedKeyPress":function(){
							var value = this.getValue().toLowerCase();
							webix.$$("listtbkmk").filter(function(obj){						        
						        if (obj.KDKMKTBKMK.toLowerCase().indexOf(value) == 0 ) return true;
						        if (obj.NAKMKTBKMK.toLowerCase().indexOf(value) !== -1 ) return true;
						        return false;
							})
						}
					}
				}
				]
			},
			{
				view: "list",
				id: "listtbkmk",
				// url: apps.ApiProvider+"/api/tbkmk",
				select: true,
				template: "<div class='marker prodi_#KDPSTTBKMK#'></div>#KDKMKTBKMK# / #NAKMKTBKMK#",
			}
		]
	}
});