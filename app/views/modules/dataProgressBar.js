define(function(){
	return {
        emptydata:function(){
            if (!this.count()){
                webix.extend(this, webix.OverlayBox);
                this.showOverlay("<div style='margin:75px; font-size:20px;'>There is no data</div>");
            }
        },
        progressbar: {
    		onBeforeLoad:function(){
                this.showOverlay("Loading...");
            },
            onAfterLoad:function(){
                this.hideOverlay();
                if (!this.count()){
                    webix.extend(this, webix.OverlayBox);
                    this.showOverlay("<div style='margin:75px; font-size:20px;'>There is no data</div>");
                }
            }
    	}
    }
});
