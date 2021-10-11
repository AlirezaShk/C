class BaseArchiveCrawlerCurrentFilters{
    /**
    * Archive Crawler Current Filters
    * 
    * This Vue component is in charge of hard filters (submitted
    * filters through api, read from session).
    * 
    **/
    constructor(apply, filters, type) {
    	this.apply = apply;
    	this.filters = filters;
    	this.type = type;
    }
    /**
    * Mark a hard filter. If it is already marked, send request to 
    * api.{type}.delete-filter to delete a filter.
    **/
    
	mark(e) {
		let filter = e.target;
		if (!$(filter).hasClass('marked-for-delete')) {
			$(filter).addClass('marked-for-delete');
			setTimeout(function() {
				$(filter).removeClass('marked-for-delete');
			}, 2000);
		} else {
			let url =  "/api/" + this.type + "/filter";
            $.ajax({
                url: url,
                type: 'DELETE',
                data: {
					key: $(filter).data('key'),
				},
                dataType: 'json',
                success: function (data) {
                    if(data.success) {
						location.reload();
                    }
                }
            });
		}
	}

    /**
    * Apply the Hard Filter
    **/

    hardFilterIt() {
        let filters = $("#currentFilters");
        let each = $("#currentFilters span");
        let table = $(".results-table")[0];
        let rows = $(table).find("tbody > tr:not(#zero-result):not(#table-loader)");
        $(rows).removeClass('filtered');
        for (let i = 0; i < each.length; i++) {
            let parts = each[i].textContent.split(':');
            for(let j = 0; j < rows.length; j++) {
                //case insensitive search for the input value inside each row of the results table
                if (this.apply(rows[j], parts[1].trim(), $(each[i]).data('code')) == -1) {
                    $(rows[j]).addClass('filtered');
                }
            }
            this.formatFilters(each[i]);
        }
    }
	mounted() {
		this.hardFilterIt();
		this.isMounted = true;
	}
}