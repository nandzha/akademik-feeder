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
						placeholder:"Find Dosen by NIDN or Name",
						on:{
							"onTimedKeyPress":function(){
								var value = this.getValue().toLowerCase();
							    webix.$$("listTbdos").filter(function(obj){
							    	
						        	if (obj.NIDNNTBDOS.toLowerCase().indexOf(value)== 0 ) return true;
							        if (obj.nm_ptk.toLowerCase().indexOf(value)!== -1 ) return true;
							        return false;
							    })
							}
						}
					}
				]
			},
			{
				view: "list",
				id: "listTbdos",
				// url:"http://localhost/core/api/tbdos",
				// save:"http://localhost/core/api/tbdos/save",
				select: true,
				template: "<div class='marker prodi_#KDPSTTBDOS#''></div>#NIDNNTBDOS# / #nm_ptk#",
			}
		]
	}
});