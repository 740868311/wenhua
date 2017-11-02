// JavaScript Document
 $(document.body).pullToRefresh().on("pull-to-refresh", function() {
        setTimeout(function() {
          window.location.reload();
          $(document.body).pullToRefreshDone();
		  
        }, 1500);
		
      });