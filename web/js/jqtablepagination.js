 (function($){

	$.fn.tablePagination = function(settings) {
		var defaults = {  
			rowsPerPage : 10,
			currPage : 1,
			optionsForRows : [5,10,25,50,100],
			ignoreRows : [0]
		};  
		settings = $.extend(defaults, settings);
		
		return this.each(function() {
      var table = $(this)[0];
      var totalPagesId, currPageId, rowsPerPageId, firstPageId, prevPageId, nextPageId, lastPageId;
  
      var tblLocation = (defaults.topNav) ? "prev" : "next";

      var possibleTableRows = $.makeArray($('tbody tr', table));
      possibleTableRows.splice(0,1);
      var tableRows = $.grep(possibleTableRows, function(value, index) {
        return ($.inArray(value, defaults.ignoreRows) == -1);
      }, false)
      
      var numRows = tableRows.length
      var totalPages = resetTotalPages();
      var currPageNumber = (defaults.currPage > totalPages) ? 1 : defaults.currPage;
      if ($.inArray(defaults.rowsPerPage, defaults.optionsForRows) == -1)
        defaults.optionsForRows.push(defaults.rowsPerPage);
      console.log(defaults.rowsPerPage);
      
      function hideOtherPages(pageNum) {
        if (pageNum==0 || pageNum > totalPages)
          return;
        var startIndex = (pageNum - 1) * defaults.rowsPerPage;
        var endIndex = (startIndex + defaults.rowsPerPage - 1);
        $(tableRows).show();
        for (var i=0;i<tableRows.length;i++) {
          if (i < startIndex || i > endIndex) {
            $(tableRows[i]).hide()
          }
        }
      }
      
      function resetTotalPages() {
        var preTotalPages = Math.round(numRows / defaults.rowsPerPage);
        var totalPages = (preTotalPages * defaults.rowsPerPage < numRows) ? preTotalPages + 1 : preTotalPages;
       
        return totalPages;
      }
      
      function resetCurrentPage(currPageNum) {
        if (currPageNum < 1 || currPageNum > totalPages)
          return;
        currPageNumber = currPageNum;
        hideOtherPages(currPageNumber);
        //$((table)[tblLocation]().find(currPageId).val(currPageNumber))
      }
      
 
      
      function createPaginationElements() {


       var str = '<div class="pagination pagination-right"><ul>';
for (i = 0;i<totalPages;i++) {
 str +=   '<li class="pagination_link '+((i==0)?'active':'')+'" data-page='+i+' ><a href="#">'+(i+1)+'</a></li>';
}
  str += '</ul></div>';
  return str;
        
      }
      
      
			$(this).after(createPaginationElements());
		
      
      hideOtherPages(currPageNumber);
      
      $('.pagination_link').click(function() {
        $('.pagination_link').removeClass('active');
        resetCurrentPage($(this).data('page')+1);
        $(this).addClass('active');
      });

      
		})
	};		
})(jQuery);